import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

import 'package:get/get.dart';
import 'package:otp_text_field/otp_text_field.dart';
import 'package:roshetta_app/controllers/auth/login_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';

import 'package:roshetta_app/data/source/remote/auth/verifyemailcode_data.dart';

import 'package:roshetta_app/view/widgets/custom_request.dart';

import '../../core/functions/reused_functions.dart';

abstract class VerifyEmailCodeController extends GetxController {
  void onSubmit(context, String pin);
  void goToBack(BuildContext context);
}

class VerifyEmailCodeControllerImp extends VerifyEmailCodeController {
  late OtpFieldController otpTextController;
  late String accountType;
  late String email;

  VerifyEmailCodeData verifyData = VerifyEmailCodeData(Get.find<Crud>());
  RequestStatus requestStatus = RequestStatus.none;

  @override
  void onInit() {
    otpTextController = OtpFieldController();
    accountType = Get.arguments["role"];
    email = Get.arguments["email"];

    Future.delayed(
        const Duration(milliseconds: 100), () => otpTextController.setFocus(0));

    super.onInit();
  }

  @override
  void goToBack(context) {
    confirmDialog(context, text: "هل تريد حقا المغادره ؟", onConfirm: () {
      Get.offAllNamed(AppRoutes.login);
    });
  }

  @override
  void onSubmit(context, pin) async {
    requestStatus = RequestStatus.loading;
    update();

    var response = await verifyData.postData(accountType, email, pin);

    requestStatus = checkResponseStatus(response);
    if (requestStatus == RequestStatus.success) {
      successDialog(context,
          icon: FontAwesomeIcons.userCheck,
          buttonText: "تسجيل الدخول",
          content: 'تم تفعيل الايميل بنجاح, يمكنك تسجيل الدخول الان',
          onSuccess: () {
        Get.offAllNamed(AppRoutes.login);
      });
    } else if (requestStatus == RequestStatus.userFailure) {
      DialogRequestMessages(context,
          status: requestStatus, failureText: response["Message"]);
    } else {
      if (!context.mounted) return;
      DialogRequestMessages(context, status: requestStatus);
    }

    update();
  }

  @override
  void onClose() {
    super.onClose();
  }
}
