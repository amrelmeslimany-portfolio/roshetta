import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/widgets/auth/auth_dialogs.dart';

// Calender
Future<DateTime?> customDatePicker(BuildContext context) async {
  return await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
      locale: const Locale("ar"),
      initialDatePickerMode: DatePickerMode.year,
      helpText: "اختر تاريخ الميلاد",
      cancelText: "غلق",
      confirmText: "اختر",
      initialEntryMode: DatePickerEntryMode.calendarOnly);
}

// Password
IconData passwordVisibleIcon(bool isVisible) {
  if (isVisible == true) {
    return FontAwesomeIcons.solidEye;
  } else {
    return FontAwesomeIcons.solidEyeSlash;
  }
}

// Overlays

Positioned overlayImag(String img) => Positioned.fill(
        child: ShaderMask(
      blendMode: BlendMode.srcATop,
      shaderCallback: (bound) {
        return LinearGradient(
            transform: const GradientRotation(1.5),
            stops: const [
              0.2,
              0.7
            ],
            colors: [
              const Color.fromARGB(255, 31, 149, 94).withOpacity(0.78),
              const Color.fromARGB(255, 17, 96, 59).withOpacity(0.88)
            ]).createShader(bound);
      },
      child: Image.asset(img, fit: BoxFit.cover),
    ));

// Avatars
Container shadowCircleAvatar(String img,
        {List<BoxShadow>? shadow,
        Color? color,
        double? radius,
        bool isNetwork = true}) =>
    Container(
      decoration: BoxDecoration(
          shape: BoxShape.circle,
          boxShadow: shadow ??
              [
                BoxShadow(
                    color: Colors.black.withOpacity(0.1),
                    spreadRadius: 2,
                    blurRadius: 8)
              ]),
      child: CircleAvatar(
        backgroundColor: color ?? AppColors.whiteColor,
        radius: radius ?? 45,
        child: ClipOval(
            child: isNetwork
                ? CachedNetworkImage(
                    imageUrl: img,
                    fit: BoxFit.cover,
                    width: double.maxFinite,
                    height: double.maxFinite,
                  )
                : Image.asset(
                    img,
                    fit: BoxFit.cover,
                    width: double.maxFinite,
                    height: double.maxFinite,
                  )),
      ),
    );

CircleAvatar iconAvatar(IconData icon, {Color? color, double? size}) =>
    CircleAvatar(
      backgroundColor: color ?? AppColors.primaryAColor,
      radius: size ?? 22,
      child: FaIcon(icon, size: size ?? 22),
    );

// Alerts
confirmDialog(BuildContext context,
    {required String text,
    required void Function() onConfirm,
    Function()? onCancle,
    String? title}) {
  Get.defaultDialog(
    content: AuthDiologs(
        icon: FontAwesomeIcons.triangleExclamation,
        title: title,
        content: text),
    contentPadding: const EdgeInsets.all(15),
    barrierDismissible: true,
    confirm: BGButton(context, text: "نعم", minWidth: 80, onPressed: onConfirm)
        .button,
    cancel: BorderedButton(context,
            text: "لا", minWidth: 80, onPressed: onCancle ?? () => Get.back())
        .button,
  );
}

successDialog(BuildContext context,
    {required String content,
    required void Function() onSuccess,
    String? title,
    IconData? icon,
    String? buttonText}) {
  Get.defaultDialog(
      barrierDismissible: false,
      onWillPop: () {
        return Future.value(false);
      },
      content: AuthDiologs(
          icon: icon ?? FontAwesomeIcons.check,
          title: title ?? "تم بنجاح",
          content: content),
      confirm:
          BGButton(context, text: buttonText ?? "موافق", onPressed: onSuccess)
              .button);
}

snackbar({String? title, String? content}) {
  Get.snackbar(title ?? "تم بنجاح", content ?? "تم تسجيل الدخول بنجاح",
      margin: const EdgeInsets.all(15),
      snackPosition: SnackPosition.BOTTOM,
      backgroundColor: AppColors.primaryAColor);
}

// Components
Row iconAndWidget(IconData icon,
    {required Widget widget,
    double iconSize = 15,
    Color? iconColor,
    double space = 7.5,
    MainAxisAlignment? mainAlign,
    CrossAxisAlignment? crossAlign,
    bool reverse = false}) {
  return Row(
    crossAxisAlignment: crossAlign ?? CrossAxisAlignment.center,
    mainAxisAlignment: mainAlign ?? MainAxisAlignment.start,
    children: isReveresed(reverse, [
      FaIcon(
        icon,
        color: iconColor ?? AppColors.lightenWhiteColor,
        size: iconSize,
      ),
      SizedBox(width: space),
      widget,
    ]),
  );
}

isReveresed(bool reverse, List<Widget> children) {
  if (reverse) {
    return children.reversed.toList();
  } else {
    return children;
  }
}
