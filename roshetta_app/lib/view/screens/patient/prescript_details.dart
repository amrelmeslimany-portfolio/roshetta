import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/class/enums.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/date_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/data/models/prescript.modal.dart';
import 'package:roshetta_app/view/widgets/clinics/medicine.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class PrescriptDetails extends StatelessWidget {
  final Prescript? prescript;
  final Widget? floatingButton;
  PrescriptDetails({super.key, required this.prescript, this.floatingButton});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();

  @override
  Widget build(BuildContext context) {
    if (prescript == null) {
      return emptyLottieList();
    }
    final List<Map> roshettaHeader = [
      {
        "icon": FontAwesomeIcons.listOl,
        "title": "سيريال الروشتة",
        "value": prescript?.prescriptSerId ?? "لايوجد",
      },
      {
        "icon": FontAwesomeIcons.userDoctor,
        "title": "الدكتور",
        "value": prescript?.doctorName ?? "لايوجد",
      },
      {
        "icon": FontAwesomeIcons.stethoscope,
        "title": "تخصص الدكتور",
        "value": prescript?.doctorSpecialist ?? "لايوجد",
      },
      {
        "icon": FontAwesomeIcons.solidCalendar,
        "title": "تاريخ الكشف",
        "value": prescript?.createdDate ?? "لايوجد",
      },
      {
        "icon": FontAwesomeIcons.solidCalendarCheck,
        "title": "اعاده الكشف",
        "value": prescript?.rediscoveryDate ?? "لايوجد",
      },
      {
        "icon": FontAwesomeIcons.solidUser,
        "title": "اسم الشخص",
        "value": prescript?.patientName ?? "لايوجد",
      },
      {
        "icon": FontAwesomeIcons.disease,
        "title": "تشخيص المرض",
        "value": prescript?.diseaseName ?? "لايوجد",
      },
    ];
    return HomeLayout(
        scaffoldKey: scaffoldKey,
        floatingButton: floatingButton,
        body: BodyLayout(
            appbar: CustomAppBar(onPressed: () {
              toggleDrawer(scaffoldKey);
            }).init,
            content: [
              HeaderContent(header: "تفاصيل الروشتة", content: Container()),
              _continer(Column(
                children: [
                  ...roshettaHeader.map((element) {
                    return Container(
                      margin: const EdgeInsets.only(bottom: 10),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Expanded(
                            child: iconAndWidget(element["icon"],
                                iconColor: AppColors.lightenWhiteColor,
                                iconSize: 13,
                                widget: CustomText(
                                    text: element["title"],
                                    textType: 5,
                                    color: AppColors.lightenWhiteColor)),
                          ),
                          const SizedBox(width: 5),
                          Expanded(
                              child: CustomText(
                                  text: element["value"],
                                  align: TextAlign.start,
                                  textType: 3,
                                  color: AppColors.whiteColor))
                        ],
                      ),
                    );
                  }).toList(),
                ],
              )),
              // Roshhetta Content
              Container(
                  padding: const EdgeInsets.all(15),
                  color: AppColors.primaryAColor.withOpacity(0.08),
                  child: prescript?.medicineData != null &&
                          prescript!.medicineData!.isNotEmpty
                      ? ListView.separated(
                          separatorBuilder: (context, index) {
                            return const SizedBox(height: 15);
                          },
                          shrinkWrap: true,
                          physics: const NeverScrollableScrollPhysics(),
                          itemCount: prescript!.medicineData!.length,
                          itemBuilder: (context, index) {
                            var item = prescript!.medicineData![index];

                            return MedicineCard(
                                smallTitle: prescript!.prescriptStatus ==
                                        PrescriptStatus.done
                                    ? "صرف"
                                    : "لم تصرف",
                                name: item["name"],
                                weight: item["size"],
                                duration: item["duration"],
                                description: item["discription"]);
                          },
                        )
                      : emptyLottieList()),
              _continer(
                  Column(
                    children: [
                      Row(
                        children: [
                          shadowCircleAvatar(AssetPaths.emptyIMG),
                          const SizedBox(width: 15),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                CustomText(
                                  text: prescript?.clinicName ?? "لا يوجد",
                                  textType: 2,
                                  fontWeight: FontWeight.w700,
                                  color: Colors.white,
                                ),
                                const SizedBox(height: 10),
                                iconAndWidget(FontAwesomeIcons.phone,
                                    iconColor: AppColors.lightenWhiteColor,
                                    iconSize: 12,
                                    widget: CustomText(
                                        text: prescript?.clinicPhoneNumber ??
                                            "لايوجد",
                                        textType: 5,
                                        color: AppColors.lightenWhiteColor)),
                                const SizedBox(height: 5),
                                iconAndWidget(FontAwesomeIcons.solidClock,
                                    iconColor: AppColors.lightenWhiteColor,
                                    iconSize: 12,
                                    widget: CustomText(
                                        text: prescript?.startWorking == null
                                            ? "غير معروف"
                                            : getRangeTime(
                                                start: prescript!.startWorking!,
                                                end: prescript!.endWorking!),
                                        textType: 5,
                                        color: AppColors.lightenWhiteColor)),
                                const SizedBox(height: 5),
                                iconAndWidget(FontAwesomeIcons.mapLocation,
                                    iconColor: AppColors.lightenWhiteColor,
                                    iconSize: 12,
                                    crossAlign: CrossAxisAlignment.start,
                                    widget: Expanded(
                                      child: CustomText(
                                          align: TextAlign.start,
                                          text: prescript?.clinicAddress ??
                                              "غير معروف",
                                          textType: 5,
                                          color: AppColors.lightenWhiteColor),
                                    )),
                              ],
                            ),
                          )
                        ],
                      ),
                      const Divider(
                          color: AppColors.lightenWhiteColor, height: 30),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          shadowCircleAvatar(AssetPaths.logoIcon,
                              radius: 10, isNetwork: false),
                          const SizedBox(width: 10),
                          const CustomText(
                            text: "http:www.com",
                            color: Colors.white,
                            textType: 4,
                          )
                        ],
                      )
                    ],
                  ),
                  borderRadius:
                      const BorderRadius.vertical(bottom: Radius.circular(10)),
                  paddingBottom: true),

              const SizedBox(height: 50)
            ]));
  }

  Widget _continer(Widget child,
      {BorderRadiusGeometry? borderRadius, bool? paddingBottom = false}) {
    return Container(
      clipBehavior: Clip.hardEdge,
      decoration: BoxDecoration(
          borderRadius: borderRadius ??
              const BorderRadius.vertical(top: Radius.circular(10)),
          color: Colors.transparent),
      child: Stack(
        children: [
          overlayImag(AssetPaths.drawer),
          Container(
            padding: paddingBottom == false
                ? const EdgeInsets.fromLTRB(15, 15, 15, 0)
                : const EdgeInsets.all(15),
            child: child,
          )
        ],
      ),
    );
  }
}
