import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/assistant/clinics_controller.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';
import '../../widgets/clinics/clinics_list.dart';

class AssistantClinics extends StatelessWidget {
  AssistantClinics({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final clinicsController = Get.find<AssistantClinicsController>();

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      body: BodyLayout(
          appbar: CustomAppBar(onPressed: () {
            toggleDrawer(scaffoldKey);
          }).init,
          content: [
            Obx(() {
              return Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  HeaderBadge(
                      header: "العيادات",
                      badgeText:
                          handleNumbers(clinicsController.clinics.length),
                      description: clinicsController.status.value !=
                              RequestStatus.success
                          ? "يجب ان تتواصل مع العياده للعمل بها"
                          : "عند الضغط علي العياده سيتم تسجيل الدخول اليها"),
                  const SizedBox(height: 15),
                  ClinicsList(
                      role: Users.assistant,
                      status: clinicsController.status,
                      loginStatus: clinicsController.loginStatus,
                      clinics: clinicsController.clinics,
                      onLogin: clinicsController.onLogin,
                      onButtonPressed: (item) {
                        onSettingsCliked(item);
                      })
                ],
              );
            }),
            const SizedBox(height: 50)
          ]),
    );
  }

  List<ButtonSheetItem> sheetItems(
          {Function()? onLogin, Function()? onClose, required String status}) =>
      [
        status != "0"
            ? ButtonSheetItem(
                icon: Icons.pause_circle_outlined,
                text: "غلق العياده",
                onTap: onClose)
            : ButtonSheetItem(
                icon: Icons.login, text: "فتح العياده", onTap: onLogin),
      ];

  onSettingsCliked(Map item) {
    CustomBottomSheets.custom(
        text: item["name"],
        items: sheetItems(
          onLogin: () {
            clinicsController.onLogin(item["id"]);
          },
          status: item["status"],
          onClose: () => clinicsController.onClinicLogout(item["id"]),
        ),
        height: 150);
  }
}
