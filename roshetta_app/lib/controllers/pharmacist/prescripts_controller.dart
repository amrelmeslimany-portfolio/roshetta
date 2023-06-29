import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/enums.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/models/prescript.modal.dart';
import 'package:roshetta_app/data/source/remote/pharmacist/prescripts_data.dart';
import 'package:roshetta_app/view/screens/patient/prescript_details.dart';
import 'package:roshetta_app/view/screens/pharmacist/confirm_prescript.dart';

class PharmacyPrescriptsController extends GetxController {
  final auth = Get.find<AuthenticationController>();
  late Rx<RequestStatus> prescriptStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> confirmPrescriptStatus = RequestStatus.none.obs;
  PharmacistPrescriptsData requests =
      PharmacistPrescriptsData(Get.find<Crud>());
  final prescripts = [].obs;
  late RxString pharmacyId = "".obs;
  late RxString prescriptId = "".obs;
  late RxString orderId = "".obs;
  late RxString userSsd = "".obs;
  late Rx<Prescript?> prescript = Rx<Prescript?>(null);
  late Rx<PrescriptStatus?> pageType = PrescriptStatus.none.obs;
  final TextEditingController search = TextEditingController();

  setPrescriptId(String id) => prescriptId.value = id;
  setOrderId(String id) => orderId.value = id;

  bool get isOrders {
    if (pageType.value == PrescriptStatus.wating) return true;
    return false;
  }

  String get searchText => search.text.trim();

  bool _checkPrescriptStatus() =>
      prescript.value!.prescriptStatus != PrescriptStatus.done;

  clearPrescripts() {
    if (prescripts.isNotEmpty) prescripts.clear();
  }

  checkIDArgumants() {
    if ((checkArgument("pharmacy_id") == null && pharmacyId.isEmpty) &&
        (checkArgument("type") == null &&
            pageType.value == PrescriptStatus.none)) {
      prescriptStatus.value = RequestStatus.failure;
      return;
    }
    if (pharmacyId.isEmpty) {
      pharmacyId.value = checkArgument("pharmacy_id");
    }

    if (pageType.value == PrescriptStatus.none) {
      pageType.value = checkArgument("type");
    }
  }

  onClearSearch() {
    search.clear();
    checkTypeOrdersRequest();
  }

  checkTypeOrdersRequest() {
    if (isOrders) {
      requestPrescripts();
    } else {
      requestPaidPrescripts();
    }
  }

  onSearchPrescripts() {
    if (searchText.trim().isNotEmpty) {
      checkTypeOrdersRequest();
    } else {
      if (Get.isSnackbarOpen) return;
      snackbar(
          isError: true,
          title: "البحث",
          content:
              "يجب ادخال اسم المريض او الرقم القومي للمريض او سيريال الروشته للبحث");
    }
  }

  getPrescripts() {
    checkIDArgumants();
    if (checkArgument("user_prescripts") != null) {
      clearPrescripts();
      prescripts.addAll(checkArgument("user_prescripts").toList());
      userSsd.value = checkArgument("userSsd") ?? "";
    } else if (pageType.value == PrescriptStatus.done) {
      requestPaidPrescripts();
    } else {
      requestPrescripts();
    }
  }

  requestPrescripts() async {
    prescriptStatus.value = RequestStatus.loading;
    var response = await requests.getPrescripts(
        getToken(auth)!, getCookie(auth)!, pharmacyId.value,
        filter: searchText);
    prescriptStatus.value = checkResponseStatus(response);
    print(response);
    if (prescriptStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        prescriptStatus.value = RequestStatus.empty;
        return;
      }
      clearPrescripts();
      prescripts.addAll(response["Data"].toList());
    } else {
      handleSnackErrors(response);
    }
  }

  requestPaidPrescripts() async {
    prescriptStatus.value = RequestStatus.loading;
    var response = await requests.viewPaidPrescript(
        getToken(auth)!, pharmacyId.value, getCookie(auth)!,
        filter: search.text);
    prescriptStatus.value = checkResponseStatus(response);
    print(response);
    if (prescriptStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        prescriptStatus.value = RequestStatus.empty;
        return;
      }
      clearPrescripts();
      prescripts.addAll(response["Data"].toList());
    } else {
      handleSnackErrors(response);
    }
  }

  getPrescriptDetails(String prescriptId, String idType) async {
    prescriptStatus.value = RequestStatus.loading;
    var response = await requests.viewPrescript(
        getToken(auth)!, pharmacyId.value, getCookie(auth)!,
        id: prescriptId, idType: idType);
    prescriptStatus.value = checkResponseStatus(response);
    print(response);
    if (prescriptStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        prescriptStatus.value = RequestStatus.success;
        return;
      }
      redirectToPRDetails(response["Data"]);
    } else {
      prescriptStatus.value = RequestStatus.success;
      handleSnackErrors(response);
    }
  }

  redirectToPRDetails(Map data, {String? pharmacyIdParam}) {
    prescript.value = Prescript.fromJson(data["prescript_data"][0]);
    prescript.value!.medicineData = data["medicine_data"];
    pharmacyId.value = pharmacyIdParam ?? pharmacyId.value;
    prescriptId.value = prescript.value!.prescriptId!;
    Get.to(_prescriptDetails());
  }

  PrescriptDetails _prescriptDetails() => PrescriptDetails(
        prescript: prescript.value,
        floatingButton: Obx(() {
          if (_checkPrescriptStatus()) {
            return ConfirmPrescriptButton(
                status: confirmPrescriptStatus.value,
                onClick: () {
                  confirmDialog(Get.context!, text: "هل تريد صرف الروشته ؟",
                      onConfirm: () async {
                    await onConfirmPrescript();
                  });
                });
          }
          return Container();
        }),
      );

  onConfirmPrescript() async {
    if (confirmPrescriptStatus.value == RequestStatus.loading) return;
    if (Get.isDialogOpen == true) Get.back();
    confirmPrescriptStatus.value = RequestStatus.loading;
    var response = await requests.confirmPrescript(
        getToken(auth)!, pharmacyId.value, getCookie(auth)!, prescriptId.value,
        orderId: orderId.value);
    confirmPrescriptStatus.value = checkResponseStatus(response);
    print(response);
    if (confirmPrescriptStatus.value == RequestStatus.success) {
      if (orderId.isNotEmpty) updateOrderStatusList();
      prescript.value!.prescriptStatus = PrescriptStatus.done;
      snackbar(title: "تم الصرف", content: response["Message"]);
    } else {
      confirmPrescriptStatus.value = RequestStatus.success;
      handleSnackErrors(response);
    }
  }

  updateOrderStatusList() {
    int index = prescripts.indexWhere((element) =>
        prescript.value!.prescriptId == element["prescript_id"] &&
        element["orderStatus"] != PrescriptStatus.done.name);
    prescripts[index]["orderStatus"] = "done";
    prescripts.refresh();
  }
}
