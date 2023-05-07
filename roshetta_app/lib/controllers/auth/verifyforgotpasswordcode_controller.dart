import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

import 'package:get/get.dart';
import 'package:otp_text_field/otp_text_field.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/source/remote/auth/forgotpassword_data.dart';

import 'package:roshetta_app/view/widgets/shared/custom_request.dart';

import '../../core/functions/reused_functions.dart';

abstract class VerifyForgotPassController extends GetxController {
  void onSubmit(context, String pin);
  void goToBack(BuildContext context);
}

class VerifyForgotPassControllerImp extends VerifyForgotPassController {
  late OtpFieldController otpTextController;
  late String accountType;
  late String email;

  ForgotPasswordData requests = ForgotPasswordData(Get.find<Crud>());
  RequestStatus requestStatus = RequestStatus.none;

  @override
  void onInit() {
    otpTextController = OtpFieldController();
    accountType = Get.arguments["role"];
    email = Get.arguments["ssdOrEmail"];

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

    var response = await requests.postVerifyCode(accountType, email, pin);

    requestStatus = checkResponseStatus(response);

    if (requestStatus == RequestStatus.success) {
      successDialog(context,
          icon: FontAwesomeIcons.userCheck,
          buttonText: "تعيين",
          content:
              'تم بنجاح التأكد من حسابك, يمكنك تعيين كلمه المرور الجديده الان',
          onSuccess: () {
        Get.offNamed(AppRoutes.resetForgotPass,
            arguments: {"ssdOrEmail": email, "role": accountType});
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
