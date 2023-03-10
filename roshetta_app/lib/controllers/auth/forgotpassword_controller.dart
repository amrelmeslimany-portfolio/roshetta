import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';

import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/widgets/auth/auth_dialogs.dart';

abstract class ForgotPasswordController extends GetxController {
  void onSubmit(BuildContext context);
  void onAccountTypeChange(String value);
  void goToLoginPage();
}

class ForgotPasswordControllerImp extends ForgotPasswordController {
  late String accountType;
  late TextEditingController ssdOrEmail;
  bool isVisiblePassword = true;

  GlobalKey<FormState> formKey = GlobalKey<FormState>();

  @override
  void onInit() {
    accountType = "";
    ssdOrEmail = TextEditingController();
    super.onInit();
  }

  @override
  void goToLoginPage() {
    Get.offNamed(AppRoutes.login);
  }

  @override
  void onAccountTypeChange(String value) {
    accountType = value;
  }

  @override
  void onSubmit(BuildContext context) {
    if (formKey.currentState!.validate()) {
      diplayDialog(context);
    }
  }

  @override
  void onClose() {
    ssdOrEmail.dispose();
    super.onClose();
  }
}

diplayDialog(BuildContext context) {
  Get.defaultDialog(
      content: const AuthDiologs(
          icon: FontAwesomeIcons.envelopeCircleCheck,
          title: "تم الارسال بنجاح",
          content:
              "تم ارسال رساله الي الايميل الخاص بك, قم بفحص الايميل جيدا لتري ما به من رسائل"),
      contentPadding: const EdgeInsets.all(15),
      barrierDismissible: true,
      actions: [
        BGButton(context, text: "تسجيل الدخول", onPressed: () => Get.back())
            .button
      ]).then((value) => Get.offNamed(AppRoutes.login));
}
