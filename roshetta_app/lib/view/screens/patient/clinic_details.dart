import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/controllers/patient/clinics_controller.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/profile/banner.dart';
import 'package:roshetta_app/view/widgets/profile/bottom_banner.dart';
import 'package:roshetta_app/view/widgets/profile/header.dart';
import 'package:roshetta_app/view/widgets/profile/info_list.dart';

class PatientClinicDetails extends StatelessWidget {
  PatientClinicDetails({super.key});
  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final patientControllers = Get.find<PatientClinicsController>();
  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      floatingButton: Obx(() {
        if (patientControllers.clinic.value == null) {
          return Container();
        }
        return SizedBox(
          height: 50,
          width: 50,
          child: FloatingActionButton(
              onPressed: () {
                if (patientControllers.clinic.value!.appointCase == 1) {
                  Map item = {
                    "clinic_id": patientControllers.clinic.value!.id,
                    "name": patientControllers.clinic.value!.name,
                  };
                  patientControllers.addAppointment(context, item);
                } else {
                  Map item = {
                    "appoint_date":
                        patientControllers.clinic.value!.appointDate,
                    "name": patientControllers.clinic.value!.name,
                  };

                  patientControllers.displayAppointment(item);
                }
              },
              backgroundColor: AppColors.primaryColor,
              child: checkOpenStatus(
                  patientControllers.clinic.value!.appointCase.toString(),
                  const Icon(Icons.remove_red_eye,
                      color: Colors.white, size: 28),
                  const Icon(Icons.add_alarm_outlined,
                      color: Colors.white, size: 28))),
        );
      }),
      body: BodyLayout(
          appbar: CustomAppBar(onPressed: () {
            toggleDrawer(scaffoldKey);
          }).init,
          content: [
            Obx(() {
              if (patientControllers.clinic.value == null) {
                return Lottie.asset(AssetPaths.loading, height: 80);
              }

              return CustomRequest(
                  status: patientControllers.clinicDetailsStatus.value,
                  sameContent: false,
                  widget: Column(
                    children: [
                      ProfileHeader(
                        image: patientControllers.clinic.value?.logo ??
                            AssetPaths.emptyIMG,
                        title: patientControllers.clinic.value?.name ?? "name",
                        subTitle: formatCloseOrNot(
                            patientControllers.clinic.value!.status!),
                        icon: patientControllers.clinic.value!.status == "0"
                            ? Icons.close_rounded
                            : Icons.check_circle,
                        subTitleColor:
                            patientControllers.clinic.value!.status == "1"
                                ? AppColors.primaryColor
                                : null,
                      ),
                      const SizedBox(height: 30),
                      ProfileBanner(
                          clinic: patientControllers.clinic.value,
                          clinicType: Users.patient.name),
                      const SizedBox(height: 30),
                      ProfileInfoList(
                        address: patientControllers.clinic.value?.address ??
                            "address",
                        governorate:
                            patientControllers.clinic.value?.governorate ??
                                "info",
                        phone: patientControllers.clinic.value?.phoneNumber ??
                            "info",
                        specialist:
                            patientControllers.clinic.value?.specialist ??
                                "info",
                      ),
                      const SizedBox(height: 30),
                      ProfileBottomBanner(
                          clinic: patientControllers.clinic.value,
                          type: Users.patient.name),
                    ],
                  ));
            }),
            const SizedBox(height: 50)
          ]),
    );
  }
}
