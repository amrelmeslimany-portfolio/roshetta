import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/view/widgets/clinics/date_box.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class AppointsList extends StatelessWidget {
  final List appointments;
  final Function(Map) onItemClick;
  const AppointsList(
      {super.key, required this.appointments, required this.onItemClick});

  @override
  Widget build(BuildContext context) {
    return _emptyList(
        appointments,
        ListView.separated(
          separatorBuilder: (context, index) {
            return const SizedBox(height: 15);
          },
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          itemCount: appointments.length,
          itemBuilder: (context, index) {
            var item = appointments[index];

            return CustomListTile(
              onTilePressed: () => onItemClick(item),
              widget: DateBox(date: item["appoint_date"]),
              title: item["name"],
              smallTitle: item["phone_number"],
              descriptionIcon: FontAwesomeIcons.clock,
              description: item["appoint_date"],
              descriptionColor: AppColors.primaryTextColor.withOpacity(0.8),
              middleWidget: CustomBadge(
                badgeText: _getStatusStyle(item)["text"],
                badgeColor: _getStatusStyle(item)["color"],
                fontSize: 12,
                badgeTextColor: AppColors.primaryTextColor,
              ),
            );
          },
        ));
  }

  Widget _emptyList(List list, Widget widget) {
    if (list.isNotEmpty) {
      return widget;
    } else {
      return emptyLottieList();
    }
  }

  Map _getStatusStyle(item) {
    Map temp = {"text": checkAppointStatusWord(item["appoint_case"])};
    switch (item["appoint_case"]) {
      case "1":
        temp["color"] = Colors.yellow[50];
        break;
      case "2":
        temp["color"] = Colors.green[50];
        break;
      default:
        temp["color"] = Colors.red[50];
    }

    return temp;
  }
}

String checkAppointStatusWord(String status) {
  switch (status) {
    case "1":
      return "يكشف";
    case "2":
      return "منتهي";
    default:
      return "ينتظر";
  }
}
