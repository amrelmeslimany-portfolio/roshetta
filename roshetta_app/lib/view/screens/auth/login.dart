import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/login_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/auth/layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class Login extends StatelessWidget {
  const Login({super.key});

  @override
  Widget build(BuildContext context) {
    return AuthLayout(
        pageTitle: "تسجيل الدخول",
        widget: Padding(
          padding: const EdgeInsets.only(top: 10),
          child: GetBuilder<LoginControllerImp>(builder: (controller) {
            return Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Form(
                    key: controller.loginFormKey,
                    child: CustomRequest(
                      loadingColor: Colors.white,
                      sameContent: true,
                      status: controller.requestStatus,
                      widget: Column(
                        children: [
                          CustomDropdown(
                              context: context,
                              initalVal: controller.accountType.isNotEmpty
                                  ? controller.accountType
                                  : null,
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
                          CustomTextField(
                            context: context,
                            onValidator: (value) => fieldValidor(value!),
                            controller: controller.password,
                            hintText: "كلمة المرور",
                            icon: passwordVisibleIcon(
                                controller.isVisiblePassword),
                            secure: controller.isVisiblePassword,
                            keyboardType: TextInputType.visiblePassword,
                            passwordTap: () {
                              controller.onPasswordVisibleChange();
                            },
                          ).textfield,
                          const SizedBox(height: 15),
                          Column(
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
                                    color: Colors.white,
                                    textType: 3,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                              ),
                              const SizedBox(height: 15),
                              BGButton(context, text: "دخول", onPressed: () {
                                FocusManager.instance.primaryFocus!.unfocus();
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
                                      color: AppColors.whiteColor,
                                      textType: 3,
                                      fontWeight: FontWeight.w400,
                                    ),
                                    InkWell(
                                      onTap: () {
                                        controller.goToCreateAccount();
                                      },
                                      child: const CustomText(
                                        text: "اضغط هنا لانشاء حساب",
                                        color: AppColors.whiteColor,
                                        textType: 3,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    )
                                  ],
                                ),
                              )
                            ],
                          )
                        ],
                      ),
                    )),
              ],
            );
          }),
        ));
  }
}
