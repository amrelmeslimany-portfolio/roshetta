import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';

import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/data/source/remote/auth/forgotpassword_data.dart';
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
  ForgotPasswordData requests = ForgotPasswordData(Get.find<Crud>());
  RequestStatus status = RequestStatus.none;

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
  void onSubmit(BuildContext context) async {
    if (Get.isSnackbarOpen) return;
    if (formKey.currentState!.validate()) {
      formKey.currentState!.save();
      status = RequestStatus.loading;
      update();
      var response = await requests.postData(accountType, ssdOrEmail.text);
      status = checkResponseStatus(response);
      if (status == RequestStatus.success) {
        diplayDialog(context);
      } else {
        handleSnackErrors(response);
      }

      update();
    }
  }

  diplayDialog(BuildContext context) {
    Get.defaultDialog(
        content: const AuthDiologs(
            icon: FontAwesomeIcons.envelopeCircleCheck,
            title: "تم الارسال بنجاح",
            content: "تم ارسال كود الي الايميل الخاص بك, قم بفحص الايميل جيدا"),
        contentPadding: const EdgeInsets.all(15),
        barrierDismissible: true,
        actions: [
          BGButton(context, text: "ادخل الكود", onPressed: () => Get.back())
              .button
        ]).then((value) => Get.toNamed(AppRoutes.verifyForgotPassCode,
        arguments: {"role": accountType, "ssdOrEmail": ssdOrEmail.text}));

    @override
    void onClose() {
      ssdOrEmail.dispose();
      super.onClose();
    }
  }
}
