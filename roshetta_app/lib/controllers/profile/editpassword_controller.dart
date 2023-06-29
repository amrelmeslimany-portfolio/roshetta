import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/source/remote/profiles/editprofile_data.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';

abstract class EditPasswordController extends GetxController {
  void onPasswordVisibleChange();
  bool? checkPasswordEquals();
  void onSubmit();
}

class EditPasswordControllerImp extends EditPasswordController {
  GlobalKey<FormState> form = GlobalKey<FormState>();
  TextEditingController password = TextEditingController();
  TextEditingController repassword = TextEditingController();
  bool isVisiblePassword = true;
  AuthenticationController auth = Get.find<AuthenticationController>();
  RequestStatus status = RequestStatus.none;
  EditProfileData requests = EditProfileData(Get.find<Crud>());

  @override
  void onPasswordVisibleChange() {
    isVisiblePassword = isVisiblePassword ? false : true;
    update();
  }

  @override
  bool? checkPasswordEquals() {
    if (password.text == repassword.text) {
      return true;
    } else {
      return false;
    }
  }

  @override
  void onSubmit() async {
    if (form.currentState!.validate()) {
      form.currentState!.save();
      status = RequestStatus.loading;
      update();
      var response = await requests.postRenewPassword(
          getToken(auth)!, password.text, repassword.text);
      print(response);
      status = checkResponseStatus(response);
      if (status == RequestStatus.success) {
        Get.back(closeOverlays: true);
        snackbar(title: "تم التغيير", content: response["Message"]);
      } else if (status == RequestStatus.userFailure) {
        DialogRequestMessages(Get.context!,
            status: status, failureText: response["Message"]);
      } else {
        DialogRequestMessages(Get.context!, status: status);
      }
      update();
    }
  }

  @override
  void onClose() {
    password.dispose();
    repassword.dispose();
    super.onClose();
  }
}
