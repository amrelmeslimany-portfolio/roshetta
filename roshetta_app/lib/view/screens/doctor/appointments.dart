import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/doctor/appointments_controllers.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
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

class DoctorAppointments extends StatelessWidget {
  DoctorAppointments({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final appointmentController =
      Get.put(DoctorAppointmentsController(), permanent: false);

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      onRefresh: () async => await appointmentController.getAppointments(),
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
                    Obx(() {
                      if (appointmentController.appointmentStatus.value ==
                          RequestStatus.loading) {
                        return const CircularProgressIndicator(
                          color: AppColors.whiteColor,
                        );
                      }
                      return FilterAppointment(
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
                      );
                    }),
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
                      description: "عند الضغط علي الحجز سيعرض التفاصيل",
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
                                  Map argumanets = {
                                    "appointment_id": item["appointment_id"],
                                    "patient_id": item["patient_id"],
                                    "clinic_id":
                                        appointmentController.clinicId.value,
                                  };
                                  Get.toNamed(AppRoutes.doctorPatient,
                                      arguments: argumanets);
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
}
