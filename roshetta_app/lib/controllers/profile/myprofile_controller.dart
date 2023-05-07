import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/data/source/remote/profiles/myprofile_data.dart';

import '../../core/functions/quick_functions.dart';

class MyProfileController extends GetxController {
  final information = User().obs;
  late RxString error = "حدثت مشكله".obs;

  AuthenticationController auth = Get.find<AuthenticationController>();

  Rx<RequestStatus> profileStatus = RequestStatus.none.obs;

  MyProfileData requests = MyProfileData(Get.find<Crud>());

  @override
  void onInit() async {
    await getProfileData();

    super.onInit();
  }

  getProfileData() async {
    profileStatus.value = RequestStatus.loading;

    var response = await requests.getProfileData(getToken(auth)!);

    profileStatus.value = checkResponseStatus(response);

    if (profileStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        profileStatus.value = RequestStatus.userFailure;
        return;
      }
      information.value = User.fromJson(response["Data"]);
      print(response);
    } else if (profileStatus.value == RequestStatus.userFailure) {
      logoutError401(response["Status"], auth, 4);
      error.value = "يرجي تسجيل الدخول مرة اخري";
    }
  }

  void goToEditProfile() {
    Get.toNamed(AppRoutes.editProfile);
  }

  updateInformation({
    String? phone,
    String? governorate,
    String? height,
    String? weight,
  }) {
    information.value.phoneNumber = phone;
    information.value.governorate = governorate;
    information.value.height = height;
    information.value.weight = weight;
    information.refresh();
  }
}
