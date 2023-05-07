import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/pharmacist/pharmacys_controller.dart';
import 'package:roshetta_app/core/class/enums.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/clinics/clinic_item.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/pharmacist/sell_prescripts/sell_prescript_form.dart';
import 'package:roshetta_app/view/widgets/shared/floating_button.dart';

class PharmacistPharmacyDetails extends StatelessWidget {
  PharmacistPharmacyDetails({super.key});
  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final pharmacyController = Get.find<PharmacistPharmacyController>();

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      floatingButton: CustomFloatingBTN(
        icon: Icons.add_circle,
        text: "صرف روشته",
        onPressed: () {
          Map arguments = {
            "pharmacy_id": pharmacyController.pharmacy.value!.id
          };
          Get.toNamed(AppRoutes.pharmacySellPrescript, arguments: arguments);
        },
      ),
      body: BodyLayout(
          appbar: CustomAppBar(onPressed: () {
            toggleDrawer(scaffoldKey);
          }).init,
          content: [
            Obx(() {
              return ClinicDetailsItem(
                  status: pharmacyController.loginStatus.value,
                  pharmacy: pharmacyController.pharmacy.value,
                  headerWidget: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Expanded(
                          child: BGButton(context, text: "الطلبات", small: true,
                              onPressed: () {
                        Map arguments = {
                          "pharmacy_id": pharmacyController.pharmacy.value!.id,
                          "type": PrescriptStatus.wating
                        };
                        Get.toNamed(AppRoutes.pharmacyPrescripts,
                            arguments: arguments);
                      }).button),
                      const SizedBox(width: 10),
                      Expanded(
                        child: BorderedButton(context,
                            text: "الروشتات", small: true, onPressed: () {
                          Map arguments = {
                            "pharmacy_id":
                                pharmacyController.pharmacy.value!.id,
                            "type": PrescriptStatus.done
                          };
                          Get.toNamed(AppRoutes.pharmacyPrescripts,
                              arguments: arguments);
                        }).button,
                      )
                    ],
                  ),
                  onSetting: () => _onSettings());
            }),
            const SizedBox(height: 70)
          ]),
    );
  }

  void _onSettings() {
    List<ButtonSheetItem> buttons = StaticData.clinicDoctorSettings(
        text: "الصيدلية",
        onEditClinic: () {
          if (Get.isBottomSheetOpen == true) {
            Get.back();
          }
          Get.toNamed(AppRoutes.addPharmacy, arguments: {"isEdit": true});
        },
        onExitClinic: () async => await pharmacyController
            .onPharmacyLogout(pharmacyController.pharmacy.value!.id),
        status: pharmacyController.pharmacy.value!.status);
    CustomBottomSheets.custom(
        text: pharmacyController.pharmacy.value!.name!,
        items: buttons,
        height: 200);
  }
}
