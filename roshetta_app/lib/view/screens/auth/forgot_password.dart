import 'package:flutter/material.dart';
import 'package:get/get.dart';

import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/controllers/auth/forgotpassword_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/auth/layout.dart';
import 'package:roshetta_app/view/widgets/custom_texts.dart';

class ForgotPassword extends StatelessWidget {
  const ForgotPassword({super.key});

  @override
  Widget build(BuildContext context) {
    return AuthLayout(
        widget: Container(
      padding: const EdgeInsets.only(top: 40, bottom: 8, left: 8, right: 8),
      child: GetBuilder<ForgotPasswordControllerImp>(builder: (controller) {
        return Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.asset(AssetPaths.logoIcon, width: 110),
            const SizedBox(height: 15),
            const CustomText(
              text: "نسيت كلمة المرور ؟",
              color: AppColors.primaryColor,
              fontWeight: FontWeight.w800,
            ),
            const CustomText(
              text: "قم بادخال البريد أو الرقم القومي",
              color: AppColors.lightTextColor,
              textType: 3,
            ),
            const SizedBox(height: 30),
            Form(
                key: controller.formKey,
                child: Column(
                  children: [
                    CustomDropdown(
                        context: context,
                        onValidator: (value) => dropdownValidator(value),
                        hintText: "اختر نوع الحساب",
                        items: StaticData.usersList,
                        onChange: (value) {
                          controller.onAccountTypeChange(value!);
                        }).dropdown,
                    const SizedBox(height: 15),
                    CustomTextField(
                            context: context,
                            onValidator: (value) => fieldValidor(value!),
                            controller: controller.ssdOrEmail,
                            hintText: "البريد او الرقم القومي",
                            keyboardType: TextInputType.emailAddress,
                            icon: FontAwesomeIcons.solidUser)
                        .textfield,
                    const SizedBox(height: 15),
                    BGButton(context, text: "ارسال", onPressed: () {
                      controller.onSubmit(context);
                    }).button,
                    const SizedBox(height: 15),
                    SizedBox(
                      width: 300,
                      child: Wrap(
                        alignment: WrapAlignment.center,
                        children: [
                          InkWell(
                            onTap: () {
                              controller.goToLoginPage();
                            },
                            child: const CustomText(
                              text: "الرجوع لتسجيل الدخول",
                              color: AppColors.primaryColor,
                              textType: 3,
                              fontWeight: FontWeight.w600,
                            ),
                          )
                        ],
                      ),
                    )
                  ],
                )),
          ],
        );
      }),
    ));
  }
}
