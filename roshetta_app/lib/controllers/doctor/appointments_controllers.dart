import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/source/remote/doctor/appointments_data.dart';

class DoctorAppointmentsController extends GetxController {
  final auth = Get.find<AuthenticationController>();
  late Rx<RequestStatus> filterStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> appointmentStatus = RequestStatus.none.obs;
  DoctorAppointmentsData requests = DoctorAppointmentsData(Get.find<Crud>());
  final appointments = [].obs;
  late TextEditingController appointDate = TextEditingController();
  late TextEditingController search = TextEditingController();
  late RxString appointStatusFilter = "1".obs;
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
      if (appointments.isNotEmpty) appointments.clear();
      appointments.addAll(response["Data"].toList());
    } else {
      snackbar(
          isError: true, title: "حدثت مشكلة", content: response["Message"]);
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
    appointStatusFilter.value = "1";

    await getAppointments();
  }
}
