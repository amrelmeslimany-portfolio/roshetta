import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/source/remote/patient/pharmacy_data.dart';

class PatientOrdersController extends GetxController {
  final auth = Get.find<AuthenticationController>();
  late Rx<RequestStatus> ordersStatus = RequestStatus.none.obs;
  PatientPharmacyData requests = PatientPharmacyData(Get.find<Crud>());
  final orders = [].obs;

  getOrders() async {
    ordersStatus.value = RequestStatus.loading;
    var response = await requests.viewOrders(getToken(auth)!);
    ordersStatus.value = checkResponseStatus(response);
    print(response);
    if (ordersStatus.value == RequestStatus.success) {
      if (orders.isNotEmpty) orders.clear();
      if (response["Data"] == null) {
        ordersStatus.value = RequestStatus.empty;
        return;
      }
      orders.addAll(response["Data"].toList());
    } else {
      handleSnackErrors(response);
    }
  }

  onDeleteOrder(String orderId) async {
    if (Get.isDialogOpen == true) Get.back();
    ordersStatus.value = RequestStatus.loading;
    var response = await requests.deleteOrder(getToken(auth)!, orderId);
    ordersStatus.value = checkResponseStatus(response);
    print(response);
    if (ordersStatus.value == RequestStatus.success) {
      removeOrderFromOrders(orderId);
      handleSuccessDialoge(response);
      if (orders.isEmpty) {
        ordersStatus.value = RequestStatus.empty;
        return;
      }
      ordersStatus.value = RequestStatus.success;
    } else {
      ordersStatus.value = RequestStatus.success;
      handleSnackErrors(response);
    }
  }

  removeOrderFromOrders(id) =>
      orders.removeWhere((element) => element["order_id"] == id);

  handleSuccessDialoge(response) {
    successDialog(Get.context!,
        isBack: true,
        title: "تم الحذف",
        content: response["Message"], onSuccess: () {
      if (Get.isDialogOpen == true) Get.back();
    });
    Future.delayed(const Duration(seconds: 2), () {
      if (Get.isDialogOpen == true) Get.back();
    });
  }
}
