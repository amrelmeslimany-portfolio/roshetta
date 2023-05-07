import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';

abstract class DrawerController extends GetxController {
  late LocalUser user;
}

class DrawerControllerImp extends DrawerController {
  final auth = Get.find<AuthenticationController>();
  final List linkList = [];

  @override
  void onInit() {
    user = auth.localUser.value!;
    linkList.addAll(StaticData.drawerLinks[user.type]);
    update();
    super.onInit();
  }
}
