import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/models/pharmacy.modal.dart';
import 'package:roshetta_app/data/source/remote/patient/pharmacy_data.dart';
import 'package:roshetta_app/view/widgets/auth/auth_dialogs.dart';

class PatientPharmacysController extends GetxController {
  final auth = Get.find<AuthenticationController>();
  late Rx<RequestStatus> pharmacysStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> pharmacyDetailsStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> ordersStatus = RequestStatus.none.obs;
  PatientPharmacyData requests = PatientPharmacyData(Get.find<Crud>());
  final orderId = "".obs;
  final pharmacys = [].obs;
  final pharmacy = Rx<PharmacyModal?>(null);

  @override
  onInit() async {
    await getPharmacys();
    super.onInit();
  }

  getPharmacys() async {
    pharmacysStatus.value = RequestStatus.loading;
    var response = await requests.getPharmacy(getToken(auth)!);
    pharmacysStatus.value = checkResponseStatus(response);

    print(response);
    if (pharmacysStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        pharmacysStatus.value = RequestStatus.empty;
        return;
      }
      pharmacys.addAll(response["Data"].toList());
    } else {
      snackbar(title: "حدثت مشكلة", content: response["Message"]);
    }
  }

  setOrderId(String id) {
    orderId.value = id;
  }

  goToPharmacyDetails(String id) async {
    pharmacy.value = null;
    Get.toNamed(AppRoutes.patientPharmacyDetails);
    pharmacyDetailsStatus.value = RequestStatus.loading;
    var response = await requests.getPharmacyDetails(getToken(auth)!, id);
    pharmacyDetailsStatus.value = checkResponseStatus(response);
    print(response);
    if (pharmacyDetailsStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        pharmacyDetailsStatus.value = RequestStatus.empty;
      } else {
        pharmacy.value = PharmacyModal.fromJson(response["Data"]);
      }
    } else {
      snackbar(
          color: Colors.red, title: "حدثت مشكلة", content: response["Message"]);
    }
  }

  onSendPrescript() async {
    if (orderId.isEmpty) {
      snackbar(
          color: Colors.red,
          title: "حدثت مشكلة",
          content: "يجب اختيار روشته واحده");
      return;
    }

    ordersStatus.value = RequestStatus.loading;

    var response = await requests.sendPrescript(
        getToken(auth)!, pharmacy.value!.id!, orderId.value);

    ordersStatus.value = checkResponseStatus(response);

    print(response);

    if (ordersStatus.value == RequestStatus.success) {
      setOrderId("");

      if (Get.isBottomSheetOpen == true) {
        Get.back();
      }

      snackbar(title: "ارسال الروشته", content: response["Message"]);
    } else {
      snackbar(
          color: Colors.red, title: "حدثت مشكلة", content: response["Message"]);
    }
  }
}
