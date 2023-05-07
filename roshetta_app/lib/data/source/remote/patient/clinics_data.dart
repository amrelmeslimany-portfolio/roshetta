import 'dart:io';

import 'package:get/get.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';

class PatientClinicData {
  Crud crud;
  PatientClinicData(this.crud);

  getClinics(String token, String filter) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    Map<String, dynamic> quires = {"filter": filter};

    var response = await crud.baseCrud(ApiUrls.patientClinics, "get",
        headers: headers, query: quires);

    return response.fold((l) => l, (r) => r);
  }

  getClinicDetails(String token, String id) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response = await crud.baseCrud(
        "${ApiUrls.patientClinicDetails}/$id", "get",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  addAppointment(String token, String date, String clinicId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    FormData body = FormData({"appoint_date": getParsedDate(date)});

    var response = await crud.baseCrud(
        "${ApiUrls.patientAddAppointment}/$clinicId", "post",
        headers: headers, body: body);

    return response.fold((l) => l, (r) => r);
  }

  // Appointments

  getAppointments(String token) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response = await crud.baseCrud(ApiUrls.patientViewAppointments, "get",
        headers: headers);

    return response.fold((l) => l, (r) => r);
  }

  editAppointments(String token, String appointId, String date) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    FormData body = FormData({"appoint_date": getParsedDate(date)});

    var response = await crud.baseCrud(ApiUrls.patientEditAppointments, "post",
        headers: headers, query: {"appoint_id": appointId}, body: body);

    return response.fold((l) => l, (r) => r);
  }

  deleteAppointments(String token, String appointId) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
    };

    var response = await crud.baseCrud(
        ApiUrls.patientDeleteAppointments, "post",
        headers: headers, query: {"appoint_id": appointId});

    return response.fold((l) => l, (r) => r);
  }
}
