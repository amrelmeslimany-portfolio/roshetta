import 'dart:io';

import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';
import 'package:roshetta_app/data/models/clinic.modal.dart';

class ClinicData {
  Users? role;
  Crud crud;
  ClinicData(this.crud, {this.role = Users.doctor});

  bool isAssistant() {
    if (role == Users.assistant) {
      return true;
    } else {
      return false;
    }
  }

  // Curd Methods
  // NOTE this add new clinic for doctor only
  postData(String token, ClinicModal clinic) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };
    FormData body = FormData(clinic.toJson());

    var response = await crud.baseCrud(ApiUrls.addClinic, "post",
        body: body, headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  // NOTE Docotor and Assistant
  getClinics(String token) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response = await crud.baseCrud(
        isAssistant() ? ApiUrls.assistantClinics : ApiUrls.doctorViewClinics,
        "get",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  loginClinic(String token, String clinicId, String cookie) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    var response = await crud.baseCrud(
        "${isAssistant() ? ApiUrls.assistantClinicLogin : ApiUrls.doctorLoginClinic}/$clinicId",
        "post",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  verifyClinic(String token, XFile file, String clinicId,
      {String? placeType = "clinic"}) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    FormData body = FormData(
        {"license_img": MultipartFile(file.path, filename: file.name)});

    Map<String, dynamic> query = {"place_role": placeType};

    var response = await crud.baseCrud(
        "${ApiUrls.verifyPlace}/$clinicId", "post",
        body: body, query: query, headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  // Edit Clinic
  updateLogo(String token, XFile file, String clinicId, String? cookie) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie ?? ""
    };

    FormData body =
        FormData({"image": MultipartFile(file.path, filename: file.name)});

    var response = await crud.baseCrud(
        "${ApiUrls.addLogoClinic}/$clinicId", "post",
        body: body, headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  editClinic(
    String token,
    String clinicId,
    String? cookie, {
    required String phone,
    required String startTime,
    required String endTime,
    required String governorate,
    required String address,
    required String price,
  }) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie ?? ""
    };

    FormData body = FormData({
      "phone_number": phone,
      "start_working": startTime,
      "end_working": endTime,
      "governorate": governorate,
      "address": address,
      "price": price,
    });

    var response = await crud.baseCrud(
        "${isAssistant() ? ApiUrls.assistantClinicEdit : ApiUrls.editClinic}/$clinicId",
        "post",
        body: body,
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  // Clinic Details
  clinicLogout(String token, String clinicId, String? cookie) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie ?? ""
    };

    var response = await crud.baseCrud(
        "${isAssistant() ? ApiUrls.assistantClinicLogout : ApiUrls.logoutClinic}/$clinicId",
        "get",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  // Clinic Assistant
  viewAssistant(String token, String cookie, String clinicId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    var response = await crud.baseCrud(
        "${ApiUrls.doctorAssistClinic}/$clinicId", "get",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  addAssistant(
      String token, String cookie, String clinicId, String assistId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    var response = await crud.baseCrud(
        "${ApiUrls.doctorAddAssistClinic}/$clinicId", "post",
        headers: headers, query: {"assistant_id": assistId});

    return response.fold((l) => l, (r) => r);
  }

  deleteAssistant(String token, String cookie, String clinicId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    var response = await crud.baseCrud(
        "${ApiUrls.doctorDeleteAssistClinic}/$clinicId", "post",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }
}
