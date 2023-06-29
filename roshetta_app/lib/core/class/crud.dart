import 'package:dartz/dartz.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/services/init_services.dart';

class Crud {
  InitServices services = Get.put(InitServices());

  Future<Either<RequestStatus, Map>> baseCrud(
    String url,
    String method, {
    String? baseAPI,
    FormData? body,
    Map<String, String>? headers,
    Map<String, dynamic>? query,
  }) async {
    // TODO related to API server
    // String api = services.sharedPreferences.getString(ApiUrls.clientAPI) ??
    //     dotenv.get(ApiUrls.clientAPI);
    String api =
        services.sharedPreferences.getString(ApiUrls.clientAPI) ?? "فارغ";

    try {
      // TODO return this condition
      // if (await checkInternet()) {
      var response = await GetConnect(
              maxAuthRetries: 2, timeout: const Duration(minutes: 1))
          .request(Uri.parse((baseAPI ?? api) + url).toString(), method,
              body: body, headers: headers, query: query);

      // print(response.body);

      if (response.isOk) {
        String? cookie = response.headers?["set-cookie"];

        if (cookie != null) {
          int index = cookie.indexOf(';');
          services.sharedPreferences.setString(
              "cookies", (index == -1) ? cookie : cookie.substring(0, index));
        }

        var responseBody = response.body;
        return Right(responseBody);
      } else {
        return const Left(RequestStatus.serverFailure);
      }
      // } else {
      //   return const Left(RequestStatus.offlineFailure);
      // }
    } catch (e) {
      print({"crud class": e});
      return const Left(RequestStatus.failure);
    }
  }
}
