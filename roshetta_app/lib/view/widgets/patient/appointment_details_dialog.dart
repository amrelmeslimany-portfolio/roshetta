import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:roshetta_app/core/functions/date_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class AppointmentDetails extends StatelessWidget {
  final Map item;
  final Function() onDelete;
  final Function() onEdit;
  const AppointmentDetails(
      {super.key,
      required this.item,
      required this.onDelete,
      required this.onEdit});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        item["appoint_case"] == "0"
            ? Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  ICButton(
                          onPressed: onDelete,
                          padding: const EdgeInsets.all(4),
                          iconColor: AppColors.primaryColor,
                          size: 20,
                          icon: Icons.delete)
                      .bordered,
                  shadowCircleAvatar(item["logo"] ?? AssetPaths.emptyIMG,
                      radius: 40),
                  ICButton(
                          onPressed: onEdit,
                          size: 20,
                          padding: const EdgeInsets.all(4),
                          iconColor: AppColors.primaryColor,
                          icon: Icons.edit)
                      .bordered,
                ],
              )
            : shadowCircleAvatar(item["logo"] ?? AssetPaths.emptyIMG,
                radius: 40),
        const SizedBox(height: 10),
        Text(item["name"],
            style: const TextStyle(
                color: AppColors.primaryColor,
                fontSize: 18,
                fontWeight: FontWeight.w700)),
        CustomText(
            text: item["specialist"],
            textType: 5,
            color: AppColors.lightTextColor),
        const SizedBox(height: 15),
        Container(
          width: double.infinity,
          decoration: shadowBoxWhite,
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const CustomText(
                  text: "الحجز", color: AppColors.lightTextColor, textType: 3),
              const SizedBox(width: 10),
              CustomText(
                  text: item["appoint_date"],
                  color: AppColors.primaryTextColor,
                  textType: 3),
            ],
          ),
        ),
        const SizedBox(height: 10),
        Container(
          width: double.infinity,
          decoration: shadowBoxWhite,
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const CustomText(
                  text: "المواعيد",
                  color: AppColors.lightTextColor,
                  textType: 3),
              const SizedBox(width: 10),
              CustomText(
                  text: getRangeTime(
                      start: item["start_working"], end: item["end_working"]),
                  color: AppColors.primaryTextColor,
                  textType: 3),
            ],
          ),
        ),
        const SizedBox(height: 10),
        Container(
          width: double.infinity,
          decoration: shadowBoxWhite,
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const CustomText(
                  text: "رقم العياده",
                  color: AppColors.lightTextColor,
                  textType: 3),
              const SizedBox(width: 10),
              CustomText(
                  text: item["phone_number"],
                  color: AppColors.primaryTextColor,
                  textType: 3),
            ],
          ),
        ),
        const SizedBox(height: 10),
        Container(
          width: double.infinity,
          decoration: shadowBoxWhite,
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const CustomText(
                  text: "العنوان",
                  color: AppColors.lightTextColor,
                  textType: 3),
              const SizedBox(width: 10),
              Expanded(
                child: CustomText(
                    text: item["address"],
                    color: AppColors.primaryTextColor,
                    textType: 3),
              ),
            ],
          ),
        ),
        const SizedBox(height: 10),
        TextButton(
            onPressed: () {
              Get.back();
            },
            style: const ButtonStyle(
                backgroundColor:
                    MaterialStatePropertyAll(AppColors.primaryAColor),
                padding: MaterialStatePropertyAll(
                    EdgeInsets.symmetric(horizontal: 10, vertical: 5))),
            child: const Text("غلق"))
      ],
    );
  }
}
