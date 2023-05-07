import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';

class CustomShadowBox extends StatelessWidget {
  final double? width;
  final double? padding;
  final bool? isBorder;
  final Color? color;
  final Color? borderColor;
  final Widget child;
  const CustomShadowBox(
      {super.key,
      required this.child,
      this.color = Colors.white,
      this.isBorder = false,
      this.width = 110,
      this.padding = 5,
      this.borderColor});

  @override
  Widget build(BuildContext context) {
    return Container(
      alignment: Alignment.center,
      decoration: BoxDecoration(
        color: color,
        borderRadius: const BorderRadius.all(Radius.circular(10)),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withOpacity(0.04),
              spreadRadius: 1.5,
              blurRadius: 4)
        ],
        border: isBorder!
            ? Border.all(color: borderColor ?? AppColors.primaryColor)
            : null,
      ),
      padding: EdgeInsets.all(padding!),
      width: width,
      child: child,
    );
  }
}
