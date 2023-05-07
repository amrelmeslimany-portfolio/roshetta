import 'package:get/get.dart';
import 'package:roshetta_app/controllers/assistant/clinics_controller.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/controllers/auth/createaccount_controller.dart';
import 'package:roshetta_app/controllers/auth/forgotpassword_controller.dart';
import 'package:roshetta_app/controllers/auth/login_controller.dart';
import 'package:roshetta_app/controllers/doctor/clinics_controller.dart';
import 'package:roshetta_app/controllers/patient/clinics_controller.dart';
import 'package:roshetta_app/controllers/patient/orders_controller.dart';
import 'package:roshetta_app/controllers/patient/pharmacy_controller.dart';
import 'package:roshetta_app/controllers/pharmacist/pharmacys_controller.dart';
import 'package:roshetta_app/controllers/pharmacist/prescripts_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';

class InitBinding extends Bindings {
  @override
  void dependencies() {
    // Global Binding Controllers
    Get.put<Crud>(Crud());
    Get.put<AuthenticationController>(AuthenticationController());

    // Auth
    Get.lazyPut<LoginControllerImp>(() => LoginControllerImp(), fenix: true);

    Get.lazyPut<ForgotPasswordControllerImp>(
        () => ForgotPasswordControllerImp(),
        fenix: true);

    Get.lazyPut<CreateAccControllerImp>(() => CreateAccControllerImp(),
        fenix: true);
  }
}

class DoctorBindings extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut(() => DoctorClinicsController());
  }
}

class PharmacistBindings extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut(() => PharmacistPharmacyController());
    Get.lazyPut(() => PharmacyPrescriptsController());
  }
}

class AssistantBindings extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut(() => AssistantClinicsController());
  }
}

class PatientBindings extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut(() => PatientClinicsController());
    Get.lazyPut(() => PatientPharmacysController());
    Get.lazyPut(() => PatientOrdersController());
  }
}
