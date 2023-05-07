import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';

class DividerText extends StatelessWidget {
  final String text;
  final double? width;
  final Color? color;
  final double? size;
  const DividerText(
      {super.key, required this.text, this.width, this.color, this.size});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: width ?? 310,
      child: Text(
        text,
        style: Theme.of(context).textTheme.bodyLarge!.copyWith(
            color: color ?? AppColors.greyColor, fontSize: size ?? 16),
      ),
    );
  }
}
