import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/patient/clinics_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';

class PatientClinics extends StatelessWidget {
  PatientClinics({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final patient = Get.find<PatientClinicsController>();

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      body: BodyLayout(
          appbar: CustomAppBar(
              onPressed: () {
                toggleDrawer(scaffoldKey);
              },
              children: SizedBox(
                width: double.infinity,
                child: Column(
                  children: [
                    const SizedBox(height: 5),
                    const CustomText(
                        text: "اختر التخصص",
                        color: AppColors.whiteColor,
                        textType: 2),
                    const SizedBox(height: 10),
                    Obx(() => CustomRequest(
                          status: patient.specialistsStatus.value,
                          widget: CustomDropdown(
                              context: context,
                              onValidator: (value) => dropdownValidator(value),
                              hintText: "تخصص العياده",
                              items: patient.spcialistsList,
                              onChange: (value) {
                                patient.onSpecialistChange(value ?? "");
                              }).dropdown,
                        ))
                  ],
                ),
              )).init,
          content: [
            Obx(() {
              return Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const HeaderContent(
                      header: "العيادات",
                      spacer: 5,
                      content: CustomText(
                        text: "عند الضغط علي العياده سيعرض التفاصيل",
                        textType: 3,
                        color: AppColors.lightTextColor,
                        align: TextAlign.start,
                      )),
                  const SizedBox(height: 15),
                  CustomRequest(
                      sameContent: false,
                      status: patient.clinicssStatus.value,
                      widget: ListView.separated(
                        separatorBuilder: (context, index) {
                          return const SizedBox(height: 15);
                        },
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: patient.clinics.length,
                        itemBuilder: (context, index) {
                          var item = patient.clinics[index];

                          return CustomListTile(
                            onTilePressed: () {
                              patient.goToClinicDetails(item["clinic_id"]);
                            },
                            img: item["logo"] ?? AssetPaths.emptyIMG,
                            title: item["name"],
                            smallTitle: item["governorate"],
                            descriptionIcon: FontAwesomeIcons.stethoscope,
                            description: item["specialist"],
                            descriptionColor:
                                AppColors.primaryTextColor.withOpacity(0.8),
                            buttonIcon: checkOpenStatus(
                                item["appoint_case"].toString(),
                                Icons.remove_red_eye,
                                Icons.add_alarm),
                            middleWidget: Text(
                              checkOpenStatus(
                                  item["isOpen"], "مغلقة", "مفتوحة"),
                              style: TextStyle(
                                  fontSize: 12,
                                  color: checkOpenStatus(
                                      item["isOpen"],
                                      AppColors.greyColor,
                                      AppColors.primaryColor)),
                            ),
                            onButtonPressed: () {
                              if (item["appoint_case"] == 0) {
                                patient.displayAppointment(item);
                              } else {
                                patient.addAppointment(context, item);
                              }
                            },
                          );
                        },
                      )),
                ],
              );
            }),
            const SizedBox(height: 50)
          ]),
    );
  }
}
