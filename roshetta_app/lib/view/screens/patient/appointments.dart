import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/controllers/patient/appointments_controllers.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/auth/label_divider.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/patient/appointment_details_dialog.dart';
import 'package:roshetta_app/view/widgets/patient/appointment_form.dart';
import 'package:roshetta_app/view/widgets/shared/floating_button.dart';

class PatientAppointments extends StatelessWidget {
  PatientAppointments({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final appointmentController =
      Get.put(PatientAppointmentsController(), permanent: false);

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      onRefresh: () async {
        await appointmentController.getAppointments();
      },
      floatingButton: CustomFloatingIcon(
        icon: Icons.add_alarm,
        onPressed: () => Get.offNamed(AppRoutes.patientClinics),
      ),
      body: BodyLayout(
          appbar: CustomAppBar(onPressed: () {
            toggleDrawer(scaffoldKey);
          }).init,
          content: [
            Obx(() {
              return Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      const Expanded(
                        child: HeaderContent(
                            header: "الحجوزات",
                            spacer: 5,
                            content: CustomText(
                              text: "عند الضغط علي الحجز سيعرض التفاصيل",
                              textType: 3,
                              color: AppColors.lightTextColor,
                              align: TextAlign.start,
                            )),
                      ),
                      const SizedBox(width: 15),
                      Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 8, vertical: 4),
                        decoration: const BoxDecoration(
                          borderRadius: BorderRadius.all(Radius.circular(100)),
                          color: AppColors.primaryColor,
                        ),
                        child: Text(
                          handleNumbers(appointmentController
                                  .appointmentsPending.length +
                              appointmentController.appointmentsDone.length),
                          style: const TextStyle(color: Colors.white),
                        ),
                      ),
                      const SizedBox(width: 8),
                    ],
                  ),
                  const SizedBox(height: 20),
                  Container(
                    margin: const EdgeInsets.symmetric(horizontal: 8),
                    child: CustomRequest(
                        sameContent: false,
                        status: appointmentController.appointmentStatus.value,
                        widget: Column(
                          children: [
                            const DividerText(text: "في الانتظار"),
                            const SizedBox(height: 15),
                            _emptyList(
                                appointmentController.appointmentsPending,
                                ListView.separated(
                                  separatorBuilder: (context, index) {
                                    return const SizedBox(height: 15);
                                  },
                                  shrinkWrap: true,
                                  physics: const NeverScrollableScrollPhysics(),
                                  itemCount: appointmentController
                                      .appointmentsPending.length,
                                  itemBuilder: (context, index) {
                                    var item = appointmentController
                                        .appointmentsPending[index];

                                    return Obx(() => CustomRequest(
                                        sameContent: true,
                                        status: appointmentController
                                            .appointmentItemStatus.value,
                                        widget: _listtileItem(item)));
                                  },
                                )),
                            const SizedBox(height: 30),
                            const DividerText(text: "منتهية"),
                            const SizedBox(height: 15),
                            _emptyList(
                                appointmentController.appointmentsDone,
                                ListView.separated(
                                  separatorBuilder: (context, index) {
                                    return const SizedBox(height: 15);
                                  },
                                  shrinkWrap: true,
                                  physics: const NeverScrollableScrollPhysics(),
                                  itemCount: appointmentController
                                      .appointmentsDone.length,
                                  itemBuilder: (context, index) {
                                    var item = appointmentController
                                        .appointmentsDone[index];

                                    return _listtileItem(item);
                                  },
                                ))
                          ],
                        )),
                  ),
                ],
              );
            }),
            const SizedBox(height: 50)
          ]),
    );
  }

  CustomListTile _listtileItem(item) {
    return CustomListTile(
      onTilePressed: () {
        _displayDetails(item);
      },
      img: item["logo"] ?? AssetPaths.emptyIMG,
      title: item["appoint_date"],
      smallTitle: item["name"],
      descriptionIcon: FontAwesomeIcons.phone,
      description: item["phone_number"],
      descriptionColor: AppColors.primaryTextColor.withOpacity(0.8),
      buttonIcon: checkOpenStatus(item["appoint_case"], Icons.more_horiz, null),
      onButtonPressed: () {
        if (item["appoint_case"] == "0") {
          _handleAppointment(item);
        }
      },
    );
  }

  _handleAppointment(item) {
    CustomBottomSheets.custom(text: item["appoint_date"], items: [
      ButtonSheetItem(
          icon: Icons.edit_calendar_sharp,
          text: "تعديل الحجز",
          onTap: () {
            _handleOnEdit(item);
          }),
      ButtonSheetItem(
          icon: Icons.delete_outline_rounded,
          text: "حذف الحجز",
          onTap: () {
            appointmentController.onDeleteAppointment(item["appointment_id"]);
          }),
    ]);
  }

  Widget _emptyList(List list, Widget widget) {
    if (list.isNotEmpty) {
      return widget;
    } else {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.center,
        mainAxisSize: MainAxisSize.min,
        children: [
          Lottie.asset(AssetPaths.empty, height: 150, repeat: false),
          const SizedBox(height: 5),
          const CustomText(
              text: "لا يوجد ", textType: 3, color: AppColors.lightTextColor),
        ],
      );
    }
  }

  _handleOnEdit(item) {
    if (Get.isBottomSheetOpen == true) {
      Get.back();
    }
    appointmentController.appointDate.text = item["appoint_date"];
    Get.bottomSheet(Obx(() => PatientAppointmentForm(
        status: appointmentController.appointmentItemStatus.value,
        name: item["name"],
        appointController: appointmentController.appointDate,
        onSubmit: () {
          appointmentController.submitEditAppointment(item["appointment_id"]);
        })));
  }

  _displayDetails(item) {
    Get.defaultDialog(
      contentPadding: const EdgeInsets.fromLTRB(20, 20, 20, 0),
      content: AppointmentDetails(
          item: item,
          onDelete: () {
            if (Get.isDialogOpen == true) {
              Get.back();
            }
            appointmentController.onDeleteAppointment(item["appointment_id"]);
          },
          onEdit: () {
            if (Get.isDialogOpen == true) {
              Get.back();
            }
            _handleOnEdit(item);
          }),
    );
  }
}
