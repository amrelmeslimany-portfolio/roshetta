import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:otp_text_field/otp_text_field.dart';
import 'package:otp_text_field/style.dart';
import 'package:roshetta_app/controllers/auth/verifyforgotpasswordcode_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/view/widgets/auth/layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class VerifyForgotPass extends StatelessWidget {
  const VerifyForgotPass({super.key});

  @override
  Widget build(BuildContext context) {
    Get.put<VerifyForgotPassControllerImp>(VerifyForgotPassControllerImp());
    return AuthLayout(
        widget: Container(
            padding:
                const EdgeInsets.only(top: 8, bottom: 8, left: 8, right: 8),
            child: GetBuilder<VerifyForgotPassControllerImp>(
                builder: (verfiyController) {
              return CustomRequest(
                  status: verfiyController.requestStatus,
                  widget: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Container(
                        alignment: Alignment.centerRight,
                        padding: const EdgeInsets.symmetric(horizontal: 15),
                        margin: const EdgeInsets.only(bottom: 80),
                        child: InkWell(
                          child: const Icon(Icons.arrow_back,
                              color: AppColors.primaryColor, size: 30),
                          onTap: () => verfiyController.goToBack(context),
                        ),
                      ),
                      Image.asset(AssetPaths.logoIcon, width: 110),
                      const SizedBox(height: 15),
                      const CustomText(
                        text: "تأكيد الكود",
                        color: AppColors.primaryColor,
                        fontWeight: FontWeight.w800,
                      ),
                      const CustomText(
                        text:
                            "قم بادخال الكود المرسل الي الايميل الخاص بك لنتأكد هل انت صاحب الحساب بالفعل ؟ لاعادة تعيين كلمة المرور",
                        color: AppColors.lightTextColor,
                        textType: 3,
                      ),
                      const SizedBox(height: 30),
                      Directionality(
                        textDirection: TextDirection.ltr,
                        child: OTPTextField(
                          fieldStyle: FieldStyle.box,
                          controller: verfiyController.otpTextController,
                          length: 6,
                          textFieldAlignment: MainAxisAlignment.spaceBetween,
                          width: 320,
                          outlineBorderRadius: 5,
                          otpFieldStyle: OtpFieldStyle(
                              focusBorderColor: AppColors.primaryColor),
                          onChanged: (value) {
                            print(value);
                          },
                          onCompleted: (value) {
                            verfiyController.onSubmit(context, value);
                          },
                        ),
                      ),
                      const SizedBox(height: 30),
                      SizedBox(
                        width: 300,
                        child: Wrap(
                          alignment: WrapAlignment.center,
                          children: [
                            InkWell(
                              onTap: () {
                                // controller.goToLoginPage();
                                verfiyController.otpTextController.clear();
                              },
                              child: const CustomText(
                                text: "حذف الكود",
                                color: AppColors.primaryColor,
                                textType: 3,
                                fontWeight: FontWeight.w600,
                              ),
                            )
                          ],
                        ),
                      )
                    ],
                  ));
            })));
  }
}
