import 'dart:io';

import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class PatientPrescriptsData {
  Crud crud;
  PatientPrescriptsData(this.crud);

  getPrescripts(String token, {String? diseaseId}) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response = await crud.baseCrud(ApiUrls.patientPrescripts, "get",
        headers: headers, query: {"disease_id": diseaseId});

    return response.fold((l) => l, (r) => r);
  }

  getPrescriptDetails(String token, String prescriptId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response = await crud.baseCrud(ApiUrls.patientPrescriptDetails, "get",
        headers: headers, query: {"prescript_id": prescriptId});

    return response.fold((l) => l, (r) => r);
  }

  getDiseases(String token) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response =
        await crud.baseCrud(ApiUrls.patientDiseases, "get", headers: headers);

    return response.fold((l) => l, (r) => r);
  }
}
