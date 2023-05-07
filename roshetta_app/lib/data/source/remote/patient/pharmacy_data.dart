import 'dart:io';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class PatientPharmacyData {
  Crud crud;
  PatientPharmacyData(this.crud);

  getPharmacy(String token) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response =
        await crud.baseCrud(ApiUrls.patientPharmacy, "get", headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  getPharmacyDetails(String token, String id) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response = await crud.baseCrud(
        "${ApiUrls.patientPharmacyDetails}/$id", "get",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  sendPrescript(String token, String pharmacyId, String prescriptId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response = await crud.baseCrud(
        "${ApiUrls.patientPharmacyOrder}/$pharmacyId", "post",
        headers: headers,
        query: {"prescript_id": prescriptId, "pharmacy_id": pharmacyId});

    return response.fold((l) => l, (r) => r);
  }

  // Orders

  viewOrders(String token) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response =
        await crud.baseCrud(ApiUrls.patientViewOrders, "get", headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  deleteOrder(String token, String orderId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response = await crud.baseCrud(ApiUrls.patientDeleteOrder, "post",
        headers: headers, query: {"order_id": orderId});

    return response.fold((l) => l, (r) => r);
  }
}
