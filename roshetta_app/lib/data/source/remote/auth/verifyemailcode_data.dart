import 'package:get/get.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class VerifyEmailCodeData {
  Crud crud;
  VerifyEmailCodeData(this.crud);

  // Curd Methods
  postData(String role, String email, String code) async {
    FormData body = FormData({"role": role, "email": email, "code": code});

    var response =
        await crud.baseCrud(ApiUrls.verifyEmailCode, "post", body: body);

    return response.fold((l) => l, (r) => r);
  }
}
