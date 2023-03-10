import 'package:get/get.dart';
import 'package:roshetta_app/core/class/auth.dart';
import 'package:roshetta_app/data/models/user.model.dart';

abstract class DrawerController extends GetxController {
  late LocalUser user;
}

class DrawerControllerImp extends DrawerController {
  @override
  void onInit() {
    user = Authentication().getUser!;
    super.onInit();
  }
}
