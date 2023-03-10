import 'package:curved_labeled_navigation_bar/curved_navigation_bar.dart';
import 'package:curved_labeled_navigation_bar/curved_navigation_bar_item.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/controllers/home/homelayout_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';

class BottomNavbar extends StatelessWidget {
  final HomeLayoutConrollerImp controller;
  const BottomNavbar({super.key, required this.controller});
  @override
  Widget build(BuildContext context) {
    return CurvedNavigationBar(
      iconPadding: 15,
      index: controller.currentPage,
      height: 65,
      backgroundColor: AppColors.whiteColor,
      buttonBackgroundColor: AppColors.primaryColor,
      color: AppColors.primaryAColor,
      animationDuration: const Duration(milliseconds: 300),
      items: [
        linkItem(
            context, controller, FontAwesomeIcons.solidUser, 0, "البروفايل",
            size: 22),
        linkItem(context, controller, FontAwesomeIcons.house, 1, "الرئيسيه",
            size: 22),
        linkItem(context, controller, FontAwesomeIcons.gear, 2, "الاعدادت",
            size: 22),
        linkItem(context, controller, FontAwesomeIcons.arrowRightFromBracket, 3,
            "تسجيل الخروج",
            size: 22),
      ],
      onTap: (value) {
        controller.onChangePage(value);
      },
    );
  }

  CurvedNavigationBarItem linkItem(BuildContext context, controller,
          IconData icon, int index, String label,
          {double? size}) =>
      CurvedNavigationBarItem(
        label: label,
        labelStyle: const TextStyle(fontSize: 13),
        child: FaIcon(
          icon,
          size: size ?? 26,
          color: checkPage(controller.currentPage, index),
        ),
      );

  Color? checkPage(int index, int current) {
    if (index == current) {
      return AppColors.whiteColor;
    } else {
      return null;
    }
  }
}
