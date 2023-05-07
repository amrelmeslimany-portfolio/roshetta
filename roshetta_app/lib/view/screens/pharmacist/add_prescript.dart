import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/doctor/doctorpatient_controller.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class AddPrescript extends StatelessWidget {
  AddPrescript({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final patientController = Get.put(DoctorPatientDetailsController());

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
        scaffoldKey: scaffoldKey,
        body: BodyLayout(
            scrollController: patientController.scrollController,
            appbar: CustomAppBar(
              onPressed: () {
                toggleDrawer(scaffoldKey);
              },
              children: Container(
                padding: const EdgeInsets.symmetric(vertical: 15),
                width: double.infinity,
                child: Obx(() => Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const CustomText(
                          text: "اضافة روشته",
                          color: Colors.white,
                          align: TextAlign.start,
                        ).subHeader(context),
                        const SizedBox(height: 5),
                        CustomText(
                          text: patientController.currentForm.value == 0
                              ? "بيانات المرض"
                              : "بيانات الادوية",
                          color: Colors.white,
                          textType: 3,
                        ),
                        const SizedBox(height: 10),
                        Row(
                          children: List.generate(
                              patientController.formsWidgets.length,
                              (index) => Expanded(
                                    key: ValueKey(index),
                                    child: AnimatedContainer(
                                      duration:
                                          const Duration(milliseconds: 200),
                                      width: double.maxFinite,
                                      height: 4,
                                      margin: const EdgeInsets.symmetric(
                                          horizontal: 4),
                                      decoration: BoxDecoration(
                                          borderRadius: const BorderRadius.all(
                                              Radius.circular(10)),
                                          color: index ==
                                                  patientController
                                                      .currentForm.value
                                              ? Colors.white
                                              : Colors.white.withOpacity(0.5)),
                                    ),
                                  )).toList(),
                        ),
                      ],
                    )),
              ),
            ).init,
            content: [
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 8),
                child: Obx(
                  () => AnimatedSwitcher(
                    duration: const Duration(milliseconds: 200),
                    transitionBuilder: (child, animation) =>
                        FadeTransition(opacity: animation, child: child),
                    child: patientController
                        .formsWidgets[patientController.currentForm.value],
                  ),
                ),
              ),
              const SizedBox(height: 70)
            ]));
  }
}
