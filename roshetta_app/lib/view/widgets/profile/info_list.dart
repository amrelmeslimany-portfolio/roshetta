import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/view/widgets/custom_texts.dart';

class ProfileInfoList extends StatelessWidget {
  final User? user;
  const ProfileInfoList({super.key, this.user});

  @override
  Widget build(BuildContext context) {
    if (user == null) return const Text("مشكله في المعلومات");
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10),
      child: Column(
        children: [
          patientHeight(user?.height),
          const SizedBox(height: 15),
          patientWeight(user?.weight),
          const SizedBox(height: 15),
          item(
            icon: FontAwesomeIcons.phone,
            title: "رقم الهاتف",
            text: user?.phoneNumber ?? "غير متوفر رقم هاتف",
          ),
          const SizedBox(height: 15),
          item(
            icon: FontAwesomeIcons.solidEnvelope,
            title: "البريد الإلكترونى",
            text: user?.email ?? "غير متوفر بريد",
          ),
          const SizedBox(height: 15),
          item(
            icon: FontAwesomeIcons.mapLocationDot,
            title: "العنوان",
            text: user?.governorate ?? "غير متوفر عنوان",
          ),
          const SizedBox(height: 15),
        ],
      ),
    );
  }

  Widget item({
    required IconData icon,
    required String title,
    required String text,
  }) {
    return iconAndWidget(icon,
        space: 15,
        iconColor: AppColors.lightTextColor,
        iconSize: 24,
        widget: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CustomText(
              text: title,
              color: AppColors.lightTextColor,
              textType: 5,
            ),
            const SizedBox(height: 5),
            CustomText(
              text: text,
              color: AppColors.primaryTextColor,
              textType: 2,
            ),
          ],
        ));
  }

  Widget patientHeight(String? text) {
    if (text == null) {
      return const SizedBox();
    } else {
      return item(
          icon: FontAwesomeIcons.ruler, title: 'طولك (سم)', text: "$text (سم)");
    }
  }

  Widget patientWeight(String? text) {
    if (text == null) {
      return const SizedBox();
    } else {
      return item(
          icon: FontAwesomeIcons.weightScale,
          title: 'وزنك (كجم)',
          text: "$text (كجم)");
    }
  }
}
