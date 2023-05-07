import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';

class MedicineCard extends StatelessWidget {
  final String smallTitle;
  final String name;
  final String weight;
  final String duration;
  final String description;
  final Function()? onDelete;
  const MedicineCard(
      {super.key,
      required this.smallTitle,
      required this.name,
      required this.weight,
      required this.duration,
      required this.description,
      this.onDelete});

  @override
  Widget build(BuildContext context) {
    return CustomListTile(
      smallTitle: smallTitle,
      title: name,
      widget: iconAvatar(FontAwesomeIcons.pills, size: 28),
      mediaColor: Colors.transparent,
      moreWidget: Column(
        children: [
          Row(
            children: [
              _customiconText(weight, FontAwesomeIcons.weightHanging),
              const SizedBox(width: 10),
              _customiconText(duration, FontAwesomeIcons.solidCalendar),
            ],
          ),
          const Divider(),
          const SizedBox(height: 5),
          iconAndWidget(FontAwesomeIcons.receipt,
              crossAlign: CrossAxisAlignment.start,
              widget: Expanded(
                child: Transform.translate(
                  offset: const Offset(0, -4),
                  child: Text(description,
                      style: const TextStyle(
                          color: AppColors.greyColor, fontSize: 15)),
                ),
              ),
              iconColor: AppColors.greyColor)
        ],
      ),
      buttonIcon: Icons.delete_outline_rounded,
      onButtonPressed: onDelete,
    );
  }

  _customiconText(String text, IconData icon) {
    return iconAndWidget(icon,
        iconSize: 14,
        widget: Text(text,
            style: const TextStyle(color: AppColors.greyColor, fontSize: 15)),
        iconColor: AppColors.greyColor);
  }
}
