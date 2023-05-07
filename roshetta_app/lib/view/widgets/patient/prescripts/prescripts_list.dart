import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/date_functions.dart';
import 'package:roshetta_app/view/widgets/patient/prescripts/prescript_status.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class PrescriptsList extends StatelessWidget {
  final String name;
  final List prescripts;
  final Function(String value) onPrescriptPress;
  const PrescriptsList(
      {super.key,
      required this.prescripts,
      required this.onPrescriptPress,
      required this.name});

  @override
  Widget build(BuildContext context) {
    return ListView.separated(
      separatorBuilder: (context, index) => const SizedBox(height: 15),
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: prescripts.length,
      itemBuilder: (context, index) {
        var item = prescripts[index];

        return CustomListTile(
          mediaHeight: 70,
          onTilePressed: () {
            onPrescriptPress(item["prescript_id"]);
          },
          widget: Center(
            child: getDiffernceDays(item["created_date"]) != 0
                ? Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const CustomText(
                          text: "منذ",
                          color: AppColors.primaryColor,
                          textType: 4),
                      const SizedBox(height: 7),
                      Text(getDiffernceDays(item["created_date"]),
                          softWrap: false,
                          overflow: TextOverflow.ellipsis,
                          style: const TextStyle(
                              color: AppColors.primaryColor,
                              fontSize: 22,
                              height: 0.8,
                              fontWeight: FontWeight.bold)),
                      const CustomText(
                          text: "ايام",
                          color: AppColors.primaryColor,
                          textType: 4),
                    ],
                  )
                : const Text("اليوم",
                    style: TextStyle(
                        color: AppColors.primaryColor,
                        fontSize: 20,
                        fontWeight: FontWeight.bold)),
          ),
          smallTitle: item["ser_id"],
          title: item[name],
          middleWidget: PrescriptStatusBadge(status: item["prescriptStatus"]),
          descriptionIcon: FontAwesomeIcons.clock,
          description: item["created_date"],
          descriptionColor: AppColors.primaryTextColor.withOpacity(0.8),
        );
      },
    );
  }
}
