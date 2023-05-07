import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/doctor/doctorpatient_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/profile/header.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class DoctorPrescript extends StatelessWidget {
  DoctorPrescript({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final patientController = Get.put(DoctorPatientDetailsController());
  final diseaseName = checkArgument("diseaseName");

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
        scaffoldKey: scaffoldKey,
        floatingButton: Obx(
          () => SizedBox(
            height: 40,
            // TODO change to 1
            child: patientController.patient.value != null &&
                    patientController.patient.value!.appointCase == "1"
                ? FloatingActionButton.extended(
                    elevation: 3,
                    extendedPadding: const EdgeInsets.all(10),
                    label: const Text("اعادة كشف",
                        style: TextStyle(
                            color: Colors.white, fontWeight: FontWeight.w600)),
                    icon: const Icon(
                      Icons.replay,
                      color: Colors.white,
                      size: 19,
                    ),
                    backgroundColor: AppColors.primaryColor,
                    onPressed: () {
                      patientController.changeTypeForm("rediscovery",
                          diseaesId: patientController.diseaseId.value);
                      patientController.changeCurrentForm(1);
                      Get.toNamed(AppRoutes.doctorAddPrescript,
                          arguments: diseaseName);
                    },
                  )
                : null,
          ),
        ),
        body: BodyLayout(
            appbar: CustomAppBar(onPressed: () {
              toggleDrawer(scaffoldKey);
            }).init,
            content: [
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 8),
                child: GetBuilder<DoctorPatientDetailsController>(
                    initState: (_) async {
                  await patientController.getDiseasePrescripts();
                  patientController.update();
                }, builder: (_) {
                  return CustomRequest(
                    sameContent: false,
                    status: patientController.prescriptStatus.value,
                    widget: Column(
                      children: [
                        ProfileHeader(
                            image: patientController.patient.value?.image ??
                                AssetPaths.emptyPerson,
                            title: patientController.patient.value?.name ??
                                "لا يوجد",
                            subTitle: patientController.patient.value?.phone ??
                                "لا يوجد",
                            icon: FontAwesomeIcons.phone),
                        const SizedBox(height: 30),
                        Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            iconAndWidget(FontAwesomeIcons.disease,
                                iconSize: 17,
                                iconColor: AppColors.lightTextColor,
                                widget: const CustomText(
                                    text: "اسم المرض:",
                                    textType: 3,
                                    color: AppColors.lightTextColor)),
                            const SizedBox(width: 5),
                            Expanded(
                              child: CustomText(
                                  text: diseaseName ?? "حدثت مشكله ما",
                                  align: TextAlign.start,
                                  fontWeight: FontWeight.w700,
                                  textType: 3,
                                  color: AppColors.primaryTextColor),
                            ),
                          ],
                        ),
                        const SizedBox(height: 30),
                        HeaderBadge(
                            header: "الروشتات",
                            badgeText: handleNumbers(
                                patientController.prescripts.length),
                            description:
                                "عند الضغط علي الروشته سيعرض التفاصيل"),
                        const SizedBox(height: 15),
                        patientController.prescripts.isNotEmpty
                            ? ListView.separated(
                                separatorBuilder: (context, index) {
                                  return const SizedBox(height: 15);
                                },
                                shrinkWrap: true,
                                physics: const NeverScrollableScrollPhysics(),
                                itemCount: patientController.prescripts.length,
                                itemBuilder: (context, index) {
                                  var item =
                                      patientController.prescripts[index];

                                  return CustomListTile(
                                    onTilePressed: () {
                                      patientController
                                          .getDiseasePrescriptDetails(
                                              item["prescript_id"]);
                                    },
                                    widget: const Center(
                                      child: Icon(
                                        FontAwesomeIcons.pills,
                                        size: 30,
                                        color: AppColors.primaryColor,
                                      ),
                                    ),
                                    smallTitle: item["disease_name"],
                                    title: item["prescript_ser_id"],
                                    middleWidget: item["isNew"] != null &&
                                            item["isNew"] == true
                                        ? const CustomText(
                                            text: "جديد",
                                            textType: 4,
                                            color: AppColors.primaryColor)
                                        : null,
                                    descriptionIcon: FontAwesomeIcons.clock,
                                    description: item["created_date"],
                                    descriptionColor: AppColors.primaryTextColor
                                        .withOpacity(0.8),
                                  );
                                },
                              )
                            : emptyLottieList()
                      ],
                    ),
                  );
                }),
              ),
              const SizedBox(height: 70)
            ]));
  }
}
