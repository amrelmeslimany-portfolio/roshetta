import 'package:flutter/material.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/view/widgets/custom_texts.dart';

class ProfileBanner extends StatelessWidget {
  final User? user;
  const ProfileBanner({super.key, this.user});

  @override
  Widget build(BuildContext context) {
    if (user == null) return const Text("البانر فيه مشكله");
    return Container(
      decoration: shadowBoxWhite,
      padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 15),
      margin: const EdgeInsets.symmetric(horizontal: 7.5),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        children: [
          Column(
            children: [
              const CustomText(
                text: "الجنس",
                color: AppColors.lightTextColor,
                textType: 5,
              ),
              const SizedBox(height: 5),
              CustomText(
                text: handleGender(user?.gender ?? ""),
                color: AppColors.primaryTextColor,
                textType: 2,
              ),
            ],
          ),
          Column(
            children: [
              const CustomText(
                text: "تاريخ الميلاد",
                color: AppColors.lightTextColor,
                textType: 5,
              ),
              const SizedBox(height: 5),
              CustomText(
                text: user?.birthDate ?? "",
                color: AppColors.primaryTextColor,
                textType: 2,
              ),
            ],
          ),
          Column(
            children: [
              const CustomText(
                text: "نوع الحساب",
                color: AppColors.lightTextColor,
                textType: 5,
              ),
              const SizedBox(height: 5),
              CustomText(
                text: usersAR[user?.role] ?? "",
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

String handleGender(String gender) {
  switch (gender) {
    case "male":
      return "ذكر";
    case "female":
      return "مؤنث";
    default:
      return gender;
  }
}
