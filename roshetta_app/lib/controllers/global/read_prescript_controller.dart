import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/data/source/remote/read_prescript_data.dart';

class ReadPrescriptController extends GetxController {
  XFile? imgFile;
  RxString extracted = "".obs;
  Rx<RequestStatus> imageStatus = RequestStatus.empty.obs;
  ReadPrescriptData requests = ReadPrescriptData(Get.find<Crud>());

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
    imageStatus.value = RequestStatus.loading;
    if (Get.isBottomSheetOpen == true) Get.back();
    var response = await requests.postProfileImage(imgFile!);

    if (isNotStatus(response) && response["status"] == "success") {
      snackbar(title: "تم الاستخراج", content: response["message"]);
      setText = response["data"];
      if (extracted.value.trim().isEmpty) {
        imageStatus.value = RequestStatus.empty;
      } else {
        imageStatus.value = RequestStatus.success;
      }
    } else {
      imageStatus.value = RequestStatus.failure;
      setText = "";
      snackbar(
          isError: true,
          title: "حدثت مشكلة",
          content:
              isNotStatus(response) ? response["message"] : "هناك مشكله ما");
    }
  }

  set setText(String value) => extracted.value = value;
  bool get isImage => imgFile == null ? false : true;
  bool get isError => imageStatus.value != RequestStatus.success &&
          imageStatus.value != RequestStatus.empty
      ? true
      : false;
  bool isNotStatus(response) => response is! RequestStatus ? true : false;
}
