import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/source/remote/patient/clinics_data.dart';

class PatientAppointmentsController extends GetxController {
  final auth = Get.find<AuthenticationController>();
  late Rx<RequestStatus> appointmentItemStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> appointmentStatus = RequestStatus.none.obs;
  PatientClinicData requests = PatientClinicData(Get.find<Crud>());
  final appointmentsDone = [].obs;
  final appointmentsPending = [].obs;
  late TextEditingController appointDate = TextEditingController();

  @override
  void onInit() async {
    super.onInit();
    await getAppointments();
  }

  getAppointments() async {
    appointmentStatus.value = RequestStatus.loading;
    var response = await requests.getAppointments(getToken(auth)!);
    appointmentStatus.value = checkResponseStatus(response);
    print(response);
    if (appointmentStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        appointmentStatus.value = RequestStatus.empty;
        return;
      }
      if (appointmentsDone.isNotEmpty) appointmentsDone.clear();
      if (appointmentsPending.isNotEmpty) appointmentsPending.clear();

      for (var element in response["Data"]) {
        if (element["appoint_case"] == "1" || element["appoint_case"] == "2") {
          appointmentsDone.add(element);
        } else if (element["appoint_case"] == "0") {
          appointmentsPending.add(element);
        }
      }
    } else {
      snackbar(
          isError: true, title: "حدثت مشكلة", content: response["Message"]);
    }
  }

  updateAppointments(String id, {bool? delete, String? date}) {
    int index = appointmentsPending
        .indexWhere((element) => id == element["appointment_id"]);

    if (delete != null && delete) {
      appointmentsPending.removeAt(index);
    } else {
      appointmentsPending[index]["appoint_date"] =
          date ?? appointmentsPending[index]["appoint_date"];
    }
  }

  submitEditAppointment(String id) async {
    appointmentItemStatus.value = RequestStatus.loading;
    var response =
        await requests.editAppointments(getToken(auth)!, id, appointDate.text);
    appointmentItemStatus.value = checkResponseStatus(response);
    print(response);
    if (appointmentItemStatus.value == RequestStatus.success) {
      if (Get.isBottomSheetOpen == true) {
        Get.back();
      }
      snackbar(title: "تم التعديل", content: response["Message"]);
      updateAppointments(id, date: appointDate.text);
    } else {
      snackbar(
          isError: true,
          title: "حدثت مشكلة",
          content: "هناك مشكله حدثت اثناء التعديل");
    }
  }

  onDeleteAppointment(String id) async {
    if (Get.isBottomSheetOpen == true) {
      Get.back();
    }
    appointmentItemStatus.value = RequestStatus.loading;
    var response = await requests.deleteAppointments(getToken(auth)!, id);
    appointmentItemStatus.value = checkResponseStatus(response);
    print(response);
    if (appointmentItemStatus.value == RequestStatus.success) {
      snackbar(title: "تم الحذف", content: response["Message"]);
      updateAppointments(id, delete: true);
    } else {
      snackbar(
          isError: true,
          title: "حدثت مشكلة",
          content: "هناك مشكله حدثت اثناء الحذف");
    }
  }
}
