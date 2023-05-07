import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/patient/diseases_controller.dart';
import 'package:roshetta_app/controllers/patient/prescripts_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/date_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class PatientDiseases extends StatelessWidget {
  PatientDiseases({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final diseasesController = Get.put(PatientDiseasesController());

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
        scaffoldKey: scaffoldKey,
        body: BodyLayout(
            appbar: CustomAppBar(onPressed: () {
              toggleDrawer(scaffoldKey);
            }).init,
            content: [
              Obx(
                () => HeaderBadge(
                    header: "الامراض",
                    badgeText:
                        handleNumbers(diseasesController.diseases.length),
                    description:
                        "عند الضغط علي المرض سيعرض الروشتات لهذا المرض"),
              ),
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 8),
                child: Obx(() {
                  return CustomRequest(
                    sameContent: false,
                    status: diseasesController.diseasesStatus.value,
                    widget: Column(
                      children: [
                        const SizedBox(height: 15),
                        diseasesController.diseases.isNotEmpty
                            ? ListView.separated(
                                separatorBuilder: (context, index) {
                                  return const SizedBox(height: 15);
                                },
                                shrinkWrap: true,
                                physics: const NeverScrollableScrollPhysics(),
                                itemCount: diseasesController.diseases.length,
                                itemBuilder: (context, index) {
                                  var item = diseasesController.diseases[index];

                                  return CustomListTile(
                                    mediaHeight: 70,
                                    onTilePressed: () {
                                      Get.toNamed(AppRoutes.patientPrescripts,
                                          arguments: {
                                            "disease_id": item["disease_id"],
                                            "disease_name": item["name"],
                                          });
                                    },
                                    widget: Center(
                                      child: getDiffernceDays(item["date"]) != 0
                                          ? Column(
                                              mainAxisAlignment:
                                                  MainAxisAlignment.center,
                                              children: [
                                                const CustomText(
                                                    text: "منذ",
                                                    color:
                                                        AppColors.primaryColor,
                                                    textType: 4),
                                                const SizedBox(height: 7),
                                                Text(
                                                    getDiffernceDays(
                                                        item["date"]),
                                                    softWrap: false,
                                                    overflow:
                                                        TextOverflow.ellipsis,
                                                    style: const TextStyle(
                                                        color: AppColors
                                                            .primaryColor,
                                                        fontSize: 22,
                                                        height: 0.8,
                                                        fontWeight:
                                                            FontWeight.bold)),
                                                const CustomText(
                                                    text: "ايام",
                                                    color:
                                                        AppColors.primaryColor,
                                                    textType: 4),
                                              ],
                                            )
                                          : const Text("اليوم",
                                              style: TextStyle(
                                                  color: AppColors.primaryColor,
                                                  fontSize: 20,
                                                  fontWeight: FontWeight.bold)),
                                    ),
                                    smallTitle: item["place"],
                                    title: item["name"],
                                    middleWidget:
                                        getDiffernceDays(item["date"]) == 0
                                            ? const CustomText(
                                                text: "جديد",
                                                color: AppColors.primaryColor,
                                                textType: 5)
                                            : null,
                                    descriptionIcon: FontAwesomeIcons.clock,
                                    description: item["date"],
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
              const SizedBox(height: 50)
            ]));
  }
}
