import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';

import '../class/users_interfaces.dart';

class DocotorPharmacistMiddleware extends GetMiddleware {
  AuthenticationController auth = Get.find<AuthenticationController>();
  @override
  int? get priority => 2;

  @override
  RouteSettings? redirect(String? route) {
    if (auth.localUser.value!.type != null &&
        auth.localUser.value!.type! != Users.doctor.name &&
        auth.localUser.value!.type! != Users.pharmacist.name) {
      snackbar(
          title: "غير مسموح لك",
          content: "غير مسموح لك بالدخول الي هذة الصفحة",
          isError: true);
      return const RouteSettings(name: AppRoutes.home);
    }
    return null;
  }
}

class DocotorPharmacistPatientMiddleware extends GetMiddleware {
  AuthenticationController auth = Get.find<AuthenticationController>();
  @override
  int? get priority => 2;

  @override
  RouteSettings? redirect(String? route) {
    if (auth.localUser.value!.type != null &&
        (auth.localUser.value!.type! != Users.doctor.name &&
            auth.localUser.value!.type! != Users.pharmacist.name &&
            auth.localUser.value!.type! != Users.patient.name)) {
      snackbar(
          title: "غير مسموح لك",
          content: "غير مسموح لك بالدخول الي هذة الصفحة",
          isError: true);
      return const RouteSettings(name: AppRoutes.home);
    }
    return null;
  }
}

class DocotorMiddleware extends GetMiddleware {
  AuthenticationController auth = Get.find<AuthenticationController>();

  @override
  int? get priority => 2;

  @override
  RouteSettings? redirect(String? route) {
    if (auth.localUser.value?.type != null &&
        auth.localUser.value?.type != Users.doctor.name) {
      snackbar(
        title: "غير مسموح لك",
        content: "غير مسموح لك بالدخول الي هذة الصفحة",
        isError: true,
      );
      return const RouteSettings(name: AppRoutes.home);
    }
    return null;
  }
}

class PharmacistMiddleware extends GetMiddleware {
  AuthenticationController auth = Get.find<AuthenticationController>();
  @override
  int? get priority => 2;

  @override
  RouteSettings? redirect(String? route) {
    if (auth.localUser.value?.type != null &&
        auth.localUser.value!.type != Users.pharmacist.name) {
      snackbar(
        title: "غير مسموح لك",
        content: "غير مسموح لك بالدخول الي هذة الصفحة",
        isError: true,
      );
      return const RouteSettings(name: AppRoutes.home);
    }
    return null;
  }
}

class AssistantMiddleware extends GetMiddleware {
  AuthenticationController auth = Get.find<AuthenticationController>();
  @override
  int? get priority => 2;

  @override
  RouteSettings? redirect(String? route) {
    if (auth.localUser.value?.type != null &&
        auth.localUser.value!.type != Users.assistant.name) {
      snackbar(
        title: "غير مسموح لك",
        content: "غير مسموح لك بالدخول الي هذة الصفحة",
        isError: true,
      );
      return const RouteSettings(name: AppRoutes.home);
    }
    return null;
  }
}

class PatientMiddleware extends GetMiddleware {
  AuthenticationController auth = Get.find<AuthenticationController>();
  @override
  int? get priority => 2;

  @override
  RouteSettings? redirect(String? route) {
    if (auth.localUser.value?.type != null &&
        auth.localUser.value!.type != Users.patient.name) {
      snackbar(
        title: "غير مسموح لك",
        content: "غير مسموح لك بالدخول الي هذة الصفحة",
        isError: true,
      );
      return const RouteSettings(name: AppRoutes.home);
    }
    return null;
  }
}
