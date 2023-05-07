import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/models/prescript.modal.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/data/source/remote/doctor/patient_data.dart';
import 'package:roshetta_app/view/screens/patient/prescript_details.dart';
import 'package:roshetta_app/view/widgets/clinics/add_medicines.dart';
import 'package:roshetta_app/view/widgets/clinics/add_disease.dart';

class DoctorPatientDetailsController extends GetxController {
  final auth = Get.find<AuthenticationController>();
  final ScrollController scrollController =
      ScrollController(initialScrollOffset: 0.0);
  late Rx<RequestStatus> infoStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> prescriptStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> submitStatus = RequestStatus.none.obs;
  DoctorPatientData requests = DoctorPatientData(Get.find<Crud>());
  final diseases = [].obs;
  final prescripts = [].obs;
  final medicines = [].obs;
  final Rx<Patient?> patient = Rx<Patient?>(null);
  late TextEditingController diseaseName = TextEditingController();
  late TextEditingController diseasePlace = TextEditingController();
  late TextEditingController patientIdTextField = TextEditingController();
  late TextEditingController reappointmentDate = TextEditingController();
  late TextEditingController medicineName = TextEditingController();
  late TextEditingController medicineSize = TextEditingController();
  late TextEditingController medicineDuration = TextEditingController();
  late TextEditingController medicineDescription = TextEditingController();
  late RxString appointmentId = "".obs;
  late RxString patientId = "".obs;
  late RxString clinicId = "".obs;
  late RxString diseaseId = "".obs;
  late RxString formType = "new".obs;
  late RxInt currentForm = 0.obs;
  late RxList formsWidgets = [
    AddDiseaseForm(key: UniqueKey()),
    AddMedicinesForm(key: UniqueKey())
  ].obs;

  @override
  void onInit() async {
    super.onInit();
    if (Get.currentRoute == AppRoutes.doctorPatient) {
      await getPatientDetails();
      patientIdTextField.text = patientId.value;
    }
  }

  setClinicAppoindIds({
    required String appoint,
    required String clinic,
  }) {
    appointmentId.value = appoint;
    clinicId.value = clinic;
  }

  checkIDArgumants() {
    if ((checkArgument("patient_id") == null && patientId.isEmpty) ||
        (checkArgument("clinic_id") == null && clinicId.isEmpty) ||
        (checkArgument("appointment_id") == null && appointmentId.isEmpty)) {
      infoStatus.value = RequestStatus.failure;
      return;
    }
    if (patientId.isEmpty) {
      patientId.value = checkArgument("patient_id");
    }
    if (clinicId.isEmpty) {
      clinicId.value = checkArgument("clinic_id");
    }
    if (appointmentId.isEmpty) {
      appointmentId.value = checkArgument("appointment_id");
    }
  }

  getPatientDetails() async {
    checkIDArgumants();
    infoStatus.value = RequestStatus.loading;
    var response = await requests.getPatientDetails(getToken(auth)!,
        getCookie(auth)!, clinicId.value, patientId.value, appointmentId.value);
    infoStatus.value = checkResponseStatus(response);
    print(response);
    if (infoStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        infoStatus.value = RequestStatus.empty;
        return;
      }

      patient.value = Patient.fromJson(response["Data"]["patient"]);
      List? diseasesResponse = response["Data"]["disease"];
      if (diseasesResponse != null) {
        diseases.addAll(response["Data"]["disease"].toList());
      }
    } else {
      handleSnackErrors(response);
    }
  }

  changeCurrentForm(int index) {
    currentForm.value = index;
  }

  changeTypeForm(String type, {String? diseaesId}) {
    formType.value = type;
    if (type == "new") {
      diseaseId.value = "";
    } else if (type == "rediscovery") {
      diseaseId.value = diseaesId!;
    }
  }

  onPickReappointmentDate(context) async {
    DateTime? date = await customDatePicker(context,
        initialTime: DateTime.now().add(const Duration(days: 1)),
        first: DateTime.now().subtract(const Duration(days: 0)),
        last: DateTime(2500),
        initialMode: DatePickerMode.day);
    reappointmentDate.text =
        date != null ? DateFormat("yyyy-MM-dd").format(date) : "";
  }

  onAddMedicine({String? formType, int? itemIndex}) {
    Map medicine = {
      "name": medicineName.text,
      "size": medicineSize.text,
      "duration": medicineDuration.text,
      "description": medicineDescription.text
    };

    if (formType == "add") {
      medicines.add(medicine);
    } else {
      medicines[itemIndex!] = medicine;
    }

    onClearMedicineInputs();
  }

  onDeleteMedcine(item) {
    medicines.remove(item);
  }

  onEditMedicine(item) {
    medicineName.text = item["name"];
    medicineSize.text = item["size"];
    medicineDuration.text = item["duration"];
    medicineDescription.text = item["description"];
  }

  onClearMedicineInputs() {
    medicineName.clear();
    medicineSize.clear();
    medicineDuration.clear();
    medicineDescription.clear();
  }

  onSubmitPrescript() async {
    submitStatus.value = RequestStatus.loading;
    var response = await requests.addPrescript(
        getToken(auth)!,
        getCookie(auth)!,
        checkArgument("clinic_id") ?? clinicId.value,
        patientIdTextField.text,
        checkArgument("appointment_id") ?? appointmentId.value,
        type: formType.value,
        rediscoveryDate: getParsedDate(reappointmentDate.text).toString(),
        medicines: medicines,
        diseaseId: diseaseId.value,
        diseaseName: diseaseName.text,
        diseasePlace: diseasePlace.text);
    submitStatus.value = checkResponseStatus(response);

    print(response);
    if (submitStatus.value == RequestStatus.success) {
      if (formType.value == "new") {
        diseases.insert(0, {
          "disease_id": response["Data"]["disease_id"],
          "name": diseaseName.text,
          "place": diseasePlace.text,
          "isNew": true
        });
      } else {
        prescripts.insert(0, {
          "prescript_id": response["Data"]["id"],
          "prescript_ser_id": response["Data"]["ser_id"],
          "created_date": response["Data"]["date"],
          "disease_name": response["Data"]["disease_name"],
          "isNew": true
        });
        update();
      }
      patient.value!.appointCase = "2";
      patient.refresh();
      Get.back(closeOverlays: true);
      clearPrescriptInputs();
      snackbar(title: "تم الاضافة", content: response["Message"]);
    } else {
      handleSnackErrors(response);
    }
  }

  // Prescriptions Screen
  getDiseasePrescripts() async {
    prescriptStatus.value = RequestStatus.loading;
    var response = await requests.getDiseasePrescript(
        getToken(auth)!, getCookie(auth)!, clinicId.value, diseaseId.value);
    prescriptStatus.value = checkResponseStatus(response);
    print(response);
    if (prescriptStatus.value == RequestStatus.success) {
      prescripts.clear();
      if (response["Data"] == null) {
        prescriptStatus.value = RequestStatus.empty;
        return;
      }
      prescripts.addAll(response["Data"].toList());
    } else {
      handleSnackErrors(response);
    }
  }

  getDiseasePrescriptDetails(String prescriptId) async {
    prescriptStatus.value = RequestStatus.loading;
    var response = await requests.getDiseasePrescriptDetails(
        getToken(auth)!, getCookie(auth)!, clinicId.value, prescriptId);
    prescriptStatus.value = checkResponseStatus(response);
    print(response);
    if (prescriptStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        prescriptStatus.value = RequestStatus.success;
        return;
      }
      Prescript prescript =
          Prescript.fromJson(response["Data"]["prescript_data"][0]);
      prescript.medicineData = response["Data"]["medicine_data"];
      Get.to(() => PrescriptDetails(prescript: prescript));
    } else {
      prescriptStatus.value = RequestStatus.success;
      handleSnackErrors(response);
    }
  }

  clearPrescriptInputs() {
    diseaseName.clear();
    diseasePlace.clear();
    reappointmentDate.clear();
    medicineName.clear();
    medicineSize.clear();
    medicineDuration.clear();
    medicineDescription.clear();
    medicines.clear();
  }

  @override
  void onClose() {
    diseaseName.dispose();
    diseasePlace.dispose();
    patientIdTextField.dispose();
    reappointmentDate.dispose();
    medicineName.dispose();
    medicineSize.dispose();
    medicineDuration.dispose();
    medicineDescription.dispose();
    super.onClose();
  }
}
