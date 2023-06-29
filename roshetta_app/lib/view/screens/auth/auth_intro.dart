import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';

import 'package:roshetta_app/view/widgets/auth/layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class AuthIntro extends StatelessWidget {
  const AuthIntro({super.key});

  @override
  Widget build(BuildContext context) {
    return AuthLayout(
        isSmall: false,
        pageTitle: "مرحبا",
        widget: Padding(
          padding: EdgeInsets.only(top: Get.height / 25),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              const CustomText(
                text: "روشتة",
                color: Colors.white,
                fontWeight: FontWeight.w900,
              ),
              const SizedBox(height: 5),
              const CustomText(
                  text: "قم باختيار نوع التسجيل الخاص بك",
                  color: Colors.white,
                  textType: 3),
              const SizedBox(height: 15),
              BGButton(context, text: "تسجيل الدخول", onPressed: loginPressed)
                  .button,
              const SizedBox(height: 5),
              BGButton(context,
                      text: "انشاء حساب",
                      bgColor: AppColors.secondryPrimary,
                      onPressed: createAccountPressed)
                  .button
            ],
          ),
        ));
  }

  void loginPressed() {
    Get.toNamed(AppRoutes.login);
  }

  void createAccountPressed() {
    Get.toNamed(AppRoutes.createAccount);
  }
}
