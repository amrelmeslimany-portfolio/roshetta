import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/models/clinic.modal.dart';
import 'package:roshetta_app/data/source/remote/doctor/clinic_data.dart';

class AssistantClinicsController extends GetxController {
  AuthenticationController auth = Get.find<AuthenticationController>();
  ClinicData clinicsRequests =
      ClinicData(Get.find<Crud>(), role: Users.assistant);
  final clinics = [].obs;
  final Rx<RequestStatus> status = RequestStatus.none.obs;
  final Rx<RequestStatus> loginStatus = RequestStatus.none.obs;
  final clinic = Rx<ClinicModal?>(null);

  @override
  void onInit() async {
    await getClinics();
    super.onInit();
  }

  getClinics() async {
    status.value = RequestStatus.loading;
    var response = await clinicsRequests.getClinics(getToken(auth)!);
    status.value = checkResponseStatus(response);
    print(response);

    if (status.value == RequestStatus.success) {
      if (response["Data"] == null) {
        status.value = RequestStatus.empty;
        return;
      }
      clinics.addAll(response["Data"].toList());
    }
  }

  updateClinicsList(String clinicId,
      {String? img, String? start, String? end, String? status}) {
    int index = clinics.indexWhere((element) => clinicId == element["id"]);
    clinics[index]["logo"] = img ?? clinics[index]["logo"];
    clinics[index]["start_working"] = start ?? clinics[index]["start_working"];
    clinics[index]["end_working"] = end ?? clinics[index]["end_working"];
    clinics[index]["status"] = status ?? clinics[index]["status"];
    clinics.refresh();
  }

  updateClinicDetails({
    String? phone,
    String? start,
    String? end,
    String? price,
    String? governorate,
    String? location,
    String? status,
    String? logo,
  }) {
    clinic.value!.logo = logo ?? clinic.value!.logo;
    clinic.value!.phoneNumber = phone ?? clinic.value!.phoneNumber;
    clinic.value!.startWorking = start ?? clinic.value!.startWorking;
    clinic.value!.endWorking = end ?? clinic.value!.endWorking;
    clinic.value!.governorate = governorate ?? clinic.value!.governorate;
    clinic.value!.address = location ?? clinic.value!.address;
    clinic.value!.price = price ?? clinic.value!.price;
    clinic.value!.status = status ?? clinic.value!.status;
    clinic.refresh();
  }

  onLogin(String id) async {
    if (Get.isBottomSheetOpen == true) {
      Get.back();
    }
    if (clinic.value != null &&
        clinic.value!.id == id &&
        clinic.value!.status == "1") {
      Get.toNamed(AppRoutes.assistantClinicDetails, preventDuplicates: true);
      return;
    }
    loginStatus.value = RequestStatus.loading;

    var response = await clinicsRequests.loginClinic(
        getToken(auth)!, id, getCookie(auth)!);

    loginStatus.value = checkResponseStatus(response);

    print(response);
    if (loginStatus.value == RequestStatus.success) {
      Get.toNamed(AppRoutes.assistantClinicDetails, preventDuplicates: true);
      if (response["Data"] == null) {
        loginStatus.value = RequestStatus.empty;
      } else {
        clinic.value = ClinicModal.fromJson(response["Data"]);
        updateClinicsList(clinic.value!.id!, status: "1");
        updateClinicDetails(status: "1");
      }
    } else {
      loginStatus.value = RequestStatus.success;
      snackbar(
          color: Colors.red,
          title: "فشل تسجيل الدخول",
          content: response["Message"]);
    }
  }

  onClinicLogout(String? id) async {
    if (Get.isBottomSheetOpen == true) {
      Get.back();
    }
    loginStatus.value = RequestStatus.loading;
    var response = await clinicsRequests.clinicLogout(
        getToken(auth)!, clinic.value?.id ?? id!, getCookie(auth));
    loginStatus.value = checkResponseStatus(response);
    print(response);
    if (loginStatus.value == RequestStatus.success) {
      // Get.offNamed(AppRoutes.doctorClinics);
      snackbar(
          title: response["Message"],
          content: "تم تسجيل الخروج من العياده وغلقها الي حين فتحها مرة اخري");
      updateClinicsList(clinic.value!.id!, status: "0");
      updateClinicDetails(status: "0");
    } else {
      loginStatus.value = RequestStatus.success;
      snackbar(
          color: Colors.red,
          title: "فشل تسجيل الخروج",
          content: response["Message"]);
    }
  }
}
