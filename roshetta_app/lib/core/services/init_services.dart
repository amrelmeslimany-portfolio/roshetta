import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart';

class InitServices extends GetxService {
  late SharedPreferences sharedPreferences;

  Future<InitServices> init() async {
    sharedPreferences = await SharedPreferences.getInstance();
    return this;
  }
}

initServices() async {
  await Get.putAsync(() => InitServices().init());
}
