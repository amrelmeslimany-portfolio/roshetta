import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/bindings/bindings.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/middlewares/auth_middleware.dart';
import 'package:roshetta_app/core/middlewares/roles_middleware.dart';
import 'package:roshetta_app/view/screens/assistant/appointments.dart';
import 'package:roshetta_app/view/screens/assistant/clinic_details.dart';
import 'package:roshetta_app/view/screens/assistant/clinics.dart';
import 'package:roshetta_app/view/screens/assistant/edit_clinic.dart';
import 'package:roshetta_app/view/screens/auth/auth_intro.dart';
import 'package:roshetta_app/view/screens/auth/create_account.dart';
import 'package:roshetta_app/view/screens/auth/forgot_password.dart';
import 'package:roshetta_app/view/screens/auth/login.dart';
import 'package:roshetta_app/view/screens/auth/reset_password.dart';
import 'package:roshetta_app/view/screens/auth/verify_email_code.dart';
import 'package:roshetta_app/view/screens/auth/verify_forgotpassword_code.dart';
import 'package:roshetta_app/view/screens/doctor/add_clinic.dart';
import 'package:roshetta_app/view/screens/doctor/add_prescript.dart';
import 'package:roshetta_app/view/screens/doctor/appointments.dart';
import 'package:roshetta_app/view/screens/doctor/assistant.dart';
import 'package:roshetta_app/view/screens/doctor/clinic_details.dart';
import 'package:roshetta_app/view/screens/doctor/clinics.dart';
import 'package:roshetta_app/view/screens/doctor/pateint_details.dart';
import 'package:roshetta_app/view/screens/doctor/prescriptions.dart';
import 'package:roshetta_app/view/screens/doctor_pharmacy/verify_accounts.dart';
import 'package:roshetta_app/view/screens/patient/appointments.dart';
import 'package:roshetta_app/view/screens/patient/clinic_details.dart';
import 'package:roshetta_app/view/screens/patient/clinics.dart';
import 'package:roshetta_app/view/screens/patient/diseases.dart';
import 'package:roshetta_app/view/screens/patient/orders.dart';
import 'package:roshetta_app/view/screens/patient/pharmacy_details.dart';
import 'package:roshetta_app/view/screens/patient/pharmacys.dart';
import 'package:roshetta_app/view/screens/patient/prescript_details.dart';
import 'package:roshetta_app/view/screens/patient/prescriptions.dart';
import 'package:roshetta_app/view/screens/pharmacist/add_pharmacy.dart';
import 'package:roshetta_app/view/screens/pharmacist/pharmacy_details.dart';
import 'package:roshetta_app/view/screens/pharmacist/pharmacys.dart';
import 'package:roshetta_app/view/screens/pharmacist/prescriptions.dart';
import 'package:roshetta_app/view/screens/profile/edit_password.dart';
import 'package:roshetta_app/view/screens/profile/edit_profile.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/pharmacist/sell_prescripts/sell_prescript_form.dart';

List<GetPage<dynamic>>? routes = [
  // Auth
  GetPage(
      name: "/",
      page: () => const AuthIntro(),
      middlewares: [AuthMiddleware()]),

  GetPage(name: AppRoutes.intro, page: () => const AuthIntro()),
  GetPage(name: AppRoutes.login, page: () => const Login()),
  GetPage(name: AppRoutes.createAccount, page: () => const CreateAccount()),
  GetPage(name: AppRoutes.verifyEmailCode, page: () => const VerifyEmailCode()),
  GetPage(name: AppRoutes.forgotPassword, page: () => const ForgotPassword()),
  GetPage(
      name: AppRoutes.verifyForgotPassCode,
      page: () => const VerifyForgotPass()),
  GetPage(name: AppRoutes.resetForgotPass, page: () => const ResetPassword()),

  // Global Pages
  GetPage(
      name: AppRoutes.home,
      page: () => HomeLayout(scaffoldKey: GlobalKey<ScaffoldState>()),
      middlewares: [AuthGuard()]),
  GetPage(
      name: AppRoutes.editProfile,
      page: () => const EditProfile(),
      middlewares: [AuthGuard()]),
  GetPage(
      name: AppRoutes.editPassword,
      page: () => const EditPassword(),
      middlewares: [AuthGuard()]),

  // Patient Pages
  GetPage(
      name: AppRoutes.patientClinics,
      page: () => PatientClinics(),
      middlewares: [AuthGuard(), PatientMiddleware()],
      binding: PatientBindings()),
  GetPage(
      name: AppRoutes.patientClinicDetails,
      page: () => PatientClinicDetails(),
      middlewares: [AuthGuard(), PatientMiddleware()],
      binding: PatientBindings()),
  GetPage(
      name: AppRoutes.patientPrescripts,
      page: () => PatientPrescripts(),
      middlewares: [AuthGuard(), PatientMiddleware()]),
  GetPage(
      name: AppRoutes.patientDiseases,
      page: () => PatientDiseases(),
      middlewares: [AuthGuard(), PatientMiddleware()]),

  GetPage(
      name: AppRoutes.patientappointments,
      page: () => PatientAppointments(),
      middlewares: [AuthGuard(), PatientMiddleware()]),
  GetPage(
      name: AppRoutes.patientPharmacys,
      page: () => PatientPharmacys(),
      middlewares: [AuthGuard(), PatientMiddleware()],
      binding: PatientBindings()),
  GetPage(
      name: AppRoutes.patientPharmacyDetails,
      page: () => PatientPharmacyDetails(),
      middlewares: [AuthGuard(), PatientMiddleware()],
      binding: PatientBindings()),
  GetPage(
      name: AppRoutes.patientOrders,
      page: () => PatientOrders(),
      middlewares: [AuthGuard(), PatientMiddleware()],
      binding: PatientBindings()),

  // Doctor & Pharmacy pages
  GetPage(
      name: AppRoutes.verifyDPAccount,
      page: () => VerifyAccount(),
      middlewares: [AuthGuard(), DocotorPharmacistMiddleware()]),

  // Doctor
  GetPage(
      name: AppRoutes.doctorAddClinic,
      page: () => AddClinic(),
      middlewares: [AuthGuard(), DocotorMiddleware()],
      binding: DoctorBindings()),
  GetPage(
      name: AppRoutes.doctorClinics,
      page: () => DoctorClinics(),
      middlewares: [AuthGuard(), DocotorMiddleware()],
      binding: DoctorBindings()),
  GetPage(
      name: AppRoutes.doctorClinicDetails,
      page: () => DoctorClinicDetails(),
      middlewares: [AuthGuard(), DocotorMiddleware()],
      binding: DoctorBindings()),
  GetPage(
      name: AppRoutes.doctorClinicAssistant,
      page: () => DoctorAssistant(),
      middlewares: [AuthGuard(), DocotorMiddleware()],
      binding: DoctorBindings()),
  GetPage(
      name: AppRoutes.doctorAppointments,
      page: () => DoctorAppointments(),
      middlewares: [AuthGuard(), DocotorMiddleware()]),
  GetPage(
      name: AppRoutes.doctorPatient,
      page: () => DoctorPatientDetails(),
      middlewares: [AuthGuard(), DocotorMiddleware()]),
  GetPage(
      name: AppRoutes.doctorDiseasePrescripts,
      page: () => DoctorPrescript(),
      middlewares: [AuthGuard(), DocotorMiddleware()]),
  GetPage(
      name: AppRoutes.doctorAddPrescript,
      page: () => AddPrescript(),
      middlewares: [AuthGuard(), DocotorMiddleware()]),

  // Assistant
  GetPage(
      name: AppRoutes.assistantClinics,
      page: () => AssistantClinics(),
      middlewares: [AuthGuard(), AssistantMiddleware()],
      binding: AssistantBindings()),
  GetPage(
      name: AppRoutes.assistantClinicDetails,
      page: () => AssistantClinicDetails(),
      middlewares: [AuthGuard(), AssistantMiddleware()],
      binding: AssistantBindings()),
  GetPage(
      name: AppRoutes.assistantAppointments,
      page: () => AssistantAppointments(),
      middlewares: [AuthGuard(), AssistantMiddleware()]),
  GetPage(
      name: AppRoutes.assistantClinicEdit,
      page: () => EditClinicAssistant(),
      middlewares: [AuthGuard(), AssistantMiddleware()]),

  // Pharmacist
  GetPage(
      name: AppRoutes.addPharmacy,
      page: () => AddPharmacy(),
      middlewares: [AuthGuard(), PharmacistMiddleware()],
      binding: PharmacistBindings()),
  GetPage(
      name: AppRoutes.pharmacistPharmacys,
      page: () => PharmacistPharmacys(),
      middlewares: [AuthGuard(), PharmacistMiddleware()],
      binding: PharmacistBindings()),
  GetPage(
      name: AppRoutes.pharmacistPharmacyDetails,
      page: () => PharmacistPharmacyDetails(),
      middlewares: [AuthGuard(), PharmacistMiddleware()],
      binding: PharmacistBindings()),
  GetPage(
      name: AppRoutes.pharmacySellPrescript,
      page: () => SellPrescriptForm(),
      middlewares: [AuthGuard(), PharmacistMiddleware()],
      binding: PharmacistBindings()),
  GetPage(
      name: AppRoutes.pharmacyPrescripts,
      page: () => PharmacistPrescripts(),
      middlewares: [AuthGuard(), PharmacistMiddleware()]),
];
