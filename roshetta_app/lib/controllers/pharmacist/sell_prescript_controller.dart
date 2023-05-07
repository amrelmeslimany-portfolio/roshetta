import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/controllers/pharmacist/pharmacys_controller.dart';
import 'package:roshetta_app/controllers/pharmacist/prescripts_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/enums.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/source/remote/pharmacist/prescripts_data.dart';

class SellPrescriptPharmacy extends GetxController {
  GlobalKey<FormState> formkey = GlobalKey<FormState>();
  PharmacistPrescriptsData requests =
      PharmacistPrescriptsData(Get.find<Crud>());
  late TextEditingController numberID = TextEditingController();
  late Rx<RequestStatus> prescriptStatus = RequestStatus.none.obs;
  late RxString numberType = "ssd".obs;
  final auth = Get.find<AuthenticationController>();
  final RxString pharmacyId = "".obs;
  final prescriptController = Get.find<PharmacyPrescriptsController>();
  final PharmacistPharmacyController pharmacyController =
      Get.find<PharmacistPharmacyController>();

  checkPharmacyId() {
    if (checkArgument("pharmacy_id") != null) {
      pharmacyId.value = checkArgument("pharmacy_id");
    }
  }

  List initPharmacies() {
    List pharmacys = pharmacyController.pharmacys
        .where((element) => element["isVerify"] == RequestStatus.success.name)
        .toList();
    return pharmacys;
  }

  onNumberTypeChange(String value) {
    numberType.value = value;
  }

  onChoosePharmacy(String id) {
    pharmacyId.value = id;
  }

  onSubmit() async {
    if (pharmacyId.isEmpty) {
      snackbar(
          color: Colors.red,
          title: "حدثت مشكله",
          content: "يجب اختيار صيدليه لصرف الروشته منها");
      return;
    }
    if (formkey.currentState!.validate()) {
      prescriptStatus.value = RequestStatus.loading;

      var response = await requests.viewPrescript(
          getToken(auth)!, pharmacyId.value, getCookie(auth),
          idType: numberType.value, id: numberID.text);
      prescriptStatus.value = checkResponseStatus(response);
      print(response);
      if (prescriptStatus.value == RequestStatus.success) {
        if (response["Data"] == null) {
          handleSnackErrors(response);
          return;
        }
        onSubmitSuccess(response["Data"]);
        resetForm();
      } else {
        handleSnackErrors(response);
      }
    }
  }

  onSubmitSuccess(data) {
    if (numberType.value == "ssd") {
      Map arguments = {
        "user_prescripts": data,
        "pharmacy_id": pharmacyId.value,
        "type": PrescriptStatus.isOrder,
        "userSsd": numberID.text
      };
      Get.toNamed(AppRoutes.pharmacyPrescripts, arguments: arguments);
    } else {
      prescriptController.redirectToPRDetails(data,
          pharmacyIdParam: pharmacyId.value);
    }
  }

  resetForm() {
    pharmacyId.value = "";
    numberType.value = "ssd";
    numberID.clear();
  }

  // Related to controller
  @override
  void onClose() {
    numberID.dispose();
    super.onClose();
  }
}
