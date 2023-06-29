import 'package:flutter/material.dart';
import 'package:get/get.dart';

import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/controllers/auth/forgotpassword_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/auth/layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class ForgotPassword extends StatelessWidget {
  const ForgotPassword({super.key});

  @override
  Widget build(BuildContext context) {
    return AuthLayout(
        pageTitle: "نسيت كلمة المرور",
        widget: Container(
          padding: const EdgeInsets.only(top: 40, bottom: 8, left: 8, right: 8),
          child: GetBuilder<ForgotPasswordControllerImp>(builder: (controller) {
            return Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Form(
                    key: controller.formKey,
                    child: CustomRequest(
                      loadingColor: Colors.white,
                      sameContent: true,
                      status: controller.status,
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
                                  controller: controller.ssdOrEmail,
                                  hintText: "البريد او الرقم القومي",
                                  keyboardType: TextInputType.emailAddress,
                                  icon: FontAwesomeIcons.solidUser)
                              .textfield,
                          const SizedBox(height: 15),
                          Column(
                            children: [
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
