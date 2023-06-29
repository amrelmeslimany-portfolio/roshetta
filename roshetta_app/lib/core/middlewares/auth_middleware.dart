import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';

class AuthMiddleware extends GetMiddleware {
  AuthenticationController auth = Get.put(AuthenticationController());
  @override
  int? get priority => 1;

  @override
  RouteSettings? redirect(String? route) {
    if (auth.isAuth.value) {
      return const RouteSettings(name: AppRoutes.home);
    }
    return null;
  }
}

class AuthGuard extends GetMiddleware {
  AuthenticationController auth = Get.put(AuthenticationController());
  @override
  int? get priority => 1;

  @override
  RouteSettings? redirect(String? route) {
    if (!auth.isAuth.value) {
      snackbar(
          isError: true,
          title: "تم تسجيل الخروج",
          content:
              "تم الانتهاء من مده تسجيل الدخول, برجاء تسجيل الدخول مرة اخري.");
      return const RouteSettings(name: AppRoutes.intro);
    }
    return null;
  }
}
