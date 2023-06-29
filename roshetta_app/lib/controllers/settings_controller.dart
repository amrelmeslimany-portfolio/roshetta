import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';

class SettingsController extends GetxController {
  final isPrescripsSave = false.obs;

  set presecriptSave(bool value) => isPrescripsSave.value = value;
  goToChangeServerApi() => Get.toNamed(AppRoutes.tempAPIChanger);
}
