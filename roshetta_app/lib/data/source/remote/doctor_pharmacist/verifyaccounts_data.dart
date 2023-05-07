import 'dart:io';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class VerifyAccountsData {
  Crud crud;
  VerifyAccountsData(this.crud);

  postVerifiedImage(String token,
      {required XFile frontNational,
      required XFile backNational,
      required XFile ceritificate,
      required XFile cardId}) async {
    Map<String, String> headers = {
      HttpHeaders.authorizationHeader: 'Bearer $token'
    };

    FormData? body = FormData({
      "front_nationtional_card":
          MultipartFile(frontNational.path, filename: frontNational.name),
      "back_nationtional_card":
          MultipartFile(backNational.path, filename: backNational.name),
      "graduation_cer":
          MultipartFile(ceritificate.path, filename: ceritificate.name),
      "card_id_img": MultipartFile(cardId.path, filename: cardId.name),
    });

    var response = await crud.baseCrud(ApiUrls.verifyAccountImages, "post",
        headers: headers, body: body);

    return response.fold((l) => l, (r) => r);
  }

  getVerifyStatus(String token) async {
    Map<String, String> headers = {
      HttpHeaders.authorizationHeader: 'Bearer $token'
    };

    var response =
        await crud.baseCrud(ApiUrls.viewAccountStatus, "get", headers: headers);

    return response.fold((l) => l, (r) => r);
  }
}
