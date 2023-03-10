import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/middlewares/auth_middleware.dart';
import 'package:roshetta_app/view/screens/auth/auth_intro.dart';
import 'package:roshetta_app/view/screens/auth/create_account.dart';
import 'package:roshetta_app/view/screens/auth/forgot_password.dart';
import 'package:roshetta_app/view/screens/auth/login.dart';
import 'package:roshetta_app/view/screens/auth/verify_email_code.dart';
import 'package:roshetta_app/view/screens/patient/appointments.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';

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
  // Pages
  GetPage(
      name: AppRoutes.home,
      page: () => HomeLayout(scaffoldKey: GlobalKey<ScaffoldState>()),
      middlewares: [AuthGuard()]),
  GetPage(
      name: AppRoutes.appointments,
      page: () => const Appointments(),
      middlewares: [AuthGuard()]),
];
