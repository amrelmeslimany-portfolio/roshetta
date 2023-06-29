import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/controllers/doctor/clinics_controller.dart';
import 'package:roshetta_app/controllers/pharmacist/pharmacys_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/data/source/remote/doctor/clinic_data.dart';

class VerifyPlaceController extends GetxController {
  XFile? file;
  final RxBool isError = false.obs;
  Rx<RequestStatus> status = RequestStatus.none.obs;
  AuthenticationController auth = Get.find<AuthenticationController>();
  ClinicData clinicRequests = ClinicData(Get.find<Crud>());

  onUploadPlaceImg() {
    pickImg(ImageSource source) async {
      file = await ImagePicker().pickImage(source: source);
      if (file == null) return;
      Get.back();
      isError.value = false;
      isError.refresh();
    }

    CustomBottomSheets.uploadImages(
        onCamera: () async {
          await pickImg(ImageSource.camera);
        },
        onGellary: () async {
          await pickImg(ImageSource.gallery);
        },
        title: "قم بإختيار صورة ترخيص العياده");
  }

  onDeleteImg() {
    file = null;
    isError.refresh();
  }

  onSubmit(id, {String? type = "clinic"}) async {
    if (file == null) {
      isError.value = true;
    } else {
      status.value = RequestStatus.loading;
      var response = await clinicRequests
          .verifyClinic(getToken(auth)!, file!, id, placeType: type);
      status.value = checkResponseStatus(response);
      print(response);
      if (status.value == RequestStatus.success) {
        file = null;
        Get.back();
        updateClinicsStatus(id, getPlaceTypeList(type!));
        snackbar(content: response["Message"], title: "تتم المراجعه");
      } else {
        snackbar(
            content: response["Message"], title: "حدثت  مشكله", isError: true);
      }
    }
  }

  RxList getPlaceTypeList(String placeType) {
    if (placeType == "clinic") {
      return Get.put(DoctorClinicsController()).clinics;
    } else {
      return Get.put(PharmacistPharmacyController()).pharmacys;
    }
  }

  updateClinicsStatus(String id, RxList placeList) {
    var index = placeList.indexWhere((element) => element["id"] == id);
    placeList.elementAt(index)["isVerify"] = "waiting";
    placeList.refresh();
  }

  @override
  void onClose() {
    file = null;
    super.onClose();
  }
}
