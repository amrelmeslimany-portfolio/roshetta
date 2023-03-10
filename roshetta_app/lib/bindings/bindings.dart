import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/createaccount_controller.dart';
import 'package:roshetta_app/controllers/auth/forgotpassword_controller.dart';
import 'package:roshetta_app/controllers/auth/login_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';

class InitBinding extends Bindings {
  @override
  void dependencies() {
    // Global Binding Controllers
    Get.put<Crud>(Crud());

    // Auth
    Get.lazyPut<LoginControllerImp>(() => LoginControllerImp(), fenix: true);

    Get.lazyPut<ForgotPasswordControllerImp>(
        () => ForgotPasswordControllerImp(),
        fenix: true);

    Get.lazyPut<CreateAccControllerImp>(() => CreateAccControllerImp(),
        fenix: true);
  }
}
