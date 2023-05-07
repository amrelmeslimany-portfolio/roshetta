import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/assistant/appointments_controller.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/clinics/appointments/filter.dart';
import 'package:roshetta_app/view/widgets/clinics/appointments/filter_header.dart';
import 'package:roshetta_app/view/widgets/clinics/appointments/list.dart';
import 'package:roshetta_app/view/widgets/clinics/appointments/status_sheet.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class AssistantAppointments extends StatelessWidget {
  AssistantAppointments({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final appointmentController =
      Get.put(AssistantAppointmentsController(), permanent: false);

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      body: BodyLayout(
          appbar: CustomAppBar(
              onPressed: () {
                toggleDrawer(scaffoldKey);
              },
              children: Container(
                width: double.infinity,
                margin: const EdgeInsets.symmetric(horizontal: 8, vertical: 5),
                child: Column(
                  children: [
                    const CustomText(
                        text: "فلتر ", textType: 2, color: Colors.white),
                    const SizedBox(height: 10),
                    Obx(() => CustomRequest(
                        sameContent: true,
                        status: appointmentController.appointmentStatus.value,
                        widget: FilterAppointment(
                          searchController: appointmentController.search,
                          onWordSearch: () {
                            appointmentController.onFilterWord();
                          },
                          onFilterDate: () {
                            appointmentController.onFilterDate(context);
                          },
                          onStatusSheet: () {
                            _openFilterSheet(context);
                          },
                          onStatusChange: (choosed) {
                            appointmentController.onStatusChange(choosed);
                          },
                        ))),
                  ],
                ),
              )).init,
          content: [
            Obx(() {
              return Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  HeaderBadge(
                      header: "الحجوزات",
                      description: "عند الضغط علي الحجز ستظهر اعدادات الحجز ",
                      badgeText: handleNumbers(
                          appointmentController.appointments.length)),
                  const SizedBox(height: 20),
                  Container(
                    margin: const EdgeInsets.symmetric(horizontal: 8),
                    child: Column(
                      children: [
                        FilterAppointHeader(
                            onClearFilter: () async {
                              await appointmentController.onClearFilter();
                            },
                            defaultStatusFilter: "0",
                            statusFilter:
                                appointmentController.appointStatusFilter.value,
                            searchFilter: appointmentController.search.text,
                            dateFilter:
                                appointmentController.appointDateFilter.value),
                        const SizedBox(height: 15),
                        CustomRequest(
                            sameContent: false,
                            status:
                                appointmentController.appointmentStatus.value,
                            widget: AppointsList(
                                appointments:
                                    appointmentController.appointments,
                                onItemClick: (item) {
                                  if (item["appoint_case"] == "0") {
                                    _appointItemSettings(item);
                                  } else {
                                    if (Get.isSnackbarOpen) return;
                                    snackbar(
                                        color: Colors.red,
                                        title: "لا يوجد اعدادات",
                                        content:
                                            "لا يمكن التحكم في الموعد الا اذا كان حالته الانتظار فقط");
                                  }
                                }))
                      ],
                    ),
                  ),
                ],
              );
            }),
            const SizedBox(height: 50)
          ]),
    );
  }

  _openFilterSheet(context) {
    Get.bottomSheet(Obx(() => StatusSheet(
            appointStatus: appointmentController.appointmentStatus.value,
            onChange: (choosed) =>
                appointmentController.onStatusChange(choosed),
            statusValue: appointmentController.appointStatusFilter.value)
        .sheet()));
  }

  _appointItemSettings(Map item) {
    List<ButtonSheetItem> buttons = [
      ButtonSheetItem(
          icon: Icons.directions_walk,
          text: "ادخال للدكتور",
          onTap: () {
            appointmentController.onSubmitAppointStatus(item["appointment_id"]);
          })
    ];
    CustomBottomSheets.custom(text: item["name"], items: buttons);
  }
}
