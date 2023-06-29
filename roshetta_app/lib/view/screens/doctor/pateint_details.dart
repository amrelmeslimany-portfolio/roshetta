import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/doctor/doctorpatient_controller.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/clinics/date_box.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/profile/banner.dart';
import 'package:roshetta_app/view/widgets/profile/header.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class DoctorPatientDetails extends StatelessWidget {
  DoctorPatientDetails({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final patientController = Get.put(DoctorPatientDetailsController());

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
        floatingButton: Obx(
          () => SizedBox(
            height: 40,
            child: patientController.patient.value != null &&
                    patientController.patient.value!.appointCase == "1"
                ? FloatingActionButton.extended(
                    elevation: 3,
                    extendedPadding: const EdgeInsets.all(10),
                    label: const Text("اضافة روشته",
                        style: TextStyle(
                            color: Colors.white, fontWeight: FontWeight.w600)),
                    icon: const Icon(Icons.add_box_rounded,
                        size: 19, color: Colors.white),
                    backgroundColor: AppColors.primaryColor,
                    onPressed: () {
                      patientController.changeTypeForm("new");
                      patientController.changeCurrentForm(0);
                      Get.toNamed(AppRoutes.doctorAddPrescript);
                    },
                  )
                : null,
          ),
        ),
        scaffoldKey: scaffoldKey,
        body: BodyLayout(
            appbar: CustomAppBar(onPressed: () {
              toggleDrawer(scaffoldKey);
            }).init,
            content: [
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 8),
                child: Obx(() {
                  return CustomRequest(
                    sameContent: false,
                    status: patientController.infoStatus.value,
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
                        ProfileBanner(
                          patient: patientController.patient.value,
                          clinicType: Users.patient.name,
                        ),
                        const SizedBox(height: 30),
                        HeaderBadge(
                            header: "الامراض",
                            badgeText: handleNumbers(
                                patientController.diseases.length),
                            description: "عند الضغط علي المرض سيعرض الروشتات"),
                        const SizedBox(height: 15),
                        patientController.diseases.isNotEmpty
                            ? ListView.separated(
                                separatorBuilder: (context, index) {
                                  return const SizedBox(height: 15);
                                },
                                shrinkWrap: true,
                                physics: const NeverScrollableScrollPhysics(),
                                itemCount: patientController.diseases.length,
                                itemBuilder: (context, index) {
                                  var item = patientController.diseases[index];

                                  return CustomListTile(
                                    onTilePressed: () {
                                      patientController.diseaseId.value =
                                          item["disease_id"];
                                      Get.toNamed(
                                          AppRoutes.doctorDiseasePrescripts,
                                          arguments: {
                                            "diseaseName": item["name"]
                                          });
                                    },
                                    widget: item["isNew"] != null &&
                                            item["isNew"] == true
                                        ? Center(
                                            child: const CustomText(
                                                    text: "الان",
                                                    color:
                                                        AppColors.primaryColor)
                                                .subHeader(context),
                                          )
                                        : DateBox(date: item["date"]),
                                    smallTitle: item["disease_id"],
                                    title: item["name"],
                                    middleWidget: item["isNew"] != null &&
                                            item["isNew"] == true
                                        ? const CustomText(
                                            text: "جديد",
                                            textType: 4,
                                            color: AppColors.primaryColor)
                                        : null,
                                    descriptionIcon: FontAwesomeIcons.child,
                                    description: item["place"],
                                    descriptionColor: AppColors.primaryTextColor
                                        .withOpacity(0.8),
                                    buttonIcon: Icons.more_horiz,
                                    onButtonPressed: () {
                                      _diseaseSettings(item);
                                    },
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

  _diseaseSettings(item) {
    List<ButtonSheetItem> buttons = [
      ButtonSheetItem(
          icon: Icons.remove_red_eye,
          text: "الروشتات",
          onTap: () {
            if (Get.isBottomSheetOpen == true) {
              Get.back();
            }
            patientController.diseaseId.value = item["disease_id"];
            Get.toNamed(AppRoutes.doctorDiseasePrescripts,
                arguments: {"diseaseName": item["name"]});
          }),
      if (patientController.patient.value!.appointCase == "1")
        ButtonSheetItem(
            icon: Icons.replay,
            text: "اعادة الكشف",
            onTap: () {
              if (Get.isBottomSheetOpen == true) {
                Get.back();
              }
              patientController.changeTypeForm("rediscovery",
                  diseaesId: item["disease_id"]);
              patientController.changeCurrentForm(1);
              Get.toNamed(AppRoutes.doctorAddPrescript,
                  arguments: item["name"]);
            })
    ];
    CustomBottomSheets.custom(text: item["name"], items: buttons);
  }
}
