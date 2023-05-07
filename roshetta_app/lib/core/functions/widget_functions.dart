import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/widgets/auth/auth_dialogs.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:time_range_picker/time_range_picker.dart';

// Calender
Future<DateTime?> customDatePicker(BuildContext context,
    {DateTime? initialTime,
    DateTime? first,
    DateTime? last,
    DatePickerMode? initialMode}) async {
  return await showDatePicker(
      context: context,
      initialDate: initialTime ?? DateTime.now(),
      firstDate: first ?? DateTime(1900),
      lastDate: last ?? DateTime.now(),
      locale: const Locale("ar"),
      initialDatePickerMode: initialMode ?? DatePickerMode.year,
      helpText: "اختر تاريخ الميلاد",
      cancelText: "غلق",
      confirmText: "اختر",
      initialEntryMode: DatePickerEntryMode.calendarOnly);
}

// Timer
customTimePicker(BuildContext context, {TimeOfDay? initTime}) async {
  return await showTimePicker(
      context: context,
      initialTime: initTime ?? TimeOfDay.now(),
      cancelText: "اغلاق",
      confirmText: "اختر",
      helpText: "قم باختيار الوقت",
      initialEntryMode: TimePickerEntryMode.dialOnly);
}

customTimeRangePicker({TimeOfDay? start, TimeOfDay? end}) async {
  return await showTimeRangePicker(
    context: Get.context!,
    use24HourFormat: false,
    start: start,
    end: end,
    minDuration: const Duration(hours: 2),
    maxDuration: const Duration(hours: 18),
    interval: const Duration(minutes: 30),
    fromText: "من",
    toText: "الى",
    snap: true,
  );
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

void dialogLoading() {
  Get.defaultDialog(
      barrierDismissible: false,
      contentPadding: const EdgeInsets.only(top: 15),
      content: Lottie.asset(AssetPaths.loading, width: 80));
}

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
        BoxBorder? border,
        bool isNetwork = true}) =>
    Container(
      decoration: BoxDecoration(
          border: border,
          shape: BoxShape.circle,
          boxShadow: shadow ??
              [
                BoxShadow(
                    color: Colors.black.withOpacity(0.08),
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
                    errorWidget: (context, url, error) =>
                        Image.network(AssetPaths.emptyIMG, fit: BoxFit.cover),
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

CircleAvatar iconAvatar(IconData icon,
        {Color? color, Color? iconColor, double? size}) =>
    CircleAvatar(
      backgroundColor: color ?? AppColors.primaryAColor,
      radius: size ?? 22,
      child: FaIcon(
        icon,
        size: size ?? 22,
        color: iconColor ?? AppColors.primaryColor,
      ),
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
    confirm: BGButton(context,
            text: "نعم", small: true, minWidth: 80, onPressed: onConfirm)
        .button,
    cancel: BorderedButton(context,
            text: "لا",
            minWidth: 80,
            small: true,
            onPressed: onCancle ?? () => Get.back())
        .button,
  );
}

successDialog(BuildContext context,
    {required String content,
    required void Function() onSuccess,
    String? title,
    IconData? icon,
    bool? isBack = false,
    String? buttonText}) {
  Get.defaultDialog(
      barrierDismissible: isBack!,
      onWillPop: isBack
          ? null
          : () {
              return Future.value(false);
            },
      content: AuthDiologs(
          icon: icon ?? FontAwesomeIcons.check,
          title: title ?? "تم بنجاح",
          content: content),
      confirm: BGButton(context,
              text: buttonText ?? "موافق", small: true, onPressed: onSuccess)
          .button);
}

snackbar({String? title, String? content, Color? color}) {
  Get.snackbar(title ?? "تم بنجاح", content ?? "تم تسجيل الدخول بنجاح",
      margin: const EdgeInsets.all(15),
      snackPosition: SnackPosition.BOTTOM,
      backgroundColor: color?.withOpacity(0.65) ??
          AppColors.primaryAColor.withOpacity(0.65));
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

Column emptyLottieList({String? text = "لا يوجد "}) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.center,
    mainAxisAlignment: MainAxisAlignment.center,
    mainAxisSize: MainAxisSize.min,
    children: [
      Lottie.asset(AssetPaths.empty, height: 150, repeat: false),
      const SizedBox(height: 5),
      CustomText(text: text!, textType: 3, color: AppColors.lightTextColor),
    ],
  );
}
