import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/controllers/patient/appointments_controllers.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/data/models/clinic.modal.dart';
import 'package:roshetta_app/data/source/remote/auth/createaccount_data.dart';
import 'package:roshetta_app/data/source/remote/patient/clinics_data.dart';
import 'package:roshetta_app/view/widgets/auth/auth_dialogs.dart';
import 'package:roshetta_app/view/widgets/patient/appointment_form.dart';

class PatientClinicsController extends GetxController {
  GlobalKey<FormState> formkey = GlobalKey<FormState>();
  final auth = Get.find<AuthenticationController>();
  late RxString spcialist = "".obs;
  final spcialistsList = <DropdownMenuItem<String>>[].obs;
  late Rx<RequestStatus> specialistsStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> clinicssStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> clinicDetailsStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> appointmentStatus = RequestStatus.none.obs;
  CreateAccountData specilaistsRequest = CreateAccountData(Get.find<Crud>());
  PatientClinicData requests = PatientClinicData(Get.find<Crud>());
  final clinics = [].obs;
  late TextEditingController appointDate = TextEditingController();
  final clinic = Rx<ClinicModal?>(null);

  @override
  onInit() async {
    await getSpecilists();

    await getClinics();

    super.onInit();
  }

  getSpecilists() async {
    spcialistsList.add(const DropdownMenuItem(
        child: DropdownMenuItem(value: "", child: Text("كل التخصصات"))));

    String? localSpecialists = auth.getStorage.getString("specialists");
    if (localSpecialists != null) {
      List data = json.decode(localSpecialists);
      spcialistsList.addAll(data
          .map((specialist) => DropdownMenuItem(
              value: specialist["ar_name"].toString(),
              child: Text(specialist["ar_name"].toString())))
          .toList());
    } else {
      specialistsStatus.value = RequestStatus.loading;

      var response = await specilaistsRequest.getSpecialists();
      specialistsStatus.value = checkResponseStatus(response);

      if (specialistsStatus.value == RequestStatus.success) {
        List data = response["Data"];
        auth.getStorage.setString("specialists", json.encode(data));
        spcialistsList.value = data
            .map((specialist) => DropdownMenuItem(
                value: specialist["ar_name"].toString(),
                child: Text(specialist["ar_name"].toString())))
            .toList();
      } else {
        snackbar(
            title: "حدثت مشكلة", content: "لا يمكن عرض التخصصات الطبيه الان");

        spcialistsList.value = [
          const DropdownMenuItem(
              value: "error",
              child: Text(
                "حدثت مشكلة في اظهار التخصصات",
                textAlign: TextAlign.center,
              ))
        ];
      }
    }
  }

  getClinics() async {
    clinicssStatus.value = RequestStatus.loading;
    var response = await requests.getClinics(getToken(auth)!, spcialist.value);
    clinicssStatus.value = checkResponseStatus(response);

    print(response);
    if (clinicssStatus.value == RequestStatus.success) {
      print("success");
      if (response["Data"] == null) {
        clinicssStatus.value = RequestStatus.empty;
        return;
      }
      clinics.addAll(response["Data"].toList());
    } else {
      snackbar(title: "حدثت مشكلة", content: response["Message"]);
    }
  }

  updateClinicList(String id,
      {int? appointmentStatus, String? appointmentDate}) {
    int index = clinics.indexWhere((element) => element["clinic_id"] == id);
    clinics[index]["appoint_case"] =
        appointmentStatus ?? clinics[index]["appoint_case"];
    clinics[index]["appoint_date"] =
        appointmentDate ?? clinics[index]["appoint_date"];
    clinics.refresh();
  }

  onSpecialistChange(String value) async {
    if (clinics.isNotEmpty && spcialist.value.isEmpty && value.isEmpty) {
      return;
    }

    spcialist.value = value;

    if (clinics.isNotEmpty) {
      clinics.clear();
    }

    await getClinics();
  }

  displayAppointment(item) {
    print("display date : ${item["appoint_date"]}");
    Get.defaultDialog(
        contentPadding: const EdgeInsets.fromLTRB(15, 15, 15, 0),
        content: AuthDiologs(
            icon: Icons.date_range,
            title: appointDate.text.isNotEmpty
                ? appointDate.text
                : item["appoint_date"],
            content: item["name"]));
  }

  addAppointment(context, item) {
    appointDate.clear();
    Get.bottomSheet(Obx(() => PatientAppointmentForm(
        status: appointmentStatus.value,
        name: item["name"],
        appointController: appointDate,
        onSubmit: () async {
          await submitAddAppointment(item["clinic_id"]);
        })));
  }

  submitAddAppointment(String id) async {
    if (appointDate.text.isNotEmpty) {
      appointmentStatus.value = RequestStatus.loading;
      var response =
          await requests.addAppointment(getToken(auth)!, appointDate.text, id);
      appointmentStatus.value = checkResponseStatus(response);
      print(response);
      if (appointmentStatus.value == RequestStatus.success) {
        if (Get.isBottomSheetOpen == true) {
          Get.back();
        }
        updateClinicList(id,
            appointmentStatus: 0, appointmentDate: appointDate.text);
        if (clinic.value != null) {
          clinic.value!.appointCase = 0;
          clinic.refresh();
        }

        snackbar(title: "تم الحجز", content: response["Message"]);
      } else {
        snackbar(
            color: Colors.red,
            title: "حدثت مشكلة",
            content: response["Message"]);
      }
    } else {
      snackbar(
          color: Colors.red,
          title: "حدثت مشكله",
          content: "يجب اختيار تاريخ محدد اولا للحجز");
    }
  }

  goToClinicDetails(String id) async {
    clinic.value = null;
    Get.toNamed(AppRoutes.patientClinicDetails);
    clinicDetailsStatus.value = RequestStatus.loading;
    var response = await requests.getClinicDetails(getToken(auth)!, id);
    clinicDetailsStatus.value = checkResponseStatus(response);
    print(response);
    if (clinicDetailsStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        clinicDetailsStatus.value = RequestStatus.empty;
      } else {
        clinic.value = ClinicModal.fromJson(response["Data"]);
      }
    } else {
      snackbar(
          color: Colors.red, title: "حدثت مشكلة", content: response["Message"]);
    }
  }
}
