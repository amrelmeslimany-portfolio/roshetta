import 'package:get/get.dart';
import 'package:roshetta_app/core/class/auth.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/data/source/remote/home/home_data.dart';

class HomeController extends GetxController {}

class HomeControllerImp extends HomeController {
  late String explainVideoURL = "";
  late Authentication auth;

  String? errorText;

  HomeData homeRequests = HomeData(Get.find<Crud>());

  RequestStatus videoStatus = RequestStatus.none;

  @override
  void onInit() async {
    auth = Authentication();

    await getExplainVideo();
    update();
    super.onInit();
  }

  getExplainVideo() async {
    videoStatus = RequestStatus.loading;
    update();

    var response = await homeRequests.getExplainVideo(auth.getUser!.token!);
    videoStatus = checkResponseStatus(response);

    if (videoStatus == RequestStatus.success) {
      explainVideoURL = response["Data"];
      print(response["Data"]);

      update();
    } else if (videoStatus == RequestStatus.userFailure) {
      errorText = response["Message"];
      logoutError401(response["Status"], auth, 4);
    } else {
      print(response);
      print(videoStatus);
    }

    update();
  }
}
