import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_notes.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class ChatHeader extends StatelessWidget {
  final String img;
  final int messagesCounter;
  const ChatHeader(
      {super.key, required this.messagesCounter, required this.img});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(15),
      decoration: _boxDecoration(),
      child: Column(
        children: [
          Row(
            children: [
              ICButton(
                      onPressed: () => Get.back(),
                      icon: Icons.arrow_back,
                      size: 26,
                      iconColor: AppColors.primaryColor)
                  .init,
              const SizedBox(width: 15),
              shadowCircleAvatar(img, radius: 22),
              const SizedBox(width: 10),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const CustomText(
                      text: "دردشة جماعية",
                      color: AppColors.primaryColor,
                      align: TextAlign.start,
                      fontWeight: FontWeight.w800,
                      textType: 2,
                    ),
                    const CustomText(
                      text: "دردشة جماعية للدكاتره لتبادل الخبرات",
                      align: TextAlign.start,
                    ).truncateText(context,
                        style: const TextStyle(
                          fontSize: 14,
                          color: AppColors.lightTextColor,
                        )),
                  ],
                ),
              ),
              const SizedBox(width: 15),
              CustomBadge(badgeText: _handleMessageNumber(), fontSize: 12)
            ],
          ),
          const Divider(),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 10),
            child: iconAndWidget(FontAwesomeIcons.circleExclamation,
                iconColor: AppColors.primaryColor,
                iconSize: 14,
                space: 20,
                widget: const CustomText(
                  text: "لحذف رسالتك قم بالضغط طويلا عليها",
                  align: TextAlign.start,
                  textType: 5,
                  color: AppColors.lightTextColor,
                )),
          )
        ],
      ),
    );
  }

  BoxDecoration _boxDecoration() {
    return BoxDecoration(
        color: Colors.white,
        borderRadius: const BorderRadius.vertical(bottom: Radius.circular(20)),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withOpacity(0.04),
              blurRadius: 15,
              offset: const Offset(0, 5),
              spreadRadius: 1)
        ]);
  }

  String _handleMessageNumber() {
    if (messagesCounter > 100) return "+100";
    return handleNumbers(messagesCounter);
  }
}
