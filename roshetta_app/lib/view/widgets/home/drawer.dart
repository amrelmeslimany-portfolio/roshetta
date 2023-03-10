import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/home/drawer_controller.dart';
import 'package:roshetta_app/controllers/home/homelayout_controller.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/view/widgets/custom_texts.dart';

import '../../../core/constants/app_colors.dart';

class CustomDrawer extends StatelessWidget {
  final GlobalKey<ScaffoldState> drawer;
  const CustomDrawer({super.key, required this.drawer});

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Drawer(
        backgroundColor: Colors.transparent,
        child: Container(
          color: AppColors.whiteColor,
          padding: EdgeInsets.zero,
          child: GetBuilder<DrawerControllerImp>(
              init: DrawerControllerImp(),
              builder: (drawerController) {
                return Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    SizedBox(
                        width: 304,
                        height: 185,
                        child: drawerHeader(context, drawerController.user)),
                    Expanded(
                      child: Container(
                        padding: EdgeInsets.zero,
                        child: GetBuilder<HomeLayoutConrollerImp>(
                            init: HomeLayoutConrollerImp(),
                            builder: (bottomNavbar) {
                              return ListView(
                                padding: EdgeInsets.zero,
                                shrinkWrap: true,
                                children: [
                                  pageItem(
                                      isActive:
                                          Get.currentRoute == AppRoutes.home &&
                                              bottomNavbar.currentPage == 1,
                                      icon: FontAwesomeIcons.house,
                                      text: "الرئيسية",
                                      onTap: () {
                                        bottomNavbar.onChangePage(1);
                                        Get.toNamed(AppRoutes.home);
                                      }),
                                  pageItem(
                                      isActive:
                                          Get.currentRoute == AppRoutes.home &&
                                              bottomNavbar.currentPage == 0,
                                      icon: FontAwesomeIcons.solidUser,
                                      text: "البروفايل",
                                      onTap: () {
                                        bottomNavbar.onChangePage(0);
                                        Get.toNamed(AppRoutes.home);
                                      }),
                                  const Divider(),
                                  pageItem(
                                      isActive: Get.currentRoute ==
                                          AppRoutes.appointments,
                                      icon: FontAwesomeIcons.tableCells,
                                      text: "الحجوزات",
                                      onTap: () {
                                        Get.toNamed(AppRoutes.appointments);
                                        bottomNavbar.onChangePage(1);
                                      }),
                                  const Divider(),
                                  pageItem(
                                      isActive:
                                          Get.currentRoute == AppRoutes.home &&
                                              bottomNavbar.currentPage == 2,
                                      icon: FontAwesomeIcons.gear,
                                      text: "الاعدادات",
                                      onTap: () {
                                        bottomNavbar.onChangePage(2);
                                        Get.toNamed(AppRoutes.home);
                                      }),
                                  pageItem(
                                      icon: FontAwesomeIcons
                                          .arrowRightFromBracket,
                                      text: "تسجيل الخروج",
                                      onTap: () {}),
                                ],
                              );
                            }),
                      ),
                    ),
                    const Divider(height: 0),
                    Padding(
                      padding: const EdgeInsets.all(15),
                      child: const CustomText(
                        text: "",
                        color: null,
                      ).copyrightText(context),
                    ),
                  ],
                );
              }),
        ),
      ),
    );
  }

  Widget drawerHeader(BuildContext context, LocalUser user) => Stack(
        alignment: Alignment.center,
        children: [
          overlayImag(AssetPaths.drawer),
          Container(
            width: double.maxFinite,
            padding: const EdgeInsets.all(15),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(child: shadowCircleAvatar(user.image!)),
                const SizedBox(height: 10),
                CustomText(text: user.name!).truncateText(context),
                iconAndWidget(FontAwesomeIcons.idCard,
                    widget: Text(
                      user.ssd!,
                      style: Theme.of(context).textTheme.bodyMedium!.copyWith(
                          color: AppColors.lightenWhiteColor,
                          fontWeight: FontWeight.w400),
                    )),
              ],
            ),
          )
        ],
      );

  Widget pageItem(
          {bool isActive = false,
          required IconData icon,
          required String text,
          required Function() onTap}) =>
      Container(
          color: isActive ? AppColors.primaryAColor : null,
          child: ListTile(
            leading: FaIcon(
              icon,
              size: 20,
              color: isActive ? AppColors.primaryColor : null,
            ),
            minLeadingWidth: 30,
            trailing: isActive
                ? Container(
                    height: 40,
                    width: 2,
                    color: AppColors.primaryColor,
                  )
                : const SizedBox(),
            title: Text(
              text,
              style: TextStyle(
                  color:
                      isActive ? AppColors.primaryColor : AppColors.greyColor),
            ),
            onTap: () {
              toggleDrawer(drawer, open: false);
              onTap();
            },
          ));
}
