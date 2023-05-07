import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';

abstract class Fields {
  final String hintText;
  final BuildContext context;
  String? Function(String?)? onValidator;

  Fields({required this.context, required this.hintText, this.onValidator});
}

class CustomTextField extends Fields {
  final IconData icon;
  final TextEditingController controller;
  TextInputType keyboardType;
  bool? secure;
  Function()? passwordTap;
  Function()? onTap;
  Function(String)? onFieldSubmitted;
  bool? readOnly;
  String? initialValue;

  CustomTextField(
      {required this.controller,
      required super.context,
      required super.hintText,
      required super.onValidator,
      required this.icon,
      this.keyboardType = TextInputType.text,
      this.passwordTap,
      this.onFieldSubmitted,
      this.onTap,
      this.readOnly,
      this.initialValue,
      this.secure});

  SizedBox get textfield {
    FaIcon iconWidget = FaIcon(
      icon,
      color: AppColors.lightTextColor,
      size: onTap == null ? 16 : 20,
    );
    return SizedBox(
      width: 320,
      child: TextFormField(
        readOnly: readOnly ?? false,
        onTap: onTap,
        initialValue: initialValue,
        validator: onValidator,
        keyboardType: keyboardType,
        onFieldSubmitted: onFieldSubmitted,
        controller: controller,
        obscureText: (secure == null || secure == false) ? false : true,
        style: Theme.of(context).textTheme.bodyLarge,
        decoration: InputDecoration(
            fillColor: AppColors.whiteColor,
            filled: true,
            errorStyle: Theme.of(context)
                .textTheme
                .bodyMedium
                ?.copyWith(color: Colors.red[900]),
            suffixIcon: Flex(
                direction: Axis.vertical,
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  (secure == null)
                      ? iconWidget
                      : InkWell(
                          onTap: passwordTap,
                          child: iconWidget,
                        )
                ]),
            hintText: hintText,
            hintStyle: Theme.of(context)
                .textTheme
                .bodyLarge
                ?.copyWith(color: AppColors.lightTextColor),
            contentPadding:
                const EdgeInsets.symmetric(vertical: 5, horizontal: 18),
            border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(50),
                borderSide: const BorderSide(color: AppColors.lightTextColor)),
            focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(50),
                borderSide: const BorderSide(color: AppColors.primaryColor))),
      ),
    );
  }
}

class CustomDropdown extends Fields {
  Function(String?)? onChange;
  List<DropdownMenuItem<String>> items;
  String? initalVal;
  CustomDropdown(
      {required super.context,
      required super.hintText,
      required super.onValidator,
      required this.items,
      this.initalVal,
      this.onChange});

  SizedBox get dropdown {
    return SizedBox(
      width: 320,
      child: DropdownButtonFormField(
          value: initalVal,
          validator: onValidator,
          elevation: 1,
          isExpanded: true,
          borderRadius: BorderRadius.circular(20),
          iconSize: 26,
          icon: const FaIcon(
            FontAwesomeIcons.caretDown,
            color: AppColors.lightTextColor,
            size: 18,
          ),
          style: Theme.of(context).textTheme.bodyLarge,
          decoration: InputDecoration(
              fillColor: AppColors.whiteColor,
              filled: true,
              errorStyle: Theme.of(context)
                  .textTheme
                  .bodyMedium
                  ?.copyWith(color: Colors.red[900]),
              hintStyle: Theme.of(context)
                  .textTheme
                  .bodyLarge
                  ?.copyWith(color: AppColors.lightTextColor),
              hintText: hintText,
              contentPadding:
                  const EdgeInsets.symmetric(vertical: 0, horizontal: 16),
              border: const OutlineInputBorder(
                  borderSide: BorderSide(color: AppColors.lightTextColor),
                  borderRadius: BorderRadius.all(Radius.circular(50))),
              focusedBorder: const OutlineInputBorder(
                  borderRadius: BorderRadius.all(Radius.circular(50)),
                  borderSide: BorderSide(color: AppColors.primaryColor))),
          items: items,
          onChanged: onChange),
    );
  }
}

class CustomRadio extends Fields {
  final String groupValue;
  final String value;
  final bool? isBorder;
  final void Function(String?) onChange;
  CustomRadio({
    required super.context,
    required super.hintText,
    required this.value,
    required this.onChange,
    required this.groupValue,
    this.isBorder,
  });

  GestureDetector get radio {
    return GestureDetector(
      onTap: () {
        onChange(value);
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 15, vertical: 7),
        decoration: BoxDecoration(
            border: isBorder == null
                ? Border.all(color: AppColors.lightTextColor)
                : null,
            color: AppColors.whiteColor,
            borderRadius: BorderRadius.circular(50)),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              hintText,
              style: Theme.of(context).textTheme.bodyLarge,
            ),
            Radio(
              value: value,
              groupValue: groupValue,
              onChanged: onChange,
              activeColor: AppColors.primaryColor,
              splashRadius: 0,
              visualDensity: const VisualDensity(
                  horizontal: VisualDensity.minimumDensity,
                  vertical: VisualDensity.minimumDensity),
            )
          ],
        ),
      ),
    );
  }
}

class UploadImageCircle {
  final Container imgWidgt;
  final Function() onUpload;

  UploadImageCircle({required this.imgWidgt, required this.onUpload});

  Widget get defaultField => Stack(
        alignment: Alignment.bottomRight,
        children: [
          imgWidgt,
          ICButton(
                  onPressed: onUpload,
                  icon: Icons.add_a_photo,
                  color: AppColors.primaryColor,
                  padding: const EdgeInsets.all(10),
                  iconColor: AppColors.whiteColor)
              .bordered
        ],
      );
}
