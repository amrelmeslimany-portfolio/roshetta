import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';

abstract class Buttons {
  final BuildContext context;
  final String text;
  final VoidCallback onPressed;

  Buttons(this.context, {required this.text, required this.onPressed});
}

class BGButton extends Buttons {
  Color? bgColor;
  Color? hoverdBgColor;
  Color? textColor;
  double? minWidth;
  IconData? icon;
  bool? small;

  BGButton(super.context,
      {required super.text,
      this.bgColor = AppColors.primaryColor,
      this.hoverdBgColor = AppColors.hoveredPrimaryColor,
      this.textColor = AppColors.whiteColor,
      this.minWidth = 250,
      this.icon,
      this.small = false,
      required super.onPressed});

  MaterialButton get button {
    return MaterialButton(
      onPressed: onPressed,
      minWidth: minWidth,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(100)),
      color: bgColor,
      highlightColor: hoverdBgColor,
      splashColor: Colors.transparent,
      highlightElevation: 0,
      elevation: 0,
      padding: EdgeInsets.symmetric(vertical: small == true ? 5 : 7),
      child: _child(),
    );
  }

  Widget _child() {
    if (icon != null) {
      return iconAndWidget(icon!,
          mainAlign: MainAxisAlignment.center,
          iconColor: textColor ?? AppColors.primaryColor,
          iconSize: small == true ? 17 : 20,
          widget: Text(
            text,
            style: Theme.of(context).textTheme.titleSmall?.copyWith(
                color: textColor, fontSize: small == true ? 16 : null),
          ));
    } else {
      return Text(
        text,
        style: Theme.of(context)
            .textTheme
            .titleSmall
            ?.copyWith(color: textColor, fontSize: small == true ? 16 : null),
      );
    }
  }
}

class BorderedButton extends Buttons {
  Color? borderColor;
  Color? hoverdBgColor;
  Color? textColor;
  IconData? icon;
  double? minWidth;
  bool? small;

  BorderedButton(super.context,
      {required super.text,
      this.borderColor = AppColors.primaryColor,
      this.hoverdBgColor = AppColors.primaryAColor,
      this.textColor = AppColors.primaryColor,
      this.icon,
      this.minWidth = 250,
      this.small = false,
      required super.onPressed});

  MaterialButton get button {
    return MaterialButton(
      onPressed: onPressed,
      minWidth: minWidth,
      shape: RoundedRectangleBorder(
          side: BorderSide(color: borderColor!),
          borderRadius: BorderRadius.circular(100)),
      highlightColor: hoverdBgColor,
      splashColor: Colors.transparent,
      highlightElevation: 0,
      elevation: 0,
      padding: EdgeInsets.symmetric(vertical: small == true ? 5 : 7),
      child: _child(),
    );
  }

  Widget _child() {
    if (icon != null) {
      return iconAndWidget(icon!,
          mainAlign: MainAxisAlignment.center,
          iconColor: textColor ?? AppColors.primaryColor,
          iconSize: small == true ? 17 : 20,
          widget: Text(
            text,
            style: Theme.of(context).textTheme.titleSmall?.copyWith(
                color: textColor, fontSize: small == true ? 16 : null),
          ));
    } else {
      return Text(
        text,
        style: Theme.of(context)
            .textTheme
            .titleSmall
            ?.copyWith(color: textColor, fontSize: small == true ? 16 : null),
      );
    }
  }
}

class ICButton {
  final Function() onPressed;
  final IconData icon;
  EdgeInsets? padding;
  Color? color;
  Color? iconColor;
  double? size;

  ICButton(
      {required this.onPressed,
      required this.icon,
      this.color,
      this.iconColor,
      this.size = 24,
      this.padding});

  Widget get init {
    return Container(
      decoration: BoxDecoration(shape: BoxShape.circle, color: color),
      child: IconButton(
          color: color,
          iconSize: size,
          padding: padding ?? EdgeInsets.zero,
          constraints: const BoxConstraints(),
          onPressed: onPressed,
          splashRadius: 20,
          splashColor: AppColors.primaryAColor,
          icon: FaIcon(
            icon,
            color: iconColor ?? AppColors.whiteColor,
          )),
    );
  }

  Widget get bordered => Container(
        decoration: BoxDecoration(
            color: color,
            shape: BoxShape.circle,
            border: Border.all(color: iconColor ?? AppColors.whiteColor)),
        child: init,
      );
}
