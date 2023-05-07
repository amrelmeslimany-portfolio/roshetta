import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
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
        widget: Column(
      mainAxisAlignment: MainAxisAlignment.center,
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        const CustomText(
          text: "روشتة",
          color: AppColors.primaryColor,
          fontWeight: FontWeight.w900,
        ),
        const SizedBox(height: 5),
        SizedBox(
          width: Get.width / 1.4,
          child: const CustomText(
              text:
                  "نرحب بكم في تطبيق روشته الذى يساعدك في ايجاد دكتور مناسب لك",
              color: AppColors.greyColor,
              textType: 2),
        ),
        const SizedBox(height: 30),
        SvgPicture.asset(
          AssetPaths.introAuth,
          width: Get.width,
        ),
        const SizedBox(height: 30),
        Text(
          "قم باختيار نوع التسجيل الخاص بك",
          style: Theme.of(context).textTheme.titleSmall?.copyWith(
                color: AppColors.lightTextColor,
              ),
        ),
        const SizedBox(height: 15),
        BGButton(context, text: "تسجيل الدخول", onPressed: loginPressed).button,
        BorderedButton(context,
                text: "انشاء حساب", onPressed: createAccountPressed)
            .button
      ],
    ));
  }

  void loginPressed() {
    Get.toNamed(AppRoutes.login);
  }

  void createAccountPressed() {
    Get.toNamed(AppRoutes.createAccount);
  }
}
