import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

import '../home/header_content.dart';

class HeaderBadge extends StatelessWidget {
  final String header;
  final String? description;
  final String badgeText;
  final Color? badgeColor;
  final Color? badgeTextColor;
  final bool? isSmallText;
  const HeaderBadge(
      {super.key,
      required this.header,
      required this.badgeText,
      this.description,
      this.badgeColor,
      this.badgeTextColor,
      this.isSmallText = false});

  @override
  Widget build(BuildContext context) {
    if (isSmallText == true) {
      return Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          CustomText(
              text: header,
              color: badgeTextColor ?? AppColors.lightTextColor,
              textType: 3),
          CustomBadge(
            badgeText: badgeText,
            fontSize: 12,
          )
        ],
      );
    }
    return Row(
      children: [
        Expanded(
          child: HeaderContent(
              header: header,
              spacer: 5,
              content: description != null
                  ? CustomText(
                      text: description!,
                      textType: 3,
                      color: AppColors.lightTextColor,
                      align: TextAlign.start,
                    )
                  : Container()),
        ),
        const SizedBox(width: 15),
        //  Badge(text: badgeText , color: ),
        CustomBadge(
            badgeText: badgeText,
            badgeColor: badgeColor,
            badgeTextColor: badgeTextColor),

        const SizedBox(width: 8),
      ],
    );
  }
}

class CustomBadge extends StatelessWidget {
  final String badgeText;
  final Color? badgeColor;
  final Color? badgeTextColor;
  final double? fontSize;

  const CustomBadge(
      {super.key,
      required this.badgeText,
      this.badgeColor,
      this.badgeTextColor,
      this.fontSize});

  @override
  Widget build(BuildContext context) {
    return Badge(
      label: Text(badgeText),
      backgroundColor: badgeColor ?? AppColors.primaryColor,
      textColor: badgeTextColor,
      textStyle: TextStyle(fontSize: fontSize ?? 14, fontFamily: "Cairo"),
      largeSize: 27,
      padding: const EdgeInsets.symmetric(horizontal: 8),
    );
  }
}
