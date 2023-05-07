import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/class/fields_interface.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/profile/header.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class DoctorAssistantContent extends StatelessWidget {
  final Map? assistant;
  final TextEditingController idController;
  final GlobalKey<FormState> formKey;
  const DoctorAssistantContent(
      {super.key,
      this.assistant,
      required this.idController,
      required this.formKey});

  @override
  Widget build(BuildContext context) {
    if (assistant == null || assistant!.isEmpty) {
      return Column(
        children: [
          iconAvatar(Icons.person_add_alt_1_rounded, size: 50),
          const SizedBox(height: 15),
          const CustomText(
                  text: "لا يوجد لديك مساعد", color: AppColors.primaryColor)
              .subHeader(context),
          const SizedBox(
            width: 250,
            child: CustomText(
                text: "من فضلك قم باضافه مساعد بإدخال رقم المعرف الخاص به (ID)",
                color: AppColors.lightTextColor,
                textType: 5),
          ),
          const SizedBox(height: 15),
          Form(
              key: formKey,
              child: CustomTextField(
                      controller: idController,
                      context: context,
                      hintText: "رقم المعرف",
                      keyboardType: TextInputType.number,
                      onValidator: (value) =>
                          fieldValidor(value!, type: FieldsTypes.number),
                      icon: FontAwesomeIcons.idCard)
                  .textfield)
        ],
      );
    } else {
      return Column(
        children: [
          const HeaderContent(
              header: "المساعد",
              spacer: 0,
              content: CustomText(
                text: "يمكنك اضافة او حذف المساعد من هذة الصفحة",
                color: AppColors.lightTextColor,
                align: TextAlign.start,
                textType: 3,
              )),
          const SizedBox(height: 15),
          ProfileHeader(
              title: assistant?["name"] ?? "لا يوجد",
              subTitle: assistant?["phone_number"] ?? "لا يوجد",
              icon: FontAwesomeIcons.phone,
              image: assistant?["profile_img"] ?? AssetPaths.emptyIMG)
        ],
      );
    }
  }
}
