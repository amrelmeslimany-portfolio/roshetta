import 'dart:convert';
import 'package:get/get.dart';
import 'package:jwt_decoder/jwt_decoder.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/services/init_services.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/data/source/remote/auth/login_data.dart';
import 'package:shared_preferences/shared_preferences.dart';

class Authentication {
  InitServices services = Get.find<InitServices>();
  LoginData requests = LoginData(Get.find<Crud>());

  SharedPreferences get getStorage => services.sharedPreferences;

  String? stringUser() => getStorage.getString("user");

  LocalUser? get getUser {
    if (stringUser() == null || stringUser()!.isEmpty) {
      return null;
    }
    return LocalUser.fromJson(json.decode(stringUser()!));
  }

  bool get isAuth {
    if (getUser == null) return false;

    if (getUser!.token == null ||
        getUser!.token!.isEmpty ||
        JwtDecoder.isExpired(getUser!.token!)) {
      removeUser();
      return false;
    }

    return true;
  }

  void removeUser() {
    getStorage.remove("user");
  }

  LocalUser updateLocalUser(
      {String? img,
      String? name,
      String? ssd,
      String? token,
      String? type,
      String? isVerify}) {
    LocalUser user = LocalUser(
        isVerify: isVerify ?? getUser!.isVerify ?? "none",
        image: img ?? getUser!.image,
        name: name ?? getUser!.name,
        ssd: ssd ?? getUser!.ssd,
        token: token ?? getUser!.token,
        type: type ?? getUser!.type);

    getStorage.setString("user", user.encodeUser);

    return user;
  }

  logout() async {
    Get.offAllNamed(AppRoutes.intro);
    var response = await requests.logout(getUser!.token!);
    removeUser();
    if (checkResponseStatus(response) == RequestStatus.success) {
      snackbar(title: "تم تسجيل الخروج", content: "تم تسجيل الخروج بنجاح");
      print(response);
    } else {
      print(response);
    }
  }
}
