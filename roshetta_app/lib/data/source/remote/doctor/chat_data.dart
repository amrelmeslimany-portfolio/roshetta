import 'dart:io';
import 'package:get/get.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class DoctorChatData {
  Crud crud;
  DoctorChatData(this.crud);

  // Curd Methods
  messages(String token, {String? messageId, String? message}) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token"
    };

    var response = await crud.baseCrud(ApiUrls.doctorChat, "post",
        headers: headers,
        query: {"chat_id": messageId},
        body: FormData({"message": message}));

    return response.fold((l) => l, (r) => r);
  }
}
