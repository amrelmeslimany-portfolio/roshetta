import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';

import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/home/link_item.dart';

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
        children: (StaticData.drawerLinks[Users.patient.name] as List)
            .map((e) => LinkItem(
                onTap: () => Get.toNamed(e["page"]),
                icon: e["icon"],
                text: e["title"]))
            .toList(),
      );

  Widget doctor(BuildContext context) => Column(
        children: (StaticData.drawerLinks[Users.doctor.name] as List)
            .map((e) => LinkItem(
                onTap: () => Get.toNamed(e["page"]),
                icon: e["icon"],
                text: e["title"]))
            .toList(),
      );

  Widget pharmacist(BuildContext context) => Column(
      children: (StaticData.drawerLinks[Users.pharmacist.name] as List)
          .map((e) => LinkItem(
              onTap: () => Get.toNamed(e["page"]),
              icon: e["icon"],
              text: e["title"]))
          .toList());
}
