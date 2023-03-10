import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/view/widgets/custom_texts.dart';

class ProfileBottomBanner extends StatelessWidget {
  final User? user;
  const ProfileBottomBanner({super.key, this.user});

  @override
  Widget build(BuildContext context) {
    if (user == null) return const Text("لايوجد احصائيه");
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 15),
      margin: const EdgeInsets.symmetric(horizontal: 7.5),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        children: [
          Column(
            children: [
              const CustomText(
                text: "الحجوزات",
                color: AppColors.lightTextColor,
                textType: 5,
              ),
              const SizedBox(height: 5),
              CustomText(
                text: handleNumbers(user?.appointNumbers),
                color: AppColors.primaryTextColor,
                textType: 2,
              ),
            ],
          ),
          Container(
            color: AppColors.lightenWhiteColor,
            height: 40,
            width: 2,
          ),
          Column(
            children: [
              const CustomText(
                text: "الأمراض",
                color: AppColors.lightTextColor,
                textType: 5,
              ),
              const SizedBox(height: 5),
              CustomText(
                text: handleNumbers(user?.diseasesNumber),
                color: AppColors.primaryTextColor,
                textType: 2,
              ),
            ],
          ),
          Container(
            color: AppColors.lightenWhiteColor,
            height: 40,
            width: 2,
          ),
          Column(
            children: [
              const CustomText(
                text: "الروشتات",
                color: AppColors.lightTextColor,
                textType: 5,
              ),
              const SizedBox(height: 5),
              CustomText(
                text: handleNumbers(user?.prescriptsNumber),
                color: AppColors.primaryTextColor,
                textType: 2,
              ),
            ],
          ),
        ],
      ),
    );
  }
}
