import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/class/auth.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/view/screens/home/home.dart';
import 'package:roshetta_app/view/screens/profile/my_profile.dart';
import 'package:roshetta_app/view/screens/settings.dart';

abstract class HomeLayoutConroller extends GetxController {
  void onChangePage(int index);
}

class HomeLayoutConrollerImp extends HomeLayoutConroller {
  late int currentPage;

  List<dynamic> pages(GlobalKey<ScaffoldState> drawer) => [
        MyProfile(drawerState: drawer),
        Home(drawerState: drawer),
        Settings(drawerState: drawer),
      ];

  @override
  void onInit() {
    currentPage = 1;
    super.onInit();
  }

  @override
  void onChangePage(int index) {
    if (index == 3) {
      Authentication().logout();
      return;
    }

    currentPage = index;

    if (Get.currentRoute != AppRoutes.home && index != 1) {
      Get.offAndToNamed(AppRoutes.home);
    }

    update();
  }
}
