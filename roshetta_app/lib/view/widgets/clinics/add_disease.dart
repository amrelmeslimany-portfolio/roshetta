import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/controllers/doctor/doctorpatient_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/view/widgets/auth/label_divider.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class AddDiseaseForm extends StatelessWidget {
  AddDiseaseForm({super.key});
  final controller = Get.find<DoctorPatientDetailsController>();
  final GlobalKey<FormState> diseaseForm = GlobalKey<FormState>();
  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const HeaderContent(
            header: "بيانات المرض",
            spacer: 5,
            content: CustomText(
              text: "يرجي ادخال البيانات الخاصه بالمرض",
              textType: 3,
              color: AppColors.lightTextColor,
              align: TextAlign.start,
            )),
        const SizedBox(height: 15),
        controller.formType.value == "new"
            ? Form(
                key: diseaseForm,
                child: Column(
                  children: [
                    CustomTextField(
                            context: context,
                            onValidator: (value) => fieldValidor(value!),
                            controller: controller.diseaseName,
                            hintText: "اسم المرض",
                            icon: FontAwesomeIcons.disease)
                        .textfield,
                    const SizedBox(height: 15),
                    CustomTextField(
                            context: context,
                            onValidator: (value) => fieldValidor(value!),
                            controller: controller.diseasePlace,
                            hintText: "مكان المرض",
                            icon: FontAwesomeIcons.child)
                        .textfield,
                    const SizedBox(height: 10),
                    const DividerText(
                        text: "يقصد به العضو في الجسم (الرأس, العين,..)",
                        size: 12,
                        color: AppColors.lightTextColor),
                    const SizedBox(height: 15),
                    CustomTextField(
                            readOnly: controller.patientIdTextField.text.isEmpty
                                ? false
                                : true,
                            context: context,
                            onValidator: (value) => fieldValidor(value!),
                            controller: controller.patientIdTextField,
                            hintText: "رقم المريض",
                            icon: FontAwesomeIcons.idCard)
                        .textfield,
                    const SizedBox(height: 15),
                    BGButton(context, text: "التالي", onPressed: () {
                      if (diseaseForm.currentState!.validate()) {
                        scrollToTop(controller.scrollController);
                        controller.changeCurrentForm(1);
                      }
                    }).button
                  ],
                ))
            : Column(
                children: [
                  Center(child: Lottie.asset(AssetPaths.error, height: 60)),
                  const SizedBox(height: 10),
                  const CustomText(
                    text: "لا يمكن الدخول هنا الا عند اضافة مرض جديد",
                    textType: 3,
                    color: AppColors.lightTextColor,
                  )
                ],
              ),
      ],
    );
  }
}
