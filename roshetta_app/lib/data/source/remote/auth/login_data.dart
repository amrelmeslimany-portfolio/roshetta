import 'dart:io';

import 'package:get/get.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class LoginData {
  Crud crud;
  LoginData(this.crud);

  // Curd Methods
  postData(String accountType, String emailOrSsd, String password) async {
    FormData body = FormData(
        {"role": accountType, "user_id": emailOrSsd, "password": password});

    var response = await crud.baseCrud(ApiUrls.login, "post", body: body);

    return response.fold((l) => l, (r) => r);
  }

  logout(String token) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token"
    };

    var response = await crud.baseCrud(ApiUrls.logout, "get", headers: headers);

    return response.fold((l) => l, (r) => r);
  }
}
