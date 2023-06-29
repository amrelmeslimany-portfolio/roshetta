import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

class InitServices extends GetxService {
  late SharedPreferences sharedPreferences;

  Future<InitServices> init() async {
    sharedPreferences = await SharedPreferences.getInstance();
    await dotenv.load();
    return this;
  }
}

initServices() async {
  await Get.putAsync(() => InitServices().init());
}
