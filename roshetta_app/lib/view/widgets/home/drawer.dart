import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/home/drawer_controller.dart';
import 'package:roshetta_app/controllers/home/homelayout_controller.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/profile/header.dart';

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
                                      icon: Icons.document_scanner,
                                      text: "قراءة روشته",
                                      iconSize: 26,
                                      isActive: Get.currentRoute ==
                                          AppRoutes.readPrescript,
                                      onTap: () {
                                        bottomNavbar.onChangePage(1);
                                        Get.toNamed(AppRoutes.readPrescript);
                                      }),
                                  const Divider(),
                                  ...drawerController.linkList
                                      .map((element) => pageItem(
                                          isActive: Get.currentRoute ==
                                              element["page"],
                                          icon: element["icon"],
                                          text: element["title"],
                                          onTap: () {
                                            bottomNavbar.onChangePage(1);
                                            Get.toNamed(element["page"]);
                                          }))
                                      .toList(),
                                  const Divider(),
                                  bottomNavbar.auth.localUser.value!.isVerify !=
                                          null
                                      ? pageItem(
                                          isActive: Get.currentRoute ==
                                              AppRoutes.verifyDPAccount,
                                          icon:
                                              FontAwesomeIcons.solidCircleCheck,
                                          text: "توثيق الحساب",
                                          onTap: () {
                                            bottomNavbar.onChangePage(1);
                                            Get.toNamed(
                                                AppRoutes.verifyDPAccount);
                                          })
                                      : const SizedBox(),
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
                                      onTap: () async {
                                        await bottomNavbar.onLogout();
                                      }),
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
                ProfileHeader(
                        title: user.name!,
                        image: user.image,
                        subTitle: user.ssd!,
                        isVerify: user.isVerify,
                        icon: FontAwesomeIcons.solidIdCard)
                    .verifiedPicture(context, radius: 45, pos: 15),
                const SizedBox(height: 10),
                CustomText(text: user.name!, align: TextAlign.start)
                    .truncateText(context),
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
          double iconSize = 20,
          required IconData icon,
          required String text,
          required Function() onTap}) =>
      Container(
          color: isActive ? AppColors.primaryAColor : null,
          child: ListTile(
            leading: FaIcon(
              icon,
              size: iconSize,
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
