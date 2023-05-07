import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/resetpass_controller.dart';
import 'package:roshetta_app/core/class/fields_interface.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/view/widgets/auth/layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class ResetPassword extends StatelessWidget {
  const ResetPassword({super.key});

  @override
  Widget build(BuildContext context) {
    Get.put<ResetPassControllerImp>(ResetPassControllerImp());
    return AuthLayout(
        widget: Container(
      padding: const EdgeInsets.only(top: 40, bottom: 8, left: 8, right: 8),
      child: GetBuilder<ResetPassControllerImp>(builder: (controller) {
        return Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.asset(AssetPaths.logoIcon, width: 110),
            const SizedBox(height: 15),
            const CustomText(
              text: "اعادة التعيين",
              color: AppColors.primaryColor,
              fontWeight: FontWeight.w800,
            ),
            const CustomText(
              text: "يرجي ملئ بيانات اعاده تعيين كلمة المرور الجديده",
              color: AppColors.lightTextColor,
              textType: 3,
            ),
            const SizedBox(height: 30),
            Form(
              key: controller.createAccountForm,
              child: CustomRequest(
                sameContent: true,
                status: controller.requestStatus,
                widget: Column(
                  children: [
                    CustomTextField(
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
                    ).textfield,
                    const SizedBox(height: 15),
                    CustomTextField(
                      context: context,
                      onValidator: (value) {
                        return fieldValidor(value!,
                            type: FieldsTypes.repassword,
                            passwordsEquals: controller.checkPasswordEquals());
                      },
                      controller: controller.rePassword,
                      hintText: "اعد كلمة المرور",
                      icon: passwordVisibleIcon(controller.isVisiblePassword),
                      secure: controller.isVisiblePassword,
                      keyboardType: TextInputType.visiblePassword,
                      passwordTap: () {
                        controller.onPasswordVisibleChange();
                      },
                    ).textfield,
                    const SizedBox(height: 15),
                    Column(
                      children: [
                        BGButton(context, text: "حفظ", onPressed: () {
                          controller.onSubmit(context);
                        }).button,
                      ],
                    )
                  ],
                ),
              ),
            ),
          ],
        );
      }),
    ));
  }
}
