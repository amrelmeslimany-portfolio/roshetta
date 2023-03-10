import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:roshetta_app/core/class/auth.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

// Data json
Future<List> readJson(String path) async {
  final String response = await rootBundle.loadString(path);
  final data = await json.decode(response);
  return data;
}

String enumToString(Enum key) {
  return key.name;
}

// APIs Functions
checkInternet() async {
  try {
    dynamic response;
    response = await http.get(Uri.parse(ApiUrls.domain));
    if (response.statusCode == 200) return true;
    response = await InternetAddress.lookup("google.com");
    if (response.isNotEmpty || response[0].rawAddress.isNotEmpty) return true;
  } on SocketException catch (error) {
    print({"network error:===": error});
    return false;
  }
}

logoutError401(int status, Authentication auth, int seconds) {
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

String handleNumbers(int? number) {
  if (number == 0 || number == null) {
    return "لا يوجد";
  } else {
    return number.toString();
  }
}
