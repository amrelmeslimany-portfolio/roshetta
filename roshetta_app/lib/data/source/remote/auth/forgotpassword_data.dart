import 'package:get/get.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class ForgotPasswordData {
  Crud crud;

  ForgotPasswordData(this.crud);

  postData(String role, String userId) async {
    FormData body = FormData({"role": role, "user_id": userId});

    var response =
        await crud.baseCrud(ApiUrls.forgotPassword, "post", body: body);

    return response.fold((l) => l, (r) => r);
  }

  postVerifyCode(String role, String userId, String code) async {
    FormData body = FormData({"role": role, "user_id": userId, "code": code});

    var response = await crud.baseCrud(ApiUrls.verifyForgotPasswordCode, "post",
        body: body);

    return response.fold((l) => l, (r) => r);
  }

  postResetPassword(String role, String userId, String password,
      String confirmPassword) async {
    FormData body = FormData({
      "role": role,
      "user_id": userId,
      "password": password,
      "confirm_password": confirmPassword
    });

    var response =
        await crud.baseCrud(ApiUrls.resetForgotPass, "post", body: body);

    return response.fold((l) => l, (r) => r);
  }
}
