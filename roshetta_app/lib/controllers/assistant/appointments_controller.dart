import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/source/remote/doctor/appointments_data.dart';

class AssistantAppointmentsController extends GetxController {
  final auth = Get.find<AuthenticationController>();
  late Rx<RequestStatus> filterStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> appointmentStatus = RequestStatus.none.obs;
  // late Rx<RequestStatus> setAppointmentStatus = RequestStatus.none.obs;
  DoctorAppointmentsData requests =
      DoctorAppointmentsData(Get.find<Crud>(), role: Users.assistant);
  final appointments = [].obs;
  late TextEditingController appointDate = TextEditingController();
  late TextEditingController search = TextEditingController();
  late RxString appointStatusFilter = "0".obs;
  late Rx<DateTime?> appointDateFilter = Rx<DateTime?>(null);
  late RxString clinicId = "".obs;

  @override
  void onInit() async {
    super.onInit();
    await getAppointments();
  }

  getAppointments() async {
    if (checkArgument("clinic_id") == null && clinicId.isEmpty) {
      appointmentStatus.value = RequestStatus.failure;
      return;
    }
    if (clinicId.isEmpty) {
      clinicId.value = checkArgument("clinic_id");
    }
    if (appointments.isNotEmpty) {
      appointments.clear();
    }
    appointmentStatus.value = RequestStatus.loading;
    var response = await requests.getAppointments(
        getToken(auth)!, getCookie(auth)!, clinicId.value,
        date: appointDateFilter.value,
        status: appointStatusFilter.value,
        filter: search.text.trim());
    appointmentStatus.value = checkResponseStatus(response);
    print(response);
    if (appointmentStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        appointmentStatus.value = RequestStatus.empty;
        return;
      }
      appointments.addAll(response["Data"].toList());
    } else {
      snackbar(
          color: Colors.red, title: "حدثت مشكلة", content: response["Message"]);
    }
  }

  onStatusChange(String value) async {
    appointStatusFilter.value = value;
    await getAppointments();
  }

  onFilterDate(context) async {
    appointDateFilter.value = null;
    DateTime? date = await customDatePicker(context,
        initialMode: DatePickerMode.day,
        last: DateTime.now().add(const Duration(days: 365)));
    if (date == null) return;
    appointDateFilter.value =
        getParsedDate(DateFormat("yyyy-MM-dd").format(date));
    await getAppointments();
  }

  onFilterWord() async {
    await getAppointments();
  }

  onClearFilter() async {
    appointDateFilter.value = null;
    search.clear();
    appointStatusFilter.value = "0";

    await getAppointments();
  }

  onSubmitAppointStatus(String appointId) async {
    if (Get.isBottomSheetOpen == true) Get.back();
    appointmentStatus.value = RequestStatus.loading;
    var response = await requests.editAppointStatus(
        getToken(auth)!, getCookie(auth)!, clinicId.value,
        appointmentId: appointId);
    appointmentStatus.value = checkResponseStatus(response);
    print(response);
    if (appointmentStatus.value == RequestStatus.success) {
      appointments[appointments.indexWhere(
          (item) => item["appointment_id"] == appointId)]["appoint_case"] = "1";
      snackbar(title: "تم الادخال", content: response["Message"]);
    } else {
      appointmentStatus.value = RequestStatus.success;
      snackbar(
          color: Colors.red, title: "حدثت مشكلة", content: response["Message"]);
    }
  }
}
