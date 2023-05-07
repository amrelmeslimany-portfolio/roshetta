import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/controllers/pharmacist/pharmacys_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/date_functions.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/data/models/pharmacy.modal.dart';
import 'package:roshetta_app/data/source/remote/pharmacist/pharmacy_data.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:time_range_picker/time_range_picker.dart';

class AddPharmacyController extends GetxController {
  GlobalKey<FormState> formkey = GlobalKey<FormState>();
  final auth = Get.find<AuthenticationController>();
  late RxString imgSrc = AssetPaths.emptyIMG.obs;
  late Rx<XFile?> imageFile = Rx<XFile?>(null);
  late TextEditingController name = TextEditingController();
  late TextEditingController phone = TextEditingController();
  late TextEditingController location = TextEditingController();
  late TextEditingController rangeTimeController = TextEditingController();
  TimeRange? selectedTime;
  late RxString governorate = "".obs;
  final governorateList = <DropdownMenuItem<String>>[].obs;
  late Rx<RequestStatus> addPharmacyStatus = RequestStatus.none.obs;
  PharmacyData pharmacyRequests = PharmacyData(Get.find<Crud>());
  final RxBool isEdit = false.obs;
  final RxString pharmacyId = "".obs;
  late PharmacistPharmacyController? pharmacysController;

  @override
  onInit() async {
    governorateList.addAll((await StaticData.getGoverments()).toList());
    checkEditOrAdd();
    super.onInit();
  }

  onSelectStartTime() async {
    TimeRange? timeRange = await customTimeRangePicker(
        start: selectedTime?.startTime, end: selectedTime?.endTime);
    if (timeRange != null) {
      selectedTime = timeRange;
      rangeTimeController.text = setTimeToField(selectedTime);
    }
  }

  onGovernmentChange(String value) {
    governorate.value = value;
  }

  onSubmit() async {
    if (formkey.currentState!.validate()) {
      formkey.currentState!.save();
      addPharmacyStatus.value = RequestStatus.loading;
      PharmacyModal pharmacy = PharmacyModal(
          name: name.text,
          phoneNumber: phone.text,
          startWorking: selectedTime!.startTime.format(Get.context!),
          endWorking: selectedTime!.endTime.format(Get.context!),
          governorate: governorate.value,
          address: location.text);
      var response = await pharmacyRequests.postData(getToken(auth)!, pharmacy);
      addPharmacyStatus.value = checkResponseStatus(response);
      print(response);
      if (addPharmacyStatus.value == RequestStatus.success) {
        snackbar(title: "تم الاضافة", content: "تم اضافة الصيدلية بنجاح");
        Get.offNamed(AppRoutes.pharmacistPharmacys);
      } else if (addPharmacyStatus.value == RequestStatus.userFailure) {
        DialogRequestMessages(Get.context!,
            status: addPharmacyStatus.value, failureText: response["Message"]);
      } else {
        DialogRequestMessages(Get.context!, status: addPharmacyStatus.value);
      }
    }
  }

  // When Edit

  checkEditOrAdd() {
    if (Get.arguments != null &&
        Get.arguments["isEdit"] != null &&
        Get.arguments["isEdit"] == true) {
      isEdit.value = true;
      pharmacysController = Get.find<PharmacistPharmacyController>();
      if (pharmacysController?.pharmacy.value != null) {
        PharmacyModal? pharmacy = pharmacysController!.pharmacy.value;
        pharmacyId.value = pharmacy!.id!;
        imgSrc.value = pharmacy.logo ?? AssetPaths.emptyIMG;
        phone.text = pharmacy.phoneNumber!;
        governorate.value = pharmacy.governorate!;
        location.text = pharmacy.address!;

        selectedTime = TimeRange(
            startTime: stringToTimeDay(pharmacy.startWorking!),
            endTime: stringToTimeDay(pharmacy.endWorking!));
        rangeTimeController.text = setTimeToField(selectedTime);
      }
    } else {
      isEdit.value = false;
    }
  }

  onPickImage() {
    pickImg(ImageSource source) async {
      imageFile.value = await ImagePicker().pickImage(source: source);
      if (imageFile.value == null) return;
      Get.back();
      if (pharmacyId.value.isNotEmpty && isEdit.value) {
        addPharmacyStatus.value = RequestStatus.loading;

        var response = await pharmacyRequests.updateLogo(getToken(auth)!,
            imageFile.value!, pharmacyId.value, getCookie(auth)!);
        addPharmacyStatus.value = checkResponseStatus(response);
        print(response);
        if (addPharmacyStatus.value == RequestStatus.success) {
          if (response["Data"] != null) {
            imgSrc.value = response["Data"];
            snackbar(title: "تم تغيير الصورة", content: response["Message"]);
            // Pharmacys list update
            pharmacysController!.updatePharmacysList(
              pharmacyId.value,
              img: imgSrc.value,
            );
            // pharmacy Details  update
            pharmacysController!.updatePharmacyDetails(logo: imgSrc.value);
          } else {
            addPharmacyStatus.value = RequestStatus.failure;
          }
        } else if (addPharmacyStatus.value == RequestStatus.userFailure) {
          DialogRequestMessages(Get.context!,
              status: addPharmacyStatus.value,
              failureText: response["Message"]);
        } else {
          DialogRequestMessages(Get.context!, status: addPharmacyStatus.value);
        }
      }
    }

    CustomBottomSheets.uploadImages(onCamera: () async {
      await pickImg(ImageSource.camera);
    }, onGellary: () async {
      await pickImg(ImageSource.gallery);
    });
  }

  onEditSubmit() async {
    if (formkey.currentState!.validate() &&
        pharmacysController?.pharmacy.value != null) {
      addPharmacyStatus.value = RequestStatus.loading;
      var response = await pharmacyRequests.editPharmacy(
          getToken(auth)!, pharmacyId.value, getCookie(auth)!,
          phone: phone.text,
          startTime: selectedTime!.startTime.format(Get.context!),
          endTime: selectedTime!.endTime.format(Get.context!),
          governorate: governorate.value,
          address: location.text);

      addPharmacyStatus.value = checkResponseStatus(response);

      if (addPharmacyStatus.value == RequestStatus.success) {
        // Update the clincs list
        pharmacysController!.updatePharmacysList(
          pharmacyId.value,
          start: rangeToHMSFormat(selectedTime!.startTime),
          end: rangeToHMSFormat(selectedTime!.endTime),
        );
        // Update Pharmacy Detials
        pharmacysController!.updatePharmacyDetails(
            phone: phone.text,
            start: rangeToHMSFormat(selectedTime!.startTime),
            end: rangeToHMSFormat(selectedTime!.endTime),
            governorate: governorate.value,
            location: location.text);

        Get.back(closeOverlays: false);
        snackbar(title: "تم التعديل", content: response["Message"]);
      } else {
        DialogRequestMessages(Get.context!,
            status: addPharmacyStatus.value, failureText: response["Message"]);
      }
    }
  }

  // Related to controller
  @override
  void onClose() {
    name.dispose();
    phone.dispose();
    rangeTimeController.dispose();
    location.dispose();
    super.onClose();
  }
}
