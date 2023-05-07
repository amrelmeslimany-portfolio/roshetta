import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/models/prescript.modal.dart';
import 'package:roshetta_app/data/source/remote/patient/prescripts_data.dart';
import 'package:roshetta_app/view/screens/patient/prescript_details.dart';

class PatientPrescriptsController extends GetxController {
  final auth = Get.find<AuthenticationController>();
  late Rx<RequestStatus> prescriptStatus = RequestStatus.none.obs;
  PatientPrescriptsData requests = PatientPrescriptsData(Get.find<Crud>());
  final prescripts = [].obs;
  final RxBool isAll = false.obs;

  @override
  void onInit() async {
    super.onInit();
    await getPrescripts();
  }

  int sortingPrescripts(a, b) {
    int statusCOM = b["prescriptStatus"]
        .toString()
        .compareTo(a["prescriptStatus"].toString());
    if (statusCOM != 0) return statusCOM;
    return getParsedDate(b["created_date"])
        .compareTo(getParsedDate(a["created_date"]));
  }

  getPrescripts({bool? isAll = false}) async {
    prescriptStatus.value = RequestStatus.loading;
    var response = await requests.getPrescripts(getToken(auth)!,
        diseaseId: isAll == true ? null : checkArgument('disease_id'));
    prescriptStatus.value = checkResponseStatus(response);
    print(response);
    if (prescriptStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        prescriptStatus.value = RequestStatus.empty;
        return;
      }
      if (prescripts.isNotEmpty) prescripts.clear();
      // NOTE this lines for sorting
      List data = response["Data"].toList();
      data.sort(sortingPrescripts);
      prescripts.addAll(data);
    } else {
      handleSnackErrors(response);
    }
  }

  getPrescriptDetails(String id) async {
    prescriptStatus.value = RequestStatus.loading;
    var response = await requests.getPrescriptDetails(getToken(auth)!, id);
    prescriptStatus.value = checkResponseStatus(response);
    print(response);
    if (prescriptStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        prescriptStatus.value = RequestStatus.success;

        snackbar(
            color: Colors.red,
            title: "هناك مشكله",
            content: "تفاصيل الروشته فارغة او غير موجوده");
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

  changeIsAll(bool value) => isAll.value = value;
}
