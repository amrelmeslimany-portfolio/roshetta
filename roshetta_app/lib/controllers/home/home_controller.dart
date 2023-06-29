import 'package:get/get.dart';
import 'package:jwt_decoder/jwt_decoder.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/data/source/remote/home/home_data.dart';

class HomeController extends GetxController {}

class HomeControllerImp extends HomeController {
  late String explainVideoURL = "";
  late AuthenticationController auth = Get.find<AuthenticationController>();

  String? assistantId;

  String? errorText;

  HomeData homeRequests = HomeData(Get.find<Crud>());

  RequestStatus videoStatus = RequestStatus.none;

  @override
  void onInit() async {
    assistantId = JwtDecoder.decode(auth.localUser.value?.token ?? "")["id"];
    await getExplainVideo();
    update();
    super.onInit();
  }

  getExplainVideo() async {
    videoStatus = RequestStatus.loading;
    update();

    var response = await homeRequests.getExplainVideo(getToken(auth)!);
    videoStatus = checkResponseStatus(response);

    if (videoStatus == RequestStatus.success) {
      if (response["Data"] != null) {
        explainVideoURL = response["Data"];
      } else {
        videoStatus = RequestStatus.empty;
      }

      update();
    } else if (videoStatus == RequestStatus.userFailure) {
      errorText = response["Message"];
      logoutError401(response["Status"], auth, 4);
    } else {
      update();
    }
  }

  goToVerifiedAccount() {
    Get.toNamed(AppRoutes.verifyDPAccount);
  }
}
