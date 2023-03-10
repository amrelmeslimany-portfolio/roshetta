import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/view/widgets/custom_texts.dart';

class ProfileHeader extends StatelessWidget {
  final LocalUser user;
  final Function()? onSettings;
  final bool? isVerified;
  const ProfileHeader(
      {super.key,
      required this.user,
      this.onSettings,
      this.isVerified = false});

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
                        size: 30,
                        iconColor: AppColors.primaryColor)
                    .bordered,
              )
            : const SizedBox(),
        shadowCircleAvatar(user.image!, radius: 60),
        const SizedBox(height: 15),
        verifiedName(context),
        const SizedBox(height: 5),
        iconAndWidget(FontAwesomeIcons.idCard,
            iconColor: AppColors.lightTextColor,
            iconSize: 16,
            widget: CustomText(
              text: user.ssd!,
              color: AppColors.lightTextColor,
              textType: 3,
            ),
            mainAlign: MainAxisAlignment.center),
      ],
    );
  }

  Text _displayName(BuildContext context) => Text(
        user.name!,
        textAlign: TextAlign.center,
        style: Theme.of(context).textTheme.titleLarge!.copyWith(
            color: AppColors.primaryColor, fontWeight: FontWeight.w800),
      );

  Widget verifiedName(BuildContext context) {
    if (isVerified != null && isVerified!) {
      return iconAndWidget(Icons.verified,
          mainAlign: MainAxisAlignment.center,
          iconColor: Colors.blue,
          reverse: true,
          iconSize: 20,
          widget: _displayName(context));
    } else {
      return _displayName(context);
    }
  }
}
