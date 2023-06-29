import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/patient/prescripts_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/patient/prescripts/prescripts_list.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class PatientPrescripts extends StatelessWidget {
  PatientPrescripts({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final prescriptsController = Get.find<PatientPrescriptsController>();

  @override
  Widget build(BuildContext context) {
    prescriptsController.getPrescripts();
    return HomeLayout(
        scaffoldKey: scaffoldKey,
        onRefresh: () async {
          await prescriptsController.getPrescripts();
        },
        body: BodyLayout(
            appbar: CustomAppBar(onPressed: () {
              toggleDrawer(scaffoldKey);
            }).init,
            content: [
              Obx(() => HeaderBadge(
                  header: "الروشتات",
                  badgeText:
                      handleNumbers(prescriptsController.prescripts.length),
                  description: "عند الضغط علي الروشته سيعرض التفاصيل")),
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 8),
                child: Obx(() {
                  return CustomRequest(
                    sameContent: false,
                    status: prescriptsController.prescriptStatus.value,
                    widget: Column(
                      children: [
                        const SizedBox(height: 15),
                        checkArgument("disease_name") != null &&
                                !prescriptsController.isAll.value
                            ? Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Expanded(
                                    child: CustomText(
                                        text:
                                            "روشتات المرض : ${checkArgument("disease_name")}",
                                        color: AppColors.lightTextColor,
                                        align: TextAlign.start,
                                        textType: 5),
                                  ),
                                  const SizedBox(width: 10),
                                  TextButton(
                                      onPressed: () {
                                        prescriptsController.changeIsAll(true);
                                        prescriptsController.getPrescripts(
                                            isAll: true);
                                      },
                                      child: const Text("كل الروشتات"))
                                ],
                              )
                            : Container(),
                        SizedBox(
                            height:
                                checkArgument("disease_name") != null ? 15 : 0),
                        prescriptsController.prescripts.isNotEmpty
                            ? PrescriptsList(
                                prescripts: prescriptsController.prescripts,
                                name: "disease_name",
                                onPrescriptPress: (value) {
                                  prescriptsController
                                      .getPrescriptDetails(value);
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
