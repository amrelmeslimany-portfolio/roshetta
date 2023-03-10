import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/data/source/remote/auth/createaccount_data.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/auth/auth_dialogs.dart';
import 'package:roshetta_app/view/widgets/custom_request.dart';

import '../../core/functions/reused_functions.dart';

abstract class CreateAccController extends GetxController {
  // Main
  void onPasswordVisibleChange();
  void onSubmit(context);
  // Checks
  bool? checkPasswordEquals();
  // Routes
  void goToLogin();
  // Dropdowns
  void onAccountTypeChange(String value);
  void onSpcialistsChange(String value);
  void onGovernmentChange(String value);
  void onGenderChange(String value);
  // Widgets
  void onShowDatePicker(BuildContext context);
}

class CreateAccControllerImp extends CreateAccController {
  late TextEditingController firstName;
  late TextEditingController lastName;
  late TextEditingController email;
  late TextEditingController ssd;
  late TextEditingController phone;
  late TextEditingController birthDate;
  late String government;
  late String gender;
  late String accountType;
  late String doctorSpecialist;
  late TextEditingController patientWeight;
  late TextEditingController patientHeight;
  late TextEditingController password;
  late TextEditingController rePassword;

  List<DropdownMenuItem<String>> governmentsList = [];
  List<DropdownMenuItem<String>> specialistsList = [];
  bool isVisiblePassword = true;

  GlobalKey<FormState> createAccountForm = GlobalKey<FormState>();

  RequestStatus requestStatus = RequestStatus.none;
  RequestStatus requestStatusSpecialists = RequestStatus.none;

  CreateAccountData createaccData = CreateAccountData(Get.find<Crud>());

  @override
  void onInit() async {
    government = "";
    accountType = "";
    gender = "male";
    doctorSpecialist = "";

    firstName = TextEditingController();
    lastName = TextEditingController();
    email = TextEditingController();
    ssd = TextEditingController();
    phone = TextEditingController();
    birthDate = TextEditingController();
    patientWeight = TextEditingController();
    patientHeight = TextEditingController();
    password = TextEditingController();
    rePassword = TextEditingController();

    governmentsList = await StaticData.getGoverments();
    update();

    super.onInit();
  }

  getSpecilists() async {
    requestStatusSpecialists = RequestStatus.loading;
    update();

    var response = await createaccData.getSpecialists();
    requestStatusSpecialists = checkResponseStatus(response);

    if (requestStatusSpecialists == RequestStatus.success) {
      List data = response["Data"];
      specialistsList = data
          .map((goverment) => DropdownMenuItem(
              value: goverment["ar_name"].toString(),
              child: Text(goverment["ar_name"].toString())))
          .toList();
    } else {
      snackbar(title: "حدثت مشكلة", content: "لا يمك عرض التخصصات الطبيه الان");

      specialistsList = [
        const DropdownMenuItem(
            value: "error",
            child: Text(
              "حدثت مشكلة في اظهار التخصصات",
              textAlign: TextAlign.center,
            ))
      ];
    }
  }

  @override
  void onShowDatePicker(BuildContext context) async {
    DateTime? date = await customDatePicker(context);
    birthDate.text = date != null ? DateFormat("dd-MM-yyyy").format(date) : "";
  }

  @override
  bool? checkPasswordEquals() {
    if (password.text != rePassword.text) {
      return false;
    }
    return null;
  }

  @override
  void goToLogin() {
    Get.offNamed(AppRoutes.login);
  }

  @override
  void onPasswordVisibleChange() {
    isVisiblePassword = isVisiblePassword == true ? false : true;
    update();
  }

  @override
  void onGenderChange(String value) {
    gender = value;
    update();
  }

  @override
  void onAccountTypeChange(String value) async {
    accountType = value;

    if (value == Users.doctor.name && specialistsList.isEmpty) {
      await getSpecilists();
    }

    update();
  }

  @override
  void onGovernmentChange(String value) {
    government = value;
  }

  @override
  void onSpcialistsChange(String value) {
    doctorSpecialist = value;
  }

  @override
  void onSubmit(context) async {
    if (createAccountForm.currentState!.validate()) {
      // When Loading
      requestStatus = RequestStatus.loading;
      update();

      User data = User(
        firstName: firstName.text,
        lastName: lastName.text,
        birthDate: birthDate.text,
        email: email.text,
        gender: gender,
        governorate: government,
        phoneNumber: phone.text,
        role: accountType,
        specialist: "عيون",
        ssd: ssd.text,
        password: password.text,
        confirmPassword: rePassword.text,
        height: patientHeight.text != "" ? patientHeight.text : "0",
        weight: patientHeight.text != "" ? patientWeight.text : "0",
      );
      var response = await createaccData.postData(data);
      requestStatus = checkResponseStatus(response);

      if (requestStatus == RequestStatus.success) {
        Map arguments = {"role": accountType, "email": email.text};
        successDialog(context,
            title: "تم الانشاء",
            content:
                "تم انشاء مستخدم جديد وارسال كود الي الايميل لتفعيل الايميل.",
            buttonText: "تفعيل", onSuccess: () {
          Get.toNamed(AppRoutes.verifyEmailCode, arguments: arguments);
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
    firstName.dispose();
    lastName.dispose();
    email.dispose();
    ssd.dispose();
    phone.dispose();
    birthDate.dispose();
    patientWeight.dispose();
    patientHeight.dispose();
    password.dispose();
    rePassword.dispose();
    super.onClose();
  }
}
