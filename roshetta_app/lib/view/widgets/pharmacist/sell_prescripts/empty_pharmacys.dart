import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class EmptyPharmcys extends StatelessWidget {
  const EmptyPharmcys({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        emptyLottieList(text: "لا يوجد صيدليات"),
        const SizedBox(height: 10),
        const CustomText(
          text: "لا يمكن صرف روشته الا بتفعيل صيدلية علي الاقل",
          color: AppColors.greyColor,
          textType: 3,
        ),
        const SizedBox(height: 10),
        TextButton.icon(
          onPressed: () => Get.toNamed(AppRoutes.pharmacistPharmacys),
          label: const Text("الصيدليات", style: TextStyle(fontSize: 18)),
          style: const ButtonStyle(
            padding: MaterialStatePropertyAll(
              EdgeInsets.symmetric(vertical: 2, horizontal: 10),
            ),
          ),
          icon: const Icon(
            Icons.medical_information,
            size: 19,
          ),
        )
      ],
    );
  }
}
