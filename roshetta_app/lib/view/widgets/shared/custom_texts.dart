import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';

class CustomText extends StatelessWidget {
  final String text;
  final int textType;
  final TextAlign align;
  final Color? color;
  final FontWeight? fontWeight;
  final bool? wrap;

  const CustomText(
      {super.key,
      required this.text,
      this.color,
      this.fontWeight = FontWeight.w500,
      this.textType = 1,
      this.align = TextAlign.center,
      this.wrap = true});

  @override
  Widget build(BuildContext context) {
    switch (textType) {
      case 2:
        return Text(text,
            textAlign: align,
            softWrap: wrap,
            style: Theme.of(context)
                .textTheme
                .titleSmall
                ?.copyWith(color: color, fontWeight: fontWeight));
      case 3:
        return Text(text,
            textAlign: align,
            softWrap: wrap,
            style: Theme.of(context)
                .textTheme
                .bodyLarge
                ?.copyWith(color: color, fontWeight: fontWeight));
      case 5:
        return Text(text,
            textAlign: align,
            softWrap: wrap,
            style: Theme.of(context)
                .textTheme
                .bodyMedium
                ?.copyWith(color: color, fontWeight: fontWeight));
      case 4:
        return Text(text,
            textAlign: align,
            softWrap: wrap,
            style: Theme.of(context)
                .textTheme
                .bodySmall
                ?.copyWith(color: color, fontWeight: fontWeight));
      default:
        return Text(text,
            textAlign: align,
            softWrap: wrap,
            style: Theme.of(context)
                .textTheme
                .headlineLarge
                ?.copyWith(color: color, fontWeight: fontWeight));
    }
  }

  Text subHeader(BuildContext context) {
    return Text(
      text,
      textAlign: align,
      style: Theme.of(context)
          .textTheme
          .titleMedium!
          .copyWith(color: color ?? AppColors.primaryTextColor),
    );
  }

  SizedBox truncateText(BuildContext context,
          {TextStyle? style,
          double? width = double.maxFinite,
          int? maxLines = 1}) =>
      SizedBox(
          width: width,
          child: Text(
            text,
            textAlign: align,
            overflow: TextOverflow.ellipsis,
            softWrap: false,
            maxLines: maxLines,
            style: style ??
                Theme.of(context).textTheme.titleSmall!.copyWith(
                    color: color ?? AppColors.whiteColor,
                    fontWeight: fontWeight ?? FontWeight.w800),
          ));

  RichText copyrightText(BuildContext context) {
    return RichText(
        textAlign: TextAlign.center,
        text: TextSpan(children: [
          TextSpan(
              text: "برمجه فريق ©",
              style: Theme.of(context)
                  .textTheme
                  .bodySmall
                  ?.copyWith(color: AppColors.lightTextColor)),
          TextSpan(
              text: " روشتة ",
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: AppColors.primaryColor, fontWeight: FontWeight.w900)),
          TextSpan(
              text: DateTime.now().year.toString(),
              style: Theme.of(context)
                  .textTheme
                  .bodySmall
                  ?.copyWith(color: AppColors.lightTextColor))
        ]));
  }
}
