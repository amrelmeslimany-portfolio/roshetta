import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';

class Notes {
  final IconData icon;
  final String text;
  final String buttonText;
  final Function() onTap;

  Notes(
      {required this.buttonText,
      required this.icon,
      required this.text,
      required this.onTap});

  Container get init => Container(
        padding: const EdgeInsets.all(10),
        decoration: shadowBoxWhite,
        child: Row(
          children: [
            iconAvatar(icon),
            const SizedBox(width: 10),
            Expanded(
              child: Text(text,
                  style: const TextStyle(color: AppColors.primaryTextColor)),
            ),
            const SizedBox(width: 10),
            InkWell(
              onTap: onTap,
              child: Container(
                padding:
                    const EdgeInsets.symmetric(vertical: 5, horizontal: 10),
                decoration: BoxDecoration(
                    color: AppColors.primaryColor,
                    borderRadius: BorderRadius.circular(50)),
                child: Text(
                  buttonText,
                  style: const TextStyle(
                      color: AppColors.whiteColor, fontWeight: FontWeight.w600),
                ),
              ),
            )
          ],
        ),
      );
}
