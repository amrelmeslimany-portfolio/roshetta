import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/core/class/crud.dart';

class ReadPrescriptData {
  Crud crud;
  ReadPrescriptData(this.crud);
  String? pythonAPI = "http://10.0.2.2:5000";

  postProfileImage(XFile img) async {
    FormData? body = FormData({
      "image": MultipartFile(img.path, filename: img.name),
    });

    var response = await crud.baseCrud("/extract_text", "post",
        baseAPI: pythonAPI, body: body);

    return response.fold((l) => l, (r) => r);
  }
}
