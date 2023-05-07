import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/controllers/doctor/clinics_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/date_functions.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/data/models/clinic.modal.dart';
import 'package:roshetta_app/data/source/remote/auth/createaccount_data.dart';
import 'package:roshetta_app/data/source/remote/doctor/clinic_data.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:time_range_picker/time_range_picker.dart';

class AddClinicController extends GetxController {
  GlobalKey<FormState> formkey = GlobalKey<FormState>();
  final auth = Get.find<AuthenticationController>();
  late RxString imgSrc = AssetPaths.emptyIMG.obs;
  late Rx<XFile?> imageFile = Rx<XFile?>(null);
  late TextEditingController name = TextEditingController();
  late TextEditingController phone = TextEditingController();
  late TextEditingController price = TextEditingController();
  late TextEditingController rangeTimeController = TextEditingController();
  TimeRange? selectedTime;
  late RxString spcialist = "".obs;
  late TextEditingController location = TextEditingController();
  late RxString governorate = "".obs;
  final spcialistsList = <DropdownMenuItem<String>>[].obs;
  final governorateList = <DropdownMenuItem<String>>[].obs;
  late Rx<RequestStatus> specialistsStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> addClinicStatus = RequestStatus.none.obs;
  CreateAccountData specilaistsRequest = CreateAccountData(Get.find<Crud>());
  ClinicData clinicRequests = ClinicData(Get.find<Crud>());
  final RxBool isEdit = false.obs;
  final RxString clinicId = "".obs;
  late DoctorClinicsController? clinicsController;

  @override
  onInit() async {
    governorateList.addAll((await StaticData.getGoverments()).toList());
    await getSpecilists();
    checkEditOrAdd();
    super.onInit();
  }

  // When Create

  getSpecilists() async {
    String? localSpecialists = auth.getStorage.getString("specialists");
    if (localSpecialists != null) {
      List data = json.decode(localSpecialists);
      spcialistsList.value = data
          .map((specialist) => DropdownMenuItem(
              value: specialist["ar_name"].toString(),
              child: Text(specialist["ar_name"].toString())))
          .toList();
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

  onSelectStartTime() async {
    TimeRange? timeRange = await customTimeRangePicker(
        start: selectedTime?.startTime, end: selectedTime?.endTime);
    if (timeRange != null) {
      selectedTime = timeRange;
      rangeTimeController.text = setTimeToField(selectedTime);
    }
  }

  onGovernmentChange(String value) {
    governorate.value = value;
  }

  onSpecialistChange(String value) {
    spcialist.value = value;
  }

  onSubmit() async {
    if (formkey.currentState!.validate()) {
      formkey.currentState!.save();
      addClinicStatus.value = RequestStatus.loading;
      ClinicModal clinic = ClinicModal(
          name: name.text,
          // logo: logo,
          specialist: spcialist.value,
          price: price.text,
          phoneNumber: phone.text,
          startWorking: selectedTime!.startTime.format(Get.context!),
          endWorking: selectedTime!.endTime.format(Get.context!),
          governorate: governorate.value,
          address: location.text);
      var response = await clinicRequests.postData(getToken(auth)!, clinic);
      addClinicStatus.value = checkResponseStatus(response);
      print(response);
      if (addClinicStatus.value == RequestStatus.success) {
        snackbar(title: "تم الاضافة", content: "تم اضافة العياده بنجاح");
        Get.offNamed(AppRoutes.doctorClinics);
      } else if (addClinicStatus.value == RequestStatus.userFailure) {
        DialogRequestMessages(Get.context!,
            status: addClinicStatus.value, failureText: response["Message"]);
      } else {
        DialogRequestMessages(Get.context!, status: addClinicStatus.value);
      }
    }
  }

  // When Edit
  checkEditOrAdd() {
    if (Get.arguments != null &&
        Get.arguments["isEdit"] != null &&
        Get.arguments["isEdit"] == true) {
      isEdit.value = true;
      clinicsController = Get.find<DoctorClinicsController>();
      if (clinicsController?.clinic.value != null) {
        ClinicModal? clinic = clinicsController!.clinic.value;
        clinicId.value = clinic!.id!;
        imgSrc.value = clinic.logo ?? AssetPaths.emptyIMG;
        phone.text = clinic.phoneNumber!;
        price.text = clinic.price!;
        spcialist.value = clinic.specialist!;
        governorate.value = clinic.governorate!;
        location.text = clinic.address!;

        selectedTime = TimeRange(
            startTime: stringToTimeDay(clinic.startWorking!),
            endTime: stringToTimeDay(clinic.endWorking!));
        rangeTimeController.text = setTimeToField(selectedTime);
      }
    } else {
      isEdit.value = false;
    }
  }

  onPickImage() {
    pickImg(ImageSource source) async {
      imageFile.value = await ImagePicker().pickImage(source: source);
      if (imageFile.value == null) return;
      Get.back();
      if (clinicId.value.isNotEmpty && isEdit.value) {
        addClinicStatus.value = RequestStatus.loading;

        var response = await clinicRequests.updateLogo(getToken(auth)!,
            imageFile.value!, clinicId.value, getCookie(auth)!);
        addClinicStatus.value = checkResponseStatus(response);
        print(response);
        if (addClinicStatus.value == RequestStatus.success) {
          if (response["Data"] != null) {
            imgSrc.value = response["Data"];
            snackbar(title: "تم تغيير الصورة", content: response["Message"]);
            // Clinics list update
            clinicsController!.updateClinicsList(
              clinicId.value,
              img: imgSrc.value,
            );
            // clinic Details  update
            clinicsController!.updateClinicDetails(logo: imgSrc.value);
          } else {
            addClinicStatus.value = RequestStatus.failure;
          }
        } else if (addClinicStatus.value == RequestStatus.userFailure) {
          DialogRequestMessages(Get.context!,
              status: addClinicStatus.value, failureText: response["Message"]);
        } else {
          DialogRequestMessages(Get.context!, status: addClinicStatus.value);
        }
      }
    }

    CustomBottomSheets.uploadImages(onCamera: () async {
      await pickImg(ImageSource.camera);
    }, onGellary: () async {
      await pickImg(ImageSource.gallery);
    });
  }

  onEditSubmit() async {
    if (formkey.currentState!.validate() &&
        clinicsController?.clinic.value != null) {
      addClinicStatus.value = RequestStatus.loading;
      var response = await clinicRequests.editClinic(
          getToken(auth)!, clinicId.value, getCookie(auth)!,
          phone: phone.text,
          startTime: selectedTime!.startTime.format(Get.context!),
          endTime: selectedTime!.endTime.format(Get.context!),
          governorate: governorate.value,
          address: location.text,
          price: price.text);

      addClinicStatus.value = checkResponseStatus(response);

      if (addClinicStatus.value == RequestStatus.success) {
        // Update the clincs list
        clinicsController!.updateClinicsList(
          clinicId.value,
          start: rangeToHMSFormat(selectedTime!.startTime),
          end: rangeToHMSFormat(selectedTime!.endTime),
        );
        // Update Clinic Detials
        clinicsController!.updateClinicDetails(
            phone: phone.text,
            start: rangeToHMSFormat(selectedTime!.startTime),
            end: rangeToHMSFormat(selectedTime!.endTime),
            governorate: governorate.value,
            location: location.text,
            price: price.text);

        Get.back(closeOverlays: false);
        snackbar(title: "تم التعديل", content: response["Message"]);
      } else {
        DialogRequestMessages(Get.context!,
            status: addClinicStatus.value, failureText: response["Message"]);
      }
    }
  }

  // Related to controller
  @override
  void onClose() {
    name.dispose();
    phone.dispose();
    price.dispose();
    rangeTimeController.dispose();
    location.dispose();
    super.onClose();
  }
}
