import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/auth.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/view/screens/home/home.dart';
import 'package:roshetta_app/view/screens/profile/my_profile.dart';
import 'package:roshetta_app/view/screens/settings.dart';

abstract class HomeLayoutConroller extends GetxController {
  void onChangePage(int index);
  Future<void> onLogout();
}

class HomeLayoutConrollerImp extends HomeLayoutConroller {
  late int currentPage;

  AuthenticationController auth = Get.find<AuthenticationController>();

  List<dynamic> pages(GlobalKey<ScaffoldState> drawer) => [
        MyProfile(drawerState: drawer),
        Home(drawerState: drawer),
        Settings(drawerState: drawer),
      ];

  @override
  void onInit() async {
    currentPage = 1;
    auth.getVerifyStatus();
    super.onInit();
  }

  @override
  Future<void> onLogout() async {
    await auth.logout();
  }

  @override
  void onChangePage(int index) async {
    if (index == 3) {
      await onLogout();

      return;
    }

    currentPage = index;

    if (Get.currentRoute != AppRoutes.home && (index != 1 || index != 0)) {
      Get.offAndToNamed(AppRoutes.home);
    }

    update();
  }
}
