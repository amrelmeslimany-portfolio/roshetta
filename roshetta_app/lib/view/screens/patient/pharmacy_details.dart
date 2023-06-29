import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/controllers/patient/pharmacy_controller.dart';
import 'package:roshetta_app/controllers/patient/prescripts_controller.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/patient/pharmacy_prescripts_list.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/profile/banner.dart';
import 'package:roshetta_app/view/widgets/profile/bottom_banner.dart';
import 'package:roshetta_app/view/widgets/profile/header.dart';
import 'package:roshetta_app/view/widgets/profile/info_list.dart';

class PatientPharmacyDetails extends StatelessWidget {
  PatientPharmacyDetails({super.key});
  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final patientControllers = Get.find<PatientPharmacysController>();

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      onRefresh: () async => await patientControllers
          .goToPharmacyDetails(patientControllers.pharmacy.value?.id ?? ""),
      floatingButton: SizedBox(
        height: 50,
        width: 50,
        child: FloatingActionButton(
            onPressed: () => _sendPrescriptSheet(),
            backgroundColor: AppColors.primaryColor,
            child: const FaIcon(
              FontAwesomeIcons.solidPaperPlane,
              size: 24,
              color: Colors.white,
            )),
      ),
      body: BodyLayout(
          appbar: CustomAppBar(onPressed: () {
            toggleDrawer(scaffoldKey);
          }).init,
          content: [
            Obx(() {
              if (patientControllers.pharmacy.value == null) {
                return Lottie.asset(AssetPaths.loading, height: 80);
              }

              return CustomRequest(
                  status: patientControllers.pharmacyDetailsStatus.value,
                  sameContent: false,
                  widget: Column(
                    children: [
                      ProfileHeader(
                        image: patientControllers.pharmacy.value?.logo ??
                            AssetPaths.emptyIMG,
                        title:
                            patientControllers.pharmacy.value?.name ?? "name",
                        subTitle: formatCloseOrNot(
                            patientControllers.pharmacy.value!.status!),
                        icon: patientControllers.pharmacy.value!.status == "0"
                            ? Icons.close_rounded
                            : Icons.check_circle,
                        subTitleColor:
                            patientControllers.pharmacy.value!.status == "1"
                                ? AppColors.primaryColor
                                : null,
                      ),
                      const SizedBox(height: 30),
                      ProfileBanner(
                          pharmacy: patientControllers.pharmacy.value,
                          clinicType: Users.patient.name),
                      const SizedBox(height: 30),
                      ProfileInfoList(
                        address: patientControllers.pharmacy.value?.address ??
                            "address",
                        governorate:
                            patientControllers.pharmacy.value?.governorate ??
                                "info",
                        phone: patientControllers.pharmacy.value?.phoneNumber ??
                            "info",
                      ),
                      const SizedBox(height: 30),
                      ProfileBottomBanner(
                          pharmacy: patientControllers.pharmacy.value,
                          type: Users.patient.name),
                    ],
                  ));
            }),
            const SizedBox(height: 50)
          ]),
    );
  }

  _sendPrescriptSheet() {
    if (patientControllers.pharmacy.value == null) return;
    PatientPrescriptsController prescriptsController =
        Get.put(PatientPrescriptsController());
    // Init prescripts
    prescriptsController.getPrescripts(isAll: true);

    if (patientControllers.isPrescripts(prescriptsController.prescripts)) {
      // Widget
      Get.bottomSheet(Obx(() => CustomBottomSheets().sheet([
            CustomBottomSheets().header("ارسل روشته للصيدلية"),
            const SizedBox(height: 5),
            _prescriptsListWidget(prescriptsController),
          ], height: 340, isLoading: patientControllers.ordersStatus.value,
              onSubmit: () {
            patientControllers.onSendPrescript();
          }))).then((value) => patientControllers.setOrderId(""));
    }
  }

  Widget _prescriptsListWidget(PatientPrescriptsController prescriptsCon) {
    return CustomRequest(
        sameContent: true,
        status: patientControllers.ordersStatus.value,
        widget: Column(
          children: [
            CustomRequest(
                sameContent: false,
                status: prescriptsCon.prescriptStatus.value,
                widget: PharmacyPrescriptsList(
                    onClear: () => patientControllers.onClearOrderId(),
                    selected: patientControllers.orderId.value,
                    prescripts: prescriptsCon.prescripts
                        .where((item) => item["prescriptStatus"] != "isOrder")
                        .toList(),
                    onItemPressed: (id) {
                      patientControllers.setOrderId(id);
                    })),
          ],
        ));
  }
}
