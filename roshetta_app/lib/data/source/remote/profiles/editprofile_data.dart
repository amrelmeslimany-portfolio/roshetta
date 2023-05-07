import 'dart:io';

import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';

import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class EditProfileData {
  Crud crud;
  EditProfileData(this.crud);

  postProfileImage(String token, XFile img) async {
    Map<String, String> headers = {
      HttpHeaders.authorizationHeader: 'Bearer $token'
    };

    FormData? body = FormData({
      "image": MultipartFile(img.path, filename: img.name),
    });

    var response = await crud.baseCrud(ApiUrls.addProfileImage, "post",
        headers: headers, body: body);

    return response.fold((l) => l, (r) => r);
  }

  postProfileEdit(String token, String phone, String governorate,
      String? weight, String? height) async {
    Map<String, String> headers = {
      HttpHeaders.authorizationHeader: 'Bearer $token'
    };

    FormData? body = FormData({
      "phone_number": phone,
      "governorate": governorate,
      "weight": weight ?? "",
      "height": height ?? ""
    });

    var response = await crud.baseCrud(ApiUrls.editProfile, "post",
        headers: headers, body: body);

    return response.fold((l) => l, (r) => r);
  }

  postRenewPassword(String token, String password, String repassword) async {
    Map<String, String> headers = {
      HttpHeaders.authorizationHeader: 'Bearer $token'
    };

    FormData? body =
        FormData({"password": password, "confirm_password": repassword});

    var response = await crud.baseCrud(ApiUrls.editPassword, "post",
        headers: headers, body: body);

    return response.fold((l) => l, (r) => r);
  }
}
