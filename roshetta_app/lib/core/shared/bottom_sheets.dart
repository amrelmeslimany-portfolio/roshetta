import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';

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

  Container sheet(List<Widget> children,
      {double? height = 200,
      String? buttonText = "ارسال",
      RequestStatus? isLoading = RequestStatus.none,
      Function()? onSubmit}) {
    return Container(
      height: height,
      padding: const EdgeInsets.fromLTRB(15, 15, 15, 10),
      decoration: const BoxDecoration(
          color: AppColors.whiteColor,
          borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.center,
        mainAxisSize: MainAxisSize.min,
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(15, 5, 15, 15),
            child: Container(
              alignment: Alignment.center,
              width: Get.width / 6,
              height: 8,
              decoration: const BoxDecoration(
                  color: AppColors.lightenWhiteColor,
                  borderRadius: BorderRadius.all(Radius.circular(10))),
            ),
          ),
          Expanded(
            child: ListView(
              shrinkWrap: true,
              children: [
                ...children,
                if (onSubmit != null) const SizedBox(height: 15)
              ],
            ),
          ),
          if (onSubmit != null && isLoading != RequestStatus.loading)
            BGButton(Get.context!,
                    text: buttonText!, small: true, onPressed: onSubmit)
                .button
        ],
      ),
    );
  }

  Text header(String text) {
    return Text(
      text,
      textAlign: TextAlign.center,
      style: const TextStyle(color: AppColors.greyColor, fontSize: 18),
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
