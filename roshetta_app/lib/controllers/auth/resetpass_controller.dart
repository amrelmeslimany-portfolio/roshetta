import 'package:flutter/material.dart';

import 'package:get/get.dart';

import 'package:roshetta_app/data/source/remote/auth/forgotpassword_data.dart';

import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';

import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';

import '../../core/functions/reused_functions.dart';

abstract class ResetPassController extends GetxController {
  // Main
  void onPasswordVisibleChange();
  void onSubmit(context);
  // Checks
  bool? checkPasswordEquals();
}

class ResetPassControllerImp extends ResetPassController {
  late String accountType;
  late String email;
  late TextEditingController password;
  late TextEditingController rePassword;

  bool isVisiblePassword = true;

  GlobalKey<FormState> createAccountForm = GlobalKey<FormState>();

  RequestStatus requestStatus = RequestStatus.none;

  ForgotPasswordData createaccData = ForgotPasswordData(Get.find<Crud>());

  @override
  void onInit() async {
    accountType = Get.arguments["role"];
    email = Get.arguments["ssdOrEmail"];
    password = TextEditingController();
    rePassword = TextEditingController();

    super.onInit();
  }

  @override
  bool? checkPasswordEquals() {
    if (password.text != rePassword.text) {
      return false;
    }
    return null;
  }

  @override
  void onPasswordVisibleChange() {
    isVisiblePassword = isVisiblePassword == true ? false : true;
    update();
  }

  @override
  void onSubmit(context) async {
    if (createAccountForm.currentState!.validate()) {
      createAccountForm.currentState!.save();
      // When Loading
      requestStatus = RequestStatus.loading;
      update();

      var response = await createaccData.postResetPassword(
          accountType, email, password.text, rePassword.text);
      requestStatus = checkResponseStatus(response);

      if (requestStatus == RequestStatus.success) {
        successDialog(context,
            title: "تم تعيين كلمة المرور",
            content: "تم تعيين كلمة المرور الجديده يمكن الدخول الي حسابك الان.",
            buttonText: "دخول", onSuccess: () {
          Get.offAllNamed(AppRoutes.login);
        });
      } else if (requestStatus == RequestStatus.userFailure) {
        DialogRequestMessages(context,
            status: requestStatus, failureText: response["Message"]);
      } else {
        if (!context.mounted) return;
        DialogRequestMessages(context, status: requestStatus);
      }
    }
    update();
  }

  @override
  void onClose() {
    password.dispose();
    rePassword.dispose();
    super.onClose();
  }
}
