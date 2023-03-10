import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';

class DividerText extends StatelessWidget {
  final String text;
  const DividerText({super.key, required this.text});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: 310,
      child: Text(
        text,
        style: Theme.of(context)
            .textTheme
            .bodyLarge!
            .copyWith(color: AppColors.greyColor),
      ),
    );
  }
}
