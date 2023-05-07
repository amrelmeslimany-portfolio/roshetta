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
import 'package:roshetta_app/data/models/pharmacy.modal.dart';
import 'package:roshetta_app/data/source/remote/pharmacist/pharmacy_data.dart';

class PharmacistPharmacyController extends GetxController {
  AuthenticationController auth = Get.find<AuthenticationController>();
  PharmacyData pharmacysRequests = PharmacyData(Get.find<Crud>());
  final pharmacys = [].obs;
  final Rx<RequestStatus> status = RequestStatus.none.obs;
  final Rx<RequestStatus> loginStatus = RequestStatus.none.obs;
  final pharmacy = Rx<PharmacyModal?>(null);

  @override
  void onInit() async {
    await getPharmacys();
    super.onInit();
  }

  getPharmacys() async {
    status.value = RequestStatus.loading;
    var response = await pharmacysRequests.getPharmacys(getToken(auth)!);
    status.value = checkResponseStatus(response);
    print(response);

    if (status.value == RequestStatus.success) {
      if (response["Data"] == null) {
        status.value = RequestStatus.empty;
        return;
      }
      pharmacys.addAll(response["Data"].toList());
    }
  }

  updatePharmacysList(String pharmacyId,
      {String? img, String? start, String? end, String? status}) {
    int index = pharmacys.indexWhere((element) => pharmacyId == element["id"]);
    pharmacys[index]["logo"] = img ?? pharmacys[index]["logo"];
    pharmacys[index]["start_working"] =
        start ?? pharmacys[index]["start_working"];
    pharmacys[index]["end_working"] = end ?? pharmacys[index]["end_working"];
    pharmacys[index]["status"] = status ?? pharmacys[index]["status"];
    pharmacys.refresh();
  }

  updatePharmacyDetails({
    String? phone,
    String? start,
    String? end,
    String? governorate,
    String? location,
    String? status,
    String? logo,
  }) {
    pharmacy.value!.logo = logo ?? pharmacy.value!.logo;
    pharmacy.value!.phoneNumber = phone ?? pharmacy.value!.phoneNumber;
    pharmacy.value!.startWorking = start ?? pharmacy.value!.startWorking;
    pharmacy.value!.endWorking = end ?? pharmacy.value!.endWorking;
    pharmacy.value!.governorate = governorate ?? pharmacy.value!.governorate;
    pharmacy.value!.address = location ?? pharmacy.value!.address;
    pharmacy.value!.status = status ?? pharmacy.value!.status;
    pharmacy.refresh();
  }

  onLogin(String id) async {
    if (Get.isBottomSheetOpen == true) {
      Get.back();
    }
    if (pharmacy.value != null &&
        pharmacy.value!.id == id &&
        pharmacy.value!.status == "1") {
      Get.toNamed(AppRoutes.pharmacistPharmacyDetails, preventDuplicates: true);
      return;
    }
    loginStatus.value = RequestStatus.loading;
    // dialogLoading();
    var response = await pharmacysRequests.loginPharmacy(
        getToken(auth)!, id, getCookie(auth)!);

    loginStatus.value = checkResponseStatus(response);
    if (Get.isDialogOpen == true) {
      Get.closeAllSnackbars();
      Get.back();
    }
    print(response);
    if (loginStatus.value == RequestStatus.success) {
      Get.toNamed(AppRoutes.pharmacistPharmacyDetails, preventDuplicates: true);
      if (response["Data"] == null) {
        loginStatus.value = RequestStatus.empty;
      } else {
        pharmacy.value = PharmacyModal.fromJson(response["Data"]);
        updatePharmacysList(pharmacy.value!.id!, status: "1");
        updatePharmacyDetails(status: "1");
      }
    } else {
      loginStatus.value = RequestStatus.success;
      snackbar(
          color: Colors.red,
          title: "فشل تسجيل الدخول",
          content: response["Message"]);
    }
  }

  onPharmacyLogout(String? id) async {
    Get.back();
    loginStatus.value = RequestStatus.loading;
    var response = await pharmacysRequests.pharmacyLogout(
        getToken(auth)!, pharmacy.value?.id ?? id!, getCookie(auth));
    loginStatus.value = checkResponseStatus(response);
    print(response);
    if (loginStatus.value == RequestStatus.success) {
      // Get.offNamed(AppRoutes.doctorPharmacys);
      snackbar(
          title: response["Message"],
          content: "تم تسجيل الخروج من العياده وغلقها الي حين فتحها مرة اخري");
      updatePharmacysList(pharmacy.value!.id!, status: "0");
      updatePharmacyDetails(status: "0");
    } else {
      loginStatus.value = RequestStatus.success;
      snackbar(
          color: Colors.red,
          title: "فشل تسجيل الخروج",
          content: response["Message"]);
    }
  }
}
