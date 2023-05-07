import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class ProfileInfoList extends StatelessWidget {
  // final User? user;
  final String? email;
  final String? phone;
  final String? governorate;
  final String? address;
  final String? height;
  final String? weight;
  final String? specialist;
  const ProfileInfoList(
      {super.key,
      this.email,
      this.phone,
      this.governorate,
      this.address,
      this.height,
      this.weight,
      this.specialist});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10),
      child: Column(
        children: [
          ...checkWeightHeight(),
          specialist != null
              ? item(
                  icon: FontAwesomeIcons.solidEnvelope,
                  title: "التخصص",
                  text: specialist ?? "غير معروف",
                )
              : const SizedBox(),
          SizedBox(height: specialist != null ? 15 : 0),
          item(
            icon: FontAwesomeIcons.phone,
            title: "رقم الهاتف",
            text: phone ?? "غير متوفر رقم هاتف",
          ),
          const SizedBox(height: 15),
          email != null
              ? item(
                  icon: FontAwesomeIcons.solidEnvelope,
                  title: "البريد الإلكترونى",
                  text: email ?? "غير متوفر بريد",
                )
              : const SizedBox(),
          SizedBox(height: email != null ? 15 : 0),
          item(
            icon: FontAwesomeIcons.mapLocationDot,
            title: "العنوان",
            text: address != null ? "$governorate - $address" : governorate!,
          )
        ],
      ),
    );
  }

  List<Widget> checkWeightHeight() {
    if (height != null && weight != null) {
      return [
        patientHeight(height),
        const SizedBox(height: 15),
        patientWeight(weight),
        const SizedBox(height: 15)
      ];
    } else {
      return [const SizedBox()];
    }
  }

  Widget patientHeight(String? text) {
    if (text == null) {
      return const SizedBox();
    } else {
      return item(
          icon: FontAwesomeIcons.ruler,
          title: 'الطول (سم)',
          text: text,
          suffix: "سم");
    }
  }

  Widget patientWeight(String? text) {
    if (text == null) {
      return const SizedBox();
    } else {
      return item(
          icon: FontAwesomeIcons.weightScale,
          title: 'الوزن (كجم)',
          text: text,
          suffix: "كجم");
    }
  }

  Widget item(
      {required IconData icon,
      required String title,
      required String text,
      String? suffix}) {
    return iconAndWidget(icon,
        space: 15,
        iconColor: AppColors.lightTextColor,
        crossAlign: CrossAxisAlignment.start,
        iconSize: 24,
        widget: Flexible(
          flex: 1,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              CustomText(
                text: title,
                color: AppColors.lightTextColor,
                textType: 5,
              ),
              const SizedBox(height: 5),
              suffix != null
                  ? Row(
                      children: [
                        CustomText(
                          text: text,
                          fontWeight: FontWeight.bold,
                          color: AppColors.primaryTextColor,
                          textType: 2,
                        ),
                        const SizedBox(width: 5),
                        CustomText(
                          text: suffix,
                          color: AppColors.primaryTextColor,
                          textType: 5,
                        )
                      ],
                    )
                  : CustomText(
                      text: text,
                      align: TextAlign.start,
                      color: AppColors.primaryTextColor,
                      textType: 2,
                    ),
            ],
          ),
        ));
  }
}
