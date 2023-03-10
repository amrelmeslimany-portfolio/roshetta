import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/class/crud.dart';

import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/services/init_services.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/data/source/remote/auth/login_data.dart';
import 'package:roshetta_app/view/screens/home/home.dart';
import 'package:roshetta_app/view/widgets/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';

abstract class LoginController extends GetxController {
  void onLogin(context);
  void onPasswordVisibleChange();
  void onAccountTypeChange(String value);
  void goToCreateAccount();
  void goToForgotpassword();
}

class LoginControllerImp extends LoginController {
  late String accountType;
  late TextEditingController idOrEmail;
  late TextEditingController password;
  bool isVisiblePassword = true;

  GlobalKey<FormState> loginFormKey = GlobalKey<FormState>();

  LoginData loginData = LoginData(Get.find<Crud>());

  RequestStatus requestStatus = RequestStatus.none;

  InitServices services = Get.find();

  @override
  void onInit() {
    accountType = "";
    idOrEmail = TextEditingController();
    password = TextEditingController();

    super.onInit();
  }

  @override
  void goToCreateAccount() {
    Get.offNamed(AppRoutes.createAccount);
  }

  @override
  void goToForgotpassword() {
    Get.offNamed(AppRoutes.forgotPassword);
  }

  @override
  void onPasswordVisibleChange() {
    isVisiblePassword = isVisiblePassword == true ? false : true;
    update();
  }

  @override
  void onAccountTypeChange(String value) {
    accountType = value;
  }

  @override
  void onLogin(context) async {
    if (loginFormKey.currentState!.validate()) {
      // When Loading
      requestStatus = RequestStatus.loading;
      update();

      Map body = {
        "role": accountType,
        "id": idOrEmail.text,
        "password": password.text
      };
      var response =
          await loginData.postData(body["role"], body["id"], body["password"]);

      requestStatus = checkResponseStatus(response);
      if (requestStatus == RequestStatus.success) {
        int? isActive = int.parse(await response["Data"]["isActive"]);
        // Get.offAll(const Home());

        if (isActive == 1) {
          Map data = response["Data"];

          LocalUser user = LocalUser(
              expiredToken: data["expiredToken"],
              image: data["image"],
              name: data["name"],
              ssd: data["ssd"],
              token: data["token"],
              type: data["type"]);

          // Save data to local storage
          services.sharedPreferences.setString("user", user.encodeUser);

          snackbar();

          Get.offAllNamed(AppRoutes.home);
        } else if (isActive == 0) {
          successDialog(context,
              buttonText: "تفعيل", content: response["Message"], onSuccess: () {
            // Close dialog
            Get.back();
            Get.offAndToNamed(AppRoutes.verifyEmailCode, arguments: {
              "role": accountType,
              "email": idOrEmail.text,
              "password": password.text
            });
          });
        }
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
    idOrEmail.dispose();
    password.dispose();
    super.onClose();
  }
}
