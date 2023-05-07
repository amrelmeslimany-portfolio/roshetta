import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:time_range_picker/time_range_picker.dart';

DateTime getParesedDTFRString(String date, {bool isOnlyTime = false}) {
  if (isOnlyTime) {
    return DateFormat("HH:mm:ss").parse(date);
  } else {
    return DateFormat("yyyy-MM-dd HH:mm:ss").parse(date);
  }
}

getDiffernceDays(String date) {
  DateTime dateNow = DateTime.now();
  DateTime parsed = DateTime.parse(date);
  int number = dateNow.difference(parsed).inDays;
  if (number == 0) {
    return number;
  }
  return handleNumbers(dateNow.difference(parsed).inDays);
}

String getFormattedTime(String date) {
  String number =
      DateFormat("hh:mm").format(getParesedDTFRString(date, isOnlyTime: true));
  String prefix = DateFormat("a", "ar")
      .format(getParesedDTFRString(date, isOnlyTime: true));
  return "$number $prefix";
}

String getRangeTime({required String start, required String end}) {
  return "${getFormattedTime(start)} - ${getFormattedTime(end)}";
}

TimeOfDay stringToTimeDay(String time) {
  return TimeOfDay.fromDateTime(getParesedDTFRString(time, isOnlyTime: true));
}

String setTimeToField(TimeRange? selectedTime) {
  return " من ${selectedTime!.startTime.format(Get.context!)} الي  ${selectedTime.endTime.format(Get.context!)} ";
}

String rangeToHMSFormat(TimeOfDay time) {
  return "${time.hour}:${time.minute}:00";
}
