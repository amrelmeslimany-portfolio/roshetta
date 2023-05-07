import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';

class ButtonSheetItem {
  late IconData icon;
  late String text;
  late Function()? onTap;
  ButtonSheetItem(
      {required this.icon, required this.text, required this.onTap});
}

class CustomBottomSheets {
  CustomBottomSheets();

  CustomBottomSheets.uploadImages(
      {Function()? onCamera, Function()? onGellary, String? title}) {
    Get.bottomSheet(sheet([
      header(title ?? "قم باختيار صورة"),
      const SizedBox(height: 15),
      buttonItem(icon: Icons.camera, text: "الكاميرا", onTap: onCamera),
      buttonItem(icon: Icons.image, text: "معرض الصور", onTap: onGellary),
    ]));
  }
  CustomBottomSheets.custom(
      {required String text,
      required List<ButtonSheetItem> items,
      double? height = 200}) {
    Get.bottomSheet(sheet([
      header(text),
      const SizedBox(height: 15),
      ...items
          .map((ButtonSheetItem item) =>
              buttonItem(icon: item.icon, text: item.text, onTap: item.onTap))
          .toList()
    ], height: height));
  }

  Container sheet(List<Widget> children, {double? height = 200}) {
    return Container(
      height: height,
      padding: const EdgeInsets.all(15),
      decoration: const BoxDecoration(
          color: AppColors.whiteColor,
          borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      child: ListView(
        children: children,
      ),
    );
  }

  Text header(String text) {
    return Text(
      text,
      textAlign: TextAlign.center,
      style: const TextStyle(color: AppColors.lightTextColor, fontSize: 18),
    );
  }

  ListTile buttonItem(
      {Function()? onTap, required IconData icon, required String text}) {
    return ListTile(
      onTap: onTap,
      minLeadingWidth: double.minPositive,
      leading: Icon(icon, size: 24, color: AppColors.primaryTextColor),
      title: Text(text,
          style:
              const TextStyle(fontSize: 16, color: AppColors.primaryTextColor)),
    );
  }
}
