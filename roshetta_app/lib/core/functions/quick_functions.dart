import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';

String? getCookie(AuthenticationController auth) {
  return auth.getStorage.getString("cookies");
}

String? getToken(AuthenticationController auth) {
  return auth.localUser.value!.token!;
}

DateTime getParsedDate(String date) {
  return DateFormat("yyyy-MM-dd").parse(date);
}

checkOpenStatus(String status, closed, openend) {
  if (status == "0") {
    return closed;
  } else {
    return openend;
  }
}

checkArgument(String name) {
  if (Get.arguments != null && Get.arguments[name] != null) {
    return Get.arguments[name];
  } else {
    return null;
  }
}

scrollToTop(ScrollController scrollController, {double? position = 0.0}) {
  scrollController.animateTo(position!,
      duration: const Duration(milliseconds: 200), curve: Curves.linear);
}
