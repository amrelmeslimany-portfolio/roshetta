import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/class/fields_interface.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';

class AccountTypeWidget extends StatelessWidget {
  final String accountType;
  final String? specialistValue;
  final TextEditingController patientWeight;
  final TextEditingController patientHeight;
  final List<DropdownMenuItem<String>> specialistsList;
  final Function(String?) onSpecialistChange;
  const AccountTypeWidget({
    super.key,
    required this.accountType,
    required this.specialistsList,
    required this.onSpecialistChange,
    required this.patientWeight,
    required this.patientHeight,
    this.specialistValue,
  });

  @override
  Widget build(BuildContext context) {
    if (accountType == enumToString(Users.doctor)) {
      return Column(
        children: [
          CustomDropdown(
              context: context,
              onValidator: (value) => dropdownValidator(value),
              hintText: "التخصص الطبي",
              initalVal: specialistValue!.isNotEmpty ? specialistValue : null,
              items: specialistsList,
              onChange: (value) {
                onSpecialistChange(value!);
              }).dropdown,
          const SizedBox(height: 15),
        ],
      );
    } else if (accountType == enumToString(Users.patient)) {
      return Column(
        children: [
          CustomTextField(
            context: context,
            onValidator: (value) => fieldValidor(value!,
                type: FieldsTypes.number, min: 5, max: 350),
            controller: patientWeight,
            hintText: "الوزن بال كيلوجرام",
            icon: FontAwesomeIcons.weightHanging,
            keyboardType: TextInputType.number,
          ).textfield,
          const SizedBox(height: 15),
          CustomTextField(
            context: context,
            onValidator: (value) => fieldValidor(value!,
                type: FieldsTypes.number, min: 50, max: 300),
            controller: patientHeight,
            hintText: "الطول بال سم",
            icon: FontAwesomeIcons.ruler,
            keyboardType: TextInputType.number,
          ).textfield,
          const SizedBox(height: 15),
        ],
      );
    }

    return const SizedBox.shrink();
  }
}
