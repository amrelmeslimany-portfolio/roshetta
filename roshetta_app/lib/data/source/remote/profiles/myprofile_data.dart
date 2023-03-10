import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class MyProfileData {
  Crud crud;
  MyProfileData(this.crud);

  getProfileData(String token) async {
    Map<String, String> headers = {'Authorization': 'Bearer $token'};

    var response =
        await crud.baseCrud(ApiUrls.myprofile, "get", headers: headers);

    return response.fold((l) => l, (r) => r);
  }
}
