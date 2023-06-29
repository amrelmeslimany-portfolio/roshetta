import 'package:flutter/material.dart';
import 'package:get/get_connect/http/src/utils/utils.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';

class TextSearchField extends StatelessWidget {
  final String? placeholder;
  final TextEditingController controller;
  final Function() onSearch;
  const TextSearchField(
      {super.key,
      required this.controller,
      required this.onSearch,
      this.placeholder = "ادخل اسم او رقم المريض"});

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        CustomTextField(
            context: context,
            controller: controller,
            onFieldSubmitted: (_) {
              onSearch();
            },
            hintText: placeholder!,
            icon: Icons.search_outlined,
            onValidator: (value) => validateField(value!)).textfield,
        Align(
            alignment: Alignment.centerLeft,
            child: ICButton(
                    onPressed: onSearch,
                    icon: Icons.search,
                    color: AppColors.primaryColor,
                    size: 21,
                    padding: const EdgeInsets.all(13.5))
                .init)
      ],
    );
  }
}
