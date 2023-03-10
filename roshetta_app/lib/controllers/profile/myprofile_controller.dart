import 'package:dartz/dartz.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/class/auth.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/data/source/remote/profiles/myprofile_data.dart';

class MyProfileController extends GetxController {}

class MyProfileControllerImp extends MyProfileController {
  late User? information = User();
  late String? error = "حدثت مشكله";

  Authentication auth = Authentication();
  LocalUser? user = LocalUser();

  RequestStatus profileStatus = RequestStatus.none;

  MyProfileData requests = MyProfileData(Get.find<Crud>());

  @override
  void onInit() async {
    user = auth.getUser!;

    await getProfileData();

    super.onInit();
  }

  getProfileData() async {
    profileStatus = RequestStatus.loading;
    update();

    var response = await requests.getProfileData(user!.token!);

    profileStatus = checkResponseStatus(response);

    if (profileStatus == RequestStatus.success) {
      information = User.fromJson(response["Data"]);
      print(response);
    } else if (profileStatus == RequestStatus.userFailure) {
      logoutError401(response["Status"], auth, 4);
      error = "يرجي تسجيل الدخول مرة اخري";
    }

    update();
  }
}
