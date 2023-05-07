import 'package:dartz/dartz.dart';
import 'package:get/get.dart';

import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/services/init_services.dart';

class Crud {
  InitServices services = Get.put(InitServices());

  Future<Either<RequestStatus, Map>> baseCrud(
    String url,
    String method, {
    FormData? body,
    Map<String, String>? headers,
    Map<String, dynamic>? query,
  }) async {
    try {
      if (await checkInternet()) {
        var response = await GetConnect(
                maxAuthRetries: 2, timeout: const Duration(minutes: 1))
            .request(Uri.parse(url).toString(), method,
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
      } else {
        return const Left(RequestStatus.offlineFailure);
      }
    } catch (e) {
      print({"crud class": e});
      return const Left(RequestStatus.failure);
    }
  }
}
