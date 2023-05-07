import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class AuthDiologs extends StatelessWidget {
  final IconData icon;
  final String? title;
  final dynamic content;
  const AuthDiologs({super.key, required this.icon, this.title, this.content});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        CircleAvatar(
          backgroundColor: AppColors.primaryAColor,
          radius: 40,
          child: FaIcon(
            icon,
            color: AppColors.primaryColor,
            size: 35,
          ),
        ),
        const SizedBox(height: 15),
        title != null
            ? Text(
                title!,
                style: Theme.of(context)
                    .textTheme
                    .titleLarge!
                    .copyWith(color: AppColors.primaryColor),
              )
            : const SizedBox(),
        handleContent()
      ],
    );
  }

  handleContent() {
    if (content == null) return const SizedBox();
    if (content is Map) {
      List<Widget> widgets = [];
      content.forEach((key, text) {
        if (text.isEmpty) {
          return;
        } else {
          widgets.add(ListTile(
              leading: const Text(
                "\u2022",
                style: TextStyle(color: AppColors.lightTextColor, fontSize: 25),
              ),
              minLeadingWidth: double.minPositive,
              contentPadding: EdgeInsets.zero,
              title: CustomText(
                text: "$text",
                color: AppColors.greyColor,
                align: TextAlign.start,
                textType: 2,
              )));
        }
      });

      return Column(children: widgets);
    } else {
      return SizedBox(
        width: 230,
        child: CustomText(
          text: content,
          color: AppColors.greyColor,
          align: TextAlign.center,
          textType: 2,
        ),
      );
    }
  }
}
