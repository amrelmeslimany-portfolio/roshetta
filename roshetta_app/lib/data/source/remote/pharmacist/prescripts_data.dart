import 'dart:io';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class PharmacistPrescriptsData {
  Crud crud;
  PharmacistPrescriptsData(this.crud);

  getPrescripts(String token, String cookie, String pharmacyId,
      {String? filter}) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    Map<String, dynamic> query = {"filter": filter};

    var response = await crud.baseCrud(
        "${ApiUrls.pharmacyOrders}/$pharmacyId", "get",
        headers: headers, query: query);

    return response.fold((l) => l, (r) => r);
  }

  viewPrescript(String token, String pharmacyId, String? cookie,
      {String? id, String? idType}) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie ?? ""
    };

    print(idType);

    var response = await crud.baseCrud(
        "${ApiUrls.pharmacyPrescript}/$pharmacyId", "get",
        headers: headers, query: {"user_id": id, "type": idType});

    return response.fold((l) => l, (r) => r);
  }

  viewPaidPrescript(String token, String pharmacyId, String? cookie,
      {String? filter}) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie ?? ""
    };

    var response = await crud.baseCrud(
        "${ApiUrls.pharmacyViewPaid}/$pharmacyId", "get",
        headers: headers, query: {"filter": filter});

    return response.fold((l) => l, (r) => r);
  }

  confirmPrescript(
      String token, String pharmacyId, String? cookie, String prescriptId,
      {String? orderId}) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie ?? ""
    };

    print(orderId);

    var response = await crud.baseCrud(
        "${ApiUrls.pharmacyConfirmPrescript}/$pharmacyId", "post",
        headers: headers,
        query: {"prescript_id": prescriptId, "order_id": orderId});

    return response.fold((l) => l, (r) => r);
  }
}
