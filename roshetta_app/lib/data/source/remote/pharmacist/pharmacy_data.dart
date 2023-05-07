import 'dart:io';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/enums.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';
import 'package:roshetta_app/data/models/pharmacy.modal.dart';

class PharmacyData {
  Crud crud;
  PharmacyData(this.crud);

  // Curd Methods
  postData(String token, PharmacyModal pharmacy) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };
    FormData body = FormData(pharmacy.toJson());

    var response = await crud.baseCrud(ApiUrls.addPharmacy, "post",
        body: body, headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  getPharmacys(String token) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response = await crud.baseCrud(ApiUrls.pharmacyViewPharmacs, "get",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  loginPharmacy(String token, String pharmacyId, String cookie) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    var response = await crud.baseCrud(
        "${ApiUrls.doctorLoginPharmacy}/$pharmacyId", "post",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  verifyPharmacy(String token, XFile file, String pharmacyId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    FormData body = FormData(
        {"license_img": MultipartFile(file.path, filename: file.name)});

    Map<String, dynamic> query = {"place_role": "pharmacy"};

    var response = await crud.baseCrud(
        "${ApiUrls.verifyPlace}/$pharmacyId", "post",
        body: body, query: query, headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  // Edit Pharmacy
  updateLogo(
      String token, XFile file, String pharmacyId, String? cookie) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie ?? ""
    };

    FormData body =
        FormData({"image": MultipartFile(file.path, filename: file.name)});

    var response = await crud.baseCrud(
        "${ApiUrls.addLogoPharmacy}/$pharmacyId", "post",
        body: body, headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  editPharmacy(
    String token,
    String pharmacyId,
    String? cookie, {
    required String phone,
    required String startTime,
    required String endTime,
    required String governorate,
    required String address,
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
      "address": address
    });

    var response = await crud.baseCrud(
        "${ApiUrls.editPharmacy}/$pharmacyId", "post",
        body: body, headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  pharmacyLogout(String token, String pharmacyId, String? cookie) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie ?? ""
    };

    var response = await crud.baseCrud(
        "${ApiUrls.logoutPharmcay}/$pharmacyId", "get",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }
}
