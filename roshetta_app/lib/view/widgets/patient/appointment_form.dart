import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class PatientAppointmentForm extends StatelessWidget {
  final RequestStatus status;
  final TextEditingController appointController;
  final String name;
  final String? buttonText;

  final Function() onSubmit;
  const PatientAppointmentForm(
      {super.key,
      required this.status,
      required this.name,
      required this.appointController,
      required this.onSubmit,
      this.buttonText});

  @override
  Widget build(BuildContext context) {
    return CustomBottomSheets().sheet([
      CustomRequest(
          sameContent: true,
          status: status,
          widget: Column(
            children: [
              Text(name,
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                      fontSize: 22, fontWeight: FontWeight.w700)),
              const CustomText(
                  text: "قم باختيار تاريخ الحجز",
                  color: AppColors.lightTextColor,
                  textType: 3),
              const SizedBox(height: 15),
              UnconstrainedBox(
                child: SizedBox(
                  width: 320,
                  child: CustomTextField(
                          context: context,
                          onTap: () async {
                            onShowDatePicker(context);
                          },
                          readOnly: true,
                          onValidator: (value) => fieldValidor(value!),
                          controller: appointController,
                          hintText: "تاريخ الحجز",
                          icon: FontAwesomeIcons.solidCalendarPlus)
                      .textfield,
                ),
              ),
              const SizedBox(height: 15),
              UnconstrainedBox(
                  child: BGButton(context,
                          text: buttonText ?? "حجز", onPressed: onSubmit)
                      .button)
            ],
          ))
    ], height: 220);
  }

  onShowDatePicker(BuildContext context) async {
    DateTime? date = await customDatePicker(context,
        initialTime: DateTime.now().add(const Duration(days: 1)),
        first: DateTime.now().subtract(const Duration(days: 0)),
        last: DateTime(2500),
        initialMode: DatePickerMode.day);
    appointController.text =
        date != null ? DateFormat("yyyy-MM-dd").format(date) : "";
  }
}
