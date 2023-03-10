import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';

class Cirlce {
  double? size;
  double? opacity;
  double? rotation;

  Cirlce({this.opacity = 0.8, this.rotation = 90, this.size = 200});

  Opacity get circle {
    return Opacity(
      opacity: opacity!,
      child: Container(
        width: size,
        height: size,
        decoration: BoxDecoration(
            gradient: LinearGradient(colors: const [
              AppColors.primaryColor,
              AppColors.hoveredPrimaryColor
            ], transform: GradientRotation(rotation!)),
            shape: BoxShape.circle),
      ),
    );
  }
}
