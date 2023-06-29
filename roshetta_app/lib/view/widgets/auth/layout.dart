import 'package:clippy_flutter/arc.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class AuthLayout extends StatelessWidget {
  final bool? isSmall;
  final String pageTitle;
  final Widget widget;
  const AuthLayout(
      {super.key,
      required this.widget,
      required this.pageTitle,
      this.isSmall = true});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
          child: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [
              AppColors.gradiant1,
              AppColors.gradiant2,
            ],
          ),
        ),
        child: Stack(
          children: [
            _box("left"),
            _box("right", size: 8),
            Positioned(
                bottom: 0,
                top: 0,
                left: 0,
                right: 0,
                child: ListView(children: [
                  Arc(
                    height: 60,
                    child: Container(
                      padding: const EdgeInsets.all(30),
                      color: Colors.white,
                      child: Column(
                        children: [
                          isSmall!
                              ? Image.asset(AssetPaths.logoIcon,
                                  width: Get.height / 9)
                              : Image.asset(AssetPaths.introAuth,
                                  width: Get.height / 4),
                          const SizedBox(height: 20),
                          CustomText(
                                  text: pageTitle,
                                  color: AppColors.primaryColor)
                              .subHeader(context)
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 30),
                  widget,
                  const SizedBox(height: 50),
                  const CustomText(text: "", color: Colors.transparent)
                      .copyrightText(context,
                          color: Colors.white, boldColor: Colors.white),
                  const SizedBox(height: 15),
                ])),
          ],
        ),
      )),
    );
  }

  Widget _box(String dir, {double? size = 6.5}) {
    return Positioned(
        bottom: -25,
        left: dir == "left" ? -25 : null,
        right: dir == "right" ? -25 : null,
        child: Transform.rotate(
          angle: dir == "left" ? 60 : -60,
          child: Container(
            height: Get.height / size!,
            width: Get.height / size,
            decoration: BoxDecoration(
                color: AppColors.darkerPrimaryColor.withOpacity(0.7),
                borderRadius: const BorderRadius.all(Radius.circular(20))),
          ),
        ));
  }
}
