import 'package:flutter/src/widgets/navigator.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/class/auth.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
// import 'package:roshetta_app/view/widgets/home/home_layout.dart';

class AuthMiddleware extends GetMiddleware {
  @override
  int? get priority => 1;

  @override
  RouteSettings? redirect(String? route) {
    if (Authentication().isAuth) {
      return const RouteSettings(name: AppRoutes.home);
    }
    return null;
  }
}

class AuthGuard extends GetMiddleware {
  @override
  int? get priority => 1;

  @override
  RouteSettings? redirect(String? route) {
    if (!Authentication().isAuth) {
      Get.snackbar("تم تسجيل الخروج",
          "تم الانتهاء من مده تسجيل الدخول, برجاء تسجيل الدخول مرة اخري.",
          backgroundColor: AppColors.primaryAColor);

      return const RouteSettings(name: AppRoutes.intro);
    }
    return null;
  }
}
