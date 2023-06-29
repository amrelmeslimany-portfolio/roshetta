import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/patient/pharmacy_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class PatientPharmacys extends StatelessWidget {
  PatientPharmacys({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final patient = Get.find<PatientPharmacysController>();

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      onRefresh: () async {
        await patient.getPharmacys();
      },
      body: BodyLayout(
          appbar: CustomAppBar(
            onPressed: () {
              toggleDrawer(scaffoldKey);
            },
          ).init,
          content: [
            Obx(() {
              return Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  HeaderBadge(
                      header: "الصيدليات",
                      badgeText: handleNumbers(patient.pharmacys.length),
                      description: "سيتم عرض تفاصيلها عند الضغط عليها"),
                  const SizedBox(height: 15),
                  CustomRequest(
                      sameContent: false,
                      status: patient.pharmacysStatus.value,
                      widget: ListView.separated(
                        separatorBuilder: (context, index) {
                          return const SizedBox(height: 15);
                        },
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: patient.pharmacys.length,
                        itemBuilder: (context, index) {
                          var item = patient.pharmacys[index];

                          return CustomListTile(
                            onTilePressed: () {
                              patient.goToPharmacyDetails(item["pharmacy_id"]);
                            },
                            img: item["logo"] ?? AssetPaths.emptyIMG,
                            title: item["name"],
                            smallTitle: item["governorate"],
                            descriptionIcon: FontAwesomeIcons.phone,
                            description: item["phone_number"],
                            descriptionColor:
                                AppColors.primaryTextColor.withOpacity(0.8),
                            middleWidget: Text(
                              checkOpenStatus(
                                  item["status"], "مغلقة", "مفتوحة"),
                              style: TextStyle(
                                  fontSize: 12,
                                  color: checkOpenStatus(
                                      item["status"],
                                      AppColors.greyColor,
                                      AppColors.primaryColor)),
                            ),
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
