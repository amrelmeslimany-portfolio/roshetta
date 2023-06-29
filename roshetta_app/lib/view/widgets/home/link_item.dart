import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';

class LinkItem extends StatelessWidget {
  final void Function() onTap;
  final IconData icon;
  final String text;
  const LinkItem(
      {super.key, required this.onTap, required this.icon, required this.text});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        decoration: shadowBoxWhite,
        margin: const EdgeInsets.only(bottom: 15),
        padding: const EdgeInsets.all(10),
        child: Row(
          children: [
            iconAvatar(icon, size: 32),
            const SizedBox(width: 15),
            Expanded(
                child: Text(
              text,
              overflow: TextOverflow.ellipsis,
              softWrap: false,
              style: const TextStyle(
                  fontSize: 19,
                  fontWeight: FontWeight.w600,
                  color: AppColors.primaryTextColor),
            )),
            const Icon(
              Icons.arrow_right,
              color: AppColors.primaryColor,
              size: 35,
            )
          ],
        ),
      ),
    );
  }
}
