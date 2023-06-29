import 'dart:async';
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
import 'package:roshetta_app/data/source/remote/doctor_pharmacist/verifyaccounts_data.dart';
import 'package:shared_preferences/shared_preferences.dart';

class AuthenticationController extends GetxController {
  InitServices services = Get.find<InitServices>();
  LoginData requests = LoginData(Get.put(Crud()));
  VerifyAccountsData verifyRequests = VerifyAccountsData(Get.put(Crud()));
  SharedPreferences get getStorage => services.sharedPreferences;
  Rx<LocalUser?> localUser = LocalUser().obs;
  RxBool isAuth = false.obs;

  String? stringUser() => getStorage.getString("user");

  @override
  void onInit() async {
    super.onInit();
    getUser();
    getIsAuth();
  }

  getUser() {
    if (stringUser() == null || stringUser()!.isEmpty) {
      return;
    }
    localUser.value = LocalUser.fromJson(json.decode(stringUser()!));
  }

  getIsAuth() {
    if (localUser.value == null) {
      isAuth.value = false;
    } else if (localUser.value?.token == null ||
        localUser.value!.token!.isEmpty ||
        JwtDecoder.isExpired(localUser.value!.token!)) {
      removeUser();
      isAuth.value = false;
    } else {
      isAuth.value = true;
    }
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
        isVerify: isVerify ?? localUser.value!.isVerify ?? "none",
        image: img ?? localUser.value!.image,
        name: name ?? localUser.value!.name,
        ssd: ssd ?? localUser.value!.ssd,
        token: token ?? localUser.value!.token,
        type: type ?? localUser.value!.type);

    getStorage.setString("user", user.encodeUser);
    localUser.value = user;

    return user;
  }

  logout() async {
    // NOTE Related to temporary page
    isAuth.value = false;
    Get.offAllNamed(AppRoutes.intro);
    var response = await requests.logout(localUser.value!.token!);
    removeUser();
    if (checkResponseStatus(response) == RequestStatus.success) {
      snackbar(title: "تم تسجيل الخروج", content: "تم تسجيل الخروج بنجاح");
    } else {
      print(response);
    }
  }

  getVerifyStatus() {
    if (localUser.value!.isVerify != null &&
        localUser.value!.isVerify == "waiting") {
      Timer.periodic(const Duration(seconds: 5), (timer) async {
        var response =
            await verifyRequests.getVerifyStatus(localUser.value!.token!);

        if (checkResponseStatus(response) == RequestStatus.success) {
          print(response["Data"]);
          if (response["Data"] != "waiting") {
            timer.cancel();
            updateLocalUser(isVerify: response["Data"]);
            if (response["Data"] == "success") {
              snackbar(
                  title: "تم التوثيق",
                  content: "تم توثيق حسابك بنجاح يمكنك استخدام المميزات الأن");
            }
          }
        }
      });
    }
  }
}
