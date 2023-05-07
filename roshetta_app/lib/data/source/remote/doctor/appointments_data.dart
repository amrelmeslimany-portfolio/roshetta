import 'dart:io';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class DoctorAppointmentsData {
  Crud crud;
  Users role;
  DoctorAppointmentsData(this.crud, {this.role = Users.doctor});

  bool isAssistant() {
    if (role == Users.assistant) {
      return true;
    } else {
      return false;
    }
  }

  // Curd Methods
  getAppointments(String token, String cookie, String clinicId,
      {String? filter, DateTime? date, String? status}) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    var response = await crud.baseCrud(
        "${isAssistant() ? ApiUrls.assistantAppoints : ApiUrls.doctorAppointments}/$clinicId",
        "get",
        headers: headers,
        query: {
          "filter": filter,
          "date": date != null ? date.toString() : "",
          "status": status
        });

    return response.fold((l) => l, (r) => r);
  }

  editAppointStatus(String token, String cookie, String clinicId,
      {String? appointmentId}) async {
    Map<String, String>? headers = {
      HttpHeaders.authorizationHeader: "Bearer $token",
      HttpHeaders.cookieHeader: cookie
    };

    var response = await crud.baseCrud(
        "${ApiUrls.assistantAppointModify}/$clinicId", "post",
        headers: headers,
        query: {
          "appointment_id": appointmentId,
        });

    return response.fold((l) => l, (r) => r);
  }
}
