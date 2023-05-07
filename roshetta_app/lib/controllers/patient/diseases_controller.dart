import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/data/source/remote/patient/prescripts_data.dart';

class PatientDiseasesController extends GetxController {
  final auth = Get.find<AuthenticationController>();
  late Rx<RequestStatus> diseasesStatus = RequestStatus.none.obs;
  PatientPrescriptsData requests = PatientPrescriptsData(Get.find<Crud>());
  final diseases = [].obs;

  @override
  void onInit() async {
    super.onInit();
    await getDiseases();
  }

  getDiseases() async {
    diseasesStatus.value = RequestStatus.loading;
    var response = await requests.getDiseases(getToken(auth)!);
    diseasesStatus.value = checkResponseStatus(response);
    print(response);
    if (diseasesStatus.value == RequestStatus.success) {
      if (response["Data"] == null) {
        diseasesStatus.value = RequestStatus.empty;
        return;
      }
      diseases.addAll(response["Data"].toList());
    } else {
      handleSnackErrors(response);
    }
  }
}
