import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/login_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/auth/layout.dart';
import 'package:roshetta_app/view/widgets/custom_request.dart';
import 'package:roshetta_app/view/widgets/custom_texts.dart';

class Login extends StatelessWidget {
  const Login({super.key});

  @override
  Widget build(BuildContext context) {
    return AuthLayout(
        widget: Container(
      padding: const EdgeInsets.only(top: 40, bottom: 8, left: 8, right: 8),
      child: GetBuilder<LoginControllerImp>(builder: (controller) {
        return Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.asset(AssetPaths.logoIcon, width: 110),
            const SizedBox(height: 15),
            const CustomText(
              text: "تسجيل الدخول",
              color: AppColors.primaryColor,
              fontWeight: FontWeight.w800,
            ),
            const CustomText(
              text: "يرجي ملئ البيانات لتسجيل الدخول",
              color: AppColors.lightTextColor,
              textType: 3,
            ),
            const SizedBox(height: 30),
            Form(
                key: controller.loginFormKey,
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
                            controller: controller.idOrEmail,
                            hintText: "البريد او الرقم القومي",
                            keyboardType: TextInputType.emailAddress,
                            icon: FontAwesomeIcons.solidUser)
                        .textfield,
                    const SizedBox(height: 15),
                    GetBuilder<LoginControllerImp>(builder: (controller) {
                      return CustomTextField(
                        context: context,
                        onValidator: (value) => fieldValidor(value!),
                        controller: controller.password,
                        hintText: "كلمة المرور",
                        icon: passwordVisibleIcon(controller.isVisiblePassword),
                        secure: controller.isVisiblePassword,
                        keyboardType: TextInputType.visiblePassword,
                        passwordTap: () {
                          controller.onPasswordVisibleChange();
                        },
                      ).textfield;
                    }),
                    const SizedBox(height: 15),
                    CustomRequest(
                        sameContent: true,
                        status: controller.requestStatus,
                        widget: Column(
                          children: [
                            Container(
                              alignment: Alignment.topRight,
                              width: 300,
                              child: InkWell(
                                onTap: () {
                                  controller.goToForgotpassword();
                                },
                                child: const CustomText(
                                  text: "هل نسيت كلمه المرور ؟",
                                  color: AppColors.primaryColor,
                                  textType: 3,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ),
                            const SizedBox(height: 15),
                            BGButton(context, text: "دخول", onPressed: () {
                              controller.onLogin(context);
                            }).button,
                            const SizedBox(height: 15),
                            SizedBox(
                              width: 300,
                              child: Wrap(
                                alignment: WrapAlignment.spaceBetween,
                                children: [
                                  const CustomText(
                                    text: "ليس لديك حساب ؟",
                                    color: AppColors.lightTextColor,
                                    textType: 3,
                                    fontWeight: FontWeight.w400,
                                  ),
                                  InkWell(
                                    onTap: () {
                                      controller.goToCreateAccount();
                                    },
                                    child: const CustomText(
                                      text: "اضغط هنا لانشاء حساب",
                                      color: AppColors.primaryColor,
                                      textType: 3,
                                      fontWeight: FontWeight.w600,
                                    ),
                                  )
                                ],
                              ),
                            )
                          ],
                        ))
                  ],
                )),
          ],
        );
      }),
    ));
  }
}
