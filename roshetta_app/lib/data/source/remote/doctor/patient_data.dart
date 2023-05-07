import 'dart:io';
import 'package:get/get.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class DoctorPatientData {
  Crud crud;
  DoctorPatientData(this.crud);

  // Curd Methods
  getPatientDetails(String token, String cookie, String clinicId,
      String patientId, String appointId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    var response = await crud.baseCrud(
        "${ApiUrls.doctorPatient}/$clinicId", "get",
        headers: headers,
        query: {"patient_id": patientId, "appoint_id": appointId});

    return response.fold((l) => l, (r) => r);
  }

  addPrescript(
    String token,
    String cookie,
    String clinicId,
    String patientId,
    String appointmentId, {
    required String type,
    required String rediscoveryDate,
    required List medicines,
    String? diseaseId,
    String? diseaseName,
    String? diseasePlace,
  }) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    Map<String, dynamic>? query = {
      "type": type,
      "patient_id": patientId,
      "disease_id": diseaseId,
      "appoint_id": appointmentId,
    };

    Map<String, dynamic> data = {
      "name": diseaseName,
      "place": diseasePlace,
      "rediscovery_date": rediscoveryDate,

      // "medicine": medicines
    };

    for (var i = 0; i < medicines.length; i++) {
      data.addAll({
        'medicine[$i][discription]': medicines[i]["description"],
        'medicine[$i][name]': medicines[i]["name"],
        'medicine[$i][size]': medicines[i]["size"],
        'medicine[$i][duration]': medicines[i]["duration"]
      });
    }

    FormData? body = FormData(data);

    var response = await crud.baseCrud(
        "${ApiUrls.doctorAddPrescript}/$clinicId", "post",
        headers: headers, query: query, body: body);

    return response.fold((l) => l, (r) => r);
  }

  getDiseasePrescript(
      String token, String cookie, String clinicId, String diseaseId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    var response = await crud.baseCrud(
        "${ApiUrls.doctorDiseasePrescript}/$clinicId", "get",
        headers: headers, query: {"disease_id": diseaseId});

    return response.fold((l) => l, (r) => r);
  }

  getDiseasePrescriptDetails(
      String token, String cookie, String clinicId, String prescriptId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    var response = await crud.baseCrud(
        "${ApiUrls.doctorPrescriptDetails}/$clinicId", "get",
        headers: headers, query: {"prescript_id": prescriptId});

    return response.fold((l) => l, (r) => r);
  }
}
