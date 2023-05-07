import 'dart:io';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/data/source/remote/doctor_pharmacist/verifyaccounts_data.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';

class VerifyAccountsController extends GetxController {
  final RxList imagesList = <Map<String, dynamic>>[].obs;
  final RxBool isError = false.obs;
  final Rx<RequestStatus> uploadStatus = RequestStatus.none.obs;
  final VerifyAccountsData requests = VerifyAccountsData(Get.find<Crud>());
  final auth = Get.find<AuthenticationController>();

  @override
  onInit() {
    imagesList.addAll(StaticData.verifyImages);
    super.onInit();
  }

  onOpenUploaderSheet(Map item) {
    int index = imagesList.indexOf(item);

    pickImg(ImageSource source) async {
      XFile? file = await ImagePicker().pickImage(source: source);
      if (file == null) return;
      imagesList[index]["file"] = file;
      imagesList.refresh();
      Get.back();
    }

    CustomBottomSheets.uploadImages(
      onCamera: () async {
        await pickImg(ImageSource.camera);
      },
      onGellary: () async {
        await pickImg(ImageSource.gallery);
      },
    );
  }

  Map onCheckImg(Map item) {
    int index = imagesList.indexOf(item);
    XFile? file = imagesList[index]["file"];
    return {
      "isImg": file,
      "path": file != null ? File(file.path) : false,
      "name": file != null ? file.name : "قم برفع صورة"
    };
  }

  onDeleteImage(Map item) {
    item["file"] = null;
    imagesList.refresh();
  }

  bool isCurrentError(element) {
    if (onCheckImg(element)["isImg"] == null && isError.value) {
      return true;
    }

    return false;
  }

  onSubmit() async {
    isError.value = imagesList.any((element) => element["file"] == null);

    if (!isError.value) {
      uploadStatus.value = RequestStatus.loading;
      var response = await requests.postVerifiedImage(getToken(auth)!,
          frontNational: imagesList[0]["file"],
          backNational: imagesList[1]["file"],
          ceritificate: imagesList[2]["file"],
          cardId: imagesList[3]["file"]);
      uploadStatus.value = checkResponseStatus(response);
      print(response);
      if (uploadStatus.value == RequestStatus.success) {
        snackbar(title: "تم الارسال", content: response["Message"]);
        // Clear Form
        for (var element in imagesList) {
          element["file"] = null;
        }

        // Change Verified Status
        auth.updateLocalUser(isVerify: "waiting");
        auth.getVerifyStatus();
      } else {
        snackbar(title: "حدثت مشكلة", content: response["Message"]);
        print({"Error": response});
      }
    }
  }
}
