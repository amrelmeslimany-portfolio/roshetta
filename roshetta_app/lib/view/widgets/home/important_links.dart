import 'package:flutter/widgets.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';

import 'package:roshetta_app/core/shared/custom_buttons.dart';

class ImportantLinks extends StatelessWidget {
  final String role;
  const ImportantLinks({super.key, required this.role});

  @override
  Widget build(BuildContext context) {
    switch (role) {
      case "doctor":
        return doctor(context);
      case "pharmacist":
        return pharmacist(context);
      case "error":
        return Center(child: Lottie.asset(AssetPaths.error, width: 50));
      default:
        return patient(context);
    }
  }

  Widget patient(BuildContext context) => Column(
        children: [
          Row(
            children: [
              Expanded(
                child: BorderedButton(context,
                    text: "العيادات",
                    icon: FontAwesomeIcons.hospital, onPressed: () {
                  Get.toNamed(AppRoutes.patientClinics);
                }).button,
              ),
              const SizedBox(width: 5),
              Expanded(
                child: BorderedButton(context,
                    text: "الصيدليات",
                    icon: FontAwesomeIcons.houseMedical, onPressed: () {
                  Get.toNamed(AppRoutes.patientPharmacys);
                }).button,
              ),
            ],
          ),
          const SizedBox(height: 5),
          Row(
            children: [
              Expanded(
                child: BorderedButton(context,
                    text: "الروشتات",
                    icon: FontAwesomeIcons.receipt, onPressed: () {
                  Get.toNamed(AppRoutes.patientPrescripts);
                }).button,
              ),
              const SizedBox(width: 5),
              Expanded(
                child: BorderedButton(context,
                    text: "الامراض",
                    icon: FontAwesomeIcons.disease, onPressed: () {
                  Get.toNamed(AppRoutes.patientDiseases);
                }).button,
              ),
            ],
          )
        ],
      );

  Widget doctor(BuildContext context) => Column(
        children: [
          Row(
            children: [
              Expanded(
                child: BorderedButton(context,
                    text: "العيادات",
                    icon: FontAwesomeIcons.hospital, onPressed: () {
                  Get.toNamed(AppRoutes.doctorClinics);
                }).button,
              ),
              const SizedBox(width: 5),
              Expanded(
                child: BorderedButton(context,
                    text: "اضافة عياده",
                    icon: FontAwesomeIcons.circlePlus, onPressed: () {
                  Get.toNamed(AppRoutes.doctorAddClinic);
                }).button,
              ),
            ],
          )
        ],
      );

  Widget pharmacist(BuildContext context) => Column(
        children: [
          Row(
            children: [
              Expanded(
                child: BorderedButton(context,
                    text: "الصيدليات",
                    icon: FontAwesomeIcons.houseMedical, onPressed: () {
                  Get.toNamed(AppRoutes.pharmacistPharmacys);
                }).button,
              ),
              const SizedBox(width: 5),
              Expanded(
                child: BorderedButton(context,
                    text: "اضافه صيدلية",
                    icon: FontAwesomeIcons.circlePlus, onPressed: () {
                  Get.toNamed(AppRoutes.addPharmacy);
                }).button,
              ),
            ],
          ),
          const SizedBox(height: 5),
          Row(
            children: [
              Expanded(
                child: BorderedButton(context,
                    text: "صرف روشته",
                    icon: FontAwesomeIcons.receipt, onPressed: () {
                  Get.toNamed(AppRoutes.pharmacySellPrescript);
                }).button,
              ),
            ],
          )
        ],
      );
}
