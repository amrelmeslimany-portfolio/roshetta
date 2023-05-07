import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/assistant/clinics_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/clinics/clinic_item.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';

class AssistantClinicDetails extends StatelessWidget {
  AssistantClinicDetails({super.key});
  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final clinicsController = Get.find<AssistantClinicsController>();

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      floatingButton: SizedBox(
        height: 40,
        child: FloatingActionButton.extended(
          elevation: 3,
          extendedPadding: const EdgeInsets.all(10),
          label: const Text("الحجوزات",
              style:
                  TextStyle(color: Colors.white, fontWeight: FontWeight.w600)),
          icon:
              const Icon(Icons.list_alt_rounded, size: 19, color: Colors.white),
          backgroundColor: AppColors.primaryColor,
          onPressed: () {
            Get.toNamed(AppRoutes.assistantAppointments,
                arguments: {"clinic_id": clinicsController.clinic.value!.id},
                preventDuplicates: true);
          },
        ),
      ),
      body: BodyLayout(
          appbar: CustomAppBar(onPressed: () {
            toggleDrawer(scaffoldKey);
          }).init,
          content: [
            Obx(() {
              return ClinicDetailsItem(
                  status: clinicsController.loginStatus.value,
                  clinic: clinicsController.clinic.value,
                  onSetting: () => _onSettings());
            }),
            const SizedBox(height: 50)
          ]),
    );
  }

  void _onSettings() {
    List<ButtonSheetItem> buttons = StaticData.clinicDoctorSettings(
        onEditClinic: () {
          if (Get.isBottomSheetOpen == true) {
            Get.back();
          }
          Get.toNamed(AppRoutes.assistantClinicEdit);
        },
        onExitClinic: () async => await clinicsController
            .onClinicLogout(clinicsController.clinic.value!.id),
        status: clinicsController.clinic.value!.status);
    CustomBottomSheets.custom(
        text: clinicsController.clinic.value!.name!,
        items: buttons,
        height: 200);
  }
}
