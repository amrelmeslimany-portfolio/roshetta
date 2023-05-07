import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class DateBox extends StatelessWidget {
  final String date;
  final Color? boxColor;
  const DateBox({super.key, required this.date, this.boxColor});

  @override
  Widget build(BuildContext context) {
    List dateList = date.split("-");
    return Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Text(
          dateList[2],
          style: const TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.w900,
              color: AppColors.primaryColor),
        ),
        CustomText(
          text: "${dateList[0]}/${dateList[1]}",
          textType: 4,
          color: boxColor ?? AppColors.primaryColor,
        )
      ],
    );
  }
}
