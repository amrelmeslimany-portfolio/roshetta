import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/controllers/profile/myprofile_controller.dart';
import 'package:roshetta_app/core/class/auth.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';

import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/data/source/remote/profiles/editprofile_data.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';

abstract class EditProfileController extends GetxController {
  Future<void> onEdit(context);
  Future<void> onUploadImage();
  void goToEditPassword();
}

class EditProfileControllerImp extends EditProfileController {
  GlobalKey<FormState> formkey = GlobalKey<FormState>();
  late TextEditingController phone = TextEditingController();
  late TextEditingController weight = TextEditingController();
  late TextEditingController height = TextEditingController();
  late String governorate = "";
  late String picture = AssetPaths.emptyPerson;
  late XFile? imgFile;

  MyProfileController profile = Get.put(MyProfileController());

  User? user;

  EditProfileData requests = EditProfileData(Get.find<Crud>());

  AuthenticationController auth = Get.find<AuthenticationController>();

  List<DropdownMenuItem<String>> governmentsList = [];
  RequestStatus pageStatus = RequestStatus.none;
  RequestStatus userStatus = RequestStatus.none;
  String? error = "";

  @override
  void onInit() async {
    governmentsList = await StaticData.getGoverments();
    update();
    hanldeInitData();
    super.onInit();
  }

  hanldeInitData() {
    user = profile.information.value;
    picture = user?.image ?? AssetPaths.emptyPerson;
    phone.text = user!.phoneNumber!;
    governorate = user!.governorate!;

    if (user!.role == Users.patient.name) {
      weight.text = user!.weight!;
      height.text = user!.height!;
    }
  }

  @override
  goToEditPassword() {
    Get.toNamed(AppRoutes.editPassword);
  }

  onGovernmentChange(String value) {
    governorate = value;
  }

  @override
  Future<void> onUploadImage() async {
    CustomBottomSheets.uploadImages(
      onCamera: () async {
        await handleClickImageUpload(ImageSource.camera);
      },
      onGellary: () async {
        await handleClickImageUpload(ImageSource.gallery);
      },
    );
  }

  handleClickImageUpload(ImageSource source) async {
    imgFile = await ImagePicker().pickImage(source: source);
    if (imgFile == null) return;
    userStatus = RequestStatus.loading;
    update();
    var response = await requests.postProfileImage(getToken(auth)!, imgFile!);
    print(response);
    userStatus = checkResponseStatus(response);
    Get.back();
    if (userStatus == RequestStatus.success) {
      auth.updateLocalUser(img: response["Data"]);
      profile.information.value.image = response["Data"];
      picture = response["Data"];
      snackbar(title: "تم التحديث", content: "تم تحديث صورة البروفايل بنجاح");
    }
    update();
  }

  @override
  Future<void> onEdit(context) async {
    if (formkey.currentState!.validate()) {
      formkey.currentState!.save();
      userStatus = RequestStatus.loading;
      update();

      var response = await requests.postProfileEdit(
          getToken(auth)!, phone.text, governorate, weight.text, height.text);

      userStatus = checkResponseStatus(response);
      print(response);
      if (userStatus == RequestStatus.success) {
        profile.updateInformation(
            governorate: governorate,
            height: height.text,
            weight: weight.text,
            phone: phone.text);

        snackbar(
            content: "تم تعديل بياناتك الشخصية بنجاح", title: "تم التعديل");
      } else if (userStatus == RequestStatus.userFailure) {
        DialogRequestMessages(context,
            status: userStatus, failureText: response["Message"]);
        logoutError401(response["Status"], auth, 5);
      } else {
        DialogRequestMessages(context, status: userStatus);
      }
      update();
    }
  }

  @override
  void onClose() {
    phone.dispose();
    super.onClose();
  }
}
