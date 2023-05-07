import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/doctor/clinics_controller.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/screens/doctor_pharmacy/verify_place.dart';
import 'package:roshetta_app/view/widgets/clinics/clinics_list.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';

class DoctorClinics extends StatelessWidget {
  DoctorClinics({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final clinicsController = Get.find<DoctorClinicsController>();

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
                children: [
                  HeaderContent(
                      header: "العيادات",
                      spacer: 5,
                      content: CustomText(
                        text: clinicsController.status.value !=
                                RequestStatus.success
                            ? "يجب اضافة عيادة من صفحة اضافة العيادات"
                            : "عند الضغط علي العياده سيتم تسجيل الدخول اليها",
                        textType: 3,
                        color: AppColors.lightTextColor,
                        align: TextAlign.start,
                      )),
                  const SizedBox(height: 15),
                  ClinicsList(
                      role: Users.doctor,
                      status: clinicsController.status,
                      loginStatus: clinicsController.loginStatus,
                      clinics: clinicsController.clinics,
                      onLogin: clinicsController.onLogin,
                      onButtonPressed: (item) {
                        if (item["isVerify"] == "success") {
                          // print(item["id"]);
                          onSettingsCliked(item);
                        } else if (item["isVerify"] == "waiting") {
                          onWaitingVerifyClinic(context);
                        } else {
                          onVerifyClinic(context, item);
                        }
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

  onVerifyClinic(BuildContext context, Map item) {
    Get.bottomSheet(CustomBottomSheets().sheet([
      const CustomText(text: "وثق العياده اولا").subHeader(context),
      const SizedBox(height: 5),
      const CustomText(
        text:
            "يجب توثيق العياده لكي تستطيع الدخول اليها والعمل فيها واستخدام مميزاتها",
        textType: 3,
        color: AppColors.lightTextColor,
      ),
      const SizedBox(height: 10),
      UnconstrainedBox(
          child:
              BGButton(context, text: "توثيق الان", small: true, onPressed: () {
        Get.back();
        Get.to(() => VerifyPlace(
            type: "clinic", placeID: item["id"], placeName: item["name"]));
      }).button)
    ], height: 180));
  }

  onWaitingVerifyClinic(BuildContext context) {
    Get.bottomSheet(CustomBottomSheets().sheet([
      const CustomText(text: "يرجي الانتظار").subHeader(context),
      const SizedBox(height: 10),
      const CustomText(
        text: "يتم مراجعة صورة ترخيص العياده التي قمت برفعها من المسؤلين",
        textType: 3,
        color: AppColors.lightTextColor,
      ),
    ], height: 150));
  }
}
