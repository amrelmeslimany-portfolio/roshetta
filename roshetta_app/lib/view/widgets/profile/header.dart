import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class ProfileHeader extends StatelessWidget {
  final String? image;
  final String title;
  final String subTitle;
  final IconData icon;
  final Color? subTitleColor;
  final String? isVerify;
  final Function()? onSettings;
  final Widget? bottomWidget;

  const ProfileHeader(
      {super.key,
      this.image,
      required this.title,
      required this.subTitle,
      required this.icon,
      this.onSettings,
      this.subTitleColor,
      this.isVerify,
      this.bottomWidget});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        onSettings != null
            ? Align(
                alignment: Alignment.centerLeft,
                child: ICButton(
                        onPressed: onSettings!,
                        padding: const EdgeInsets.all(4),
                        icon: Icons.more_horiz,
                        size: 25,
                        iconColor: AppColors.primaryColor)
                    .bordered,
              )
            : const SizedBox(height: 8),
        verifiedPicture(context),
        const SizedBox(height: 15),
        _displayName(context),
        const SizedBox(height: 5),
        iconAndWidget(icon,
            iconColor: subTitleColor ?? AppColors.lightTextColor,
            iconSize: 14,
            widget: CustomText(
              text: subTitle,
              color: subTitleColor ?? AppColors.lightTextColor,
              textType: 3,
            ),
            mainAlign: MainAxisAlignment.center),
        SizedBox(height: bottomWidget != null ? 10 : 0),
        bottomWidget ?? Container()
      ],
    );
  }

  Text _displayName(BuildContext context) => Text(
        title,
        textAlign: TextAlign.center,
        style: Theme.of(context).textTheme.titleLarge!.copyWith(
            color: AppColors.primaryColor, fontWeight: FontWeight.w800),
      );

  Widget verifiedPicture(BuildContext context,
      {double? radius = 70, double? pos = 20}) {
    if (isVerify != null && isVerify == "success") {
      return Stack(
        children: [
          shadowCircleAvatar(image ?? AssetPaths.emptyPerson,
              radius: radius, border: Border.all(color: Colors.blue, width: 3)),
          Positioned(
              right: pos,
              child: Container(
                  padding: const EdgeInsets.all(2.5),
                  decoration: const BoxDecoration(
                      shape: BoxShape.circle, color: Colors.blue),
                  child: Icon(Icons.verified,
                      color: AppColors.whiteColor, size: pos)))
        ],
      );
    } else {
      return shadowCircleAvatar(image ?? AssetPaths.emptyPerson,
          radius: radius);
    }
  }
}
