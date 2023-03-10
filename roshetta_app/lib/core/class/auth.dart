import 'dart:convert';
import 'package:get/get.dart';
import 'package:jwt_decoder/jwt_decoder.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/services/init_services.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:shared_preferences/shared_preferences.dart';

class Authentication {
  InitServices services = Get.find<InitServices>();

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

  void logout() {
    removeUser();
    Get.offAllNamed(AppRoutes.intro);
  }
}
