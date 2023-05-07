import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';

// Data json
Future<List> readJson(String path) async {
  final String response = await rootBundle.loadString(path);
  final data = await json.decode(response);
  return data;
}

String enumToString(Enum key) {
  return key.name;
}

String formatCloseOrNot(String status) {
  return status == "0" ? "مغلقة الان" : "تعمل الان";
}

copyToClip(String text, {String? successText = "النص"}) async {
  await Clipboard.setData(ClipboardData(text: text)).then((value) =>
      snackbar(title: "تم النسخ", content: "تم نسخ $successText بنجاح"));
}

// APIs Functions
checkInternet() async {
  try {
    dynamic response;

    response = await http.get(Uri.parse(ApiUrls.domain)).timeout(
        Duration(seconds: 20),
        onTimeout: () => http.Response("وصل السيرفر", 404));
    if (response.statusCode == 200) return true;
    response = await InternetAddress.lookup("google.com");
    if (response.isNotEmpty || response[0].rawAddress.isNotEmpty) return true;
  } on SocketException catch (error) {
    print({"network error:===": error});
    return false;
  }
}

logoutError401(int status, AuthenticationController auth, int seconds) {
  if (status == 401) {
    Future.delayed(Duration(seconds: seconds), () => auth.logout());
  }
}

RequestStatus checkResponseStatus(response) {
  if (response is RequestStatus) {
    return response;
  } else {
    if (response["Status"] >= 200 && response["Status"] < 300) {
      return RequestStatus.success;
    } else {
      return RequestStatus.userFailure;
    }
  }
}

toggleDrawer(GlobalKey<ScaffoldState> drawer, {bool open = true}) {
  if (open) {
    drawer.currentState?.openDrawer();
  } else {
    drawer.currentState?.closeDrawer();
  }
}

String handleNumbers(int? number, {String emptyText = "فارغ"}) {
  if (number == 0 || number == null) {
    return emptyText;
  } else if (number < 10 && number > 0) {
    return "0$number";
  } else {
    return number.toString();
  }
}

handleSnackErrors(response) {
  Map? messages = response["Message"] is Map ? response["Message"] : null;
  snackbar(
      color: Colors.red,
      title: "حدثت مشكلة",
      content: messages?.values.join(" - ") ?? response["Message"]);
}
