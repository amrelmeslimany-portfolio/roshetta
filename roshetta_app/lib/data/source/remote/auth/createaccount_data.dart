import 'package:get/get.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';
import 'package:roshetta_app/data/models/user.model.dart';

class CreateAccountData {
  Crud crud;
  CreateAccountData(this.crud);

  // Curd Methods
  postData(User data) async {
    FormData body = FormData(data.toJson());

    var response = await crud.baseCrud(ApiUrls.register, "post", body: body);

    return response.fold((l) => l, (r) => r);
  }

  getSpecialists() async {
    var response = await crud.baseCrud(ApiUrls.getDoctorSpecialists, "get");

    return response.fold((l) => l, (r) => r);
  }
}
