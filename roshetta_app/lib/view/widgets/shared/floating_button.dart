import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';

class CustomFloatingBTN extends StatelessWidget {
  final IconData icon;
  final String text;
  final Function() onPressed;
  const CustomFloatingBTN(
      {super.key,
      required this.icon,
      required this.text,
      required this.onPressed});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 40,
      child: FloatingActionButton.extended(
        elevation: 3,
        extendedPadding: const EdgeInsets.all(10),
        label: Text(text,
            style: const TextStyle(
                color: Colors.white, fontWeight: FontWeight.w600)),
        icon: Icon(icon, size: 19, color: Colors.white),
        backgroundColor: AppColors.primaryColor,
        onPressed: onPressed,
      ),
    );
  }
}

class CustomFloatingIcon extends StatelessWidget {
  final IconData icon;
  final double? iconSize;
  final bool? isLoading;
  final Function() onPressed;
  const CustomFloatingIcon(
      {super.key,
      required this.icon,
      required this.onPressed,
      this.isLoading = false,
      this.iconSize = 25});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: 50,
      height: 50,
      child: FloatingActionButton(
          backgroundColor: AppColors.primaryColor,
          onPressed: onPressed,
          child: isLoading!
              ? const CircularProgressIndicator(color: Colors.white)
              : Icon(
                  icon,
                  color: AppColors.whiteColor,
                  size: iconSize,
                )),
    );
  }
}
