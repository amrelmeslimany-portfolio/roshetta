import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';

class HomeData {
  Crud crud;
  HomeData(this.crud);
  getExplainVideo(String token) async {
    Map<String, String> headers = {'Authorization': 'Bearer $token'};

    var response =
        await crud.baseCrud(ApiUrls.explainVideo, "get", headers: headers);

    return response.fold((l) => l, (r) => r);
  }
}
