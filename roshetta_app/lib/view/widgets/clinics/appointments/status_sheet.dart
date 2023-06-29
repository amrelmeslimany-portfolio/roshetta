import 'package:flutter/material.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';

class StatusSheet extends StatelessWidget {
  final RequestStatus appointStatus;
  final String statusValue;
  final Function(String) onChange;
  const StatusSheet(
      {super.key,
      required this.appointStatus,
      required this.onChange,
      required this.statusValue});

  @override
  Widget build(BuildContext context) {
    return CustomRequest(
        sameContent: true,
        status: appointStatus,
        widget: Column(
          children: [
            CustomBottomSheets().header("اختر حاله الحجز"),
            const SizedBox(height: 10),
            statusRadio(context, hint: "في الانتظار", value: "0"),
            const SizedBox(height: 5),
            statusRadio(context, hint: "في الكشف", value: "1"),
            const SizedBox(height: 5),
            statusRadio(context, hint: "تم الكشف", value: "2"),
          ],
        ));
  }

  Widget statusRadio(context, {String? hint, String? value}) {
    return CustomRadio(
            context: context,
            isBorder: false,
            hintText: hint!,
            value: value!,
            onChange: (choosed) {
              onChange(choosed!);
            },
            groupValue: statusValue)
        .radio;
  }

  sheet() {
    return CustomBottomSheets().sheet([this], height: 250);
  }
}
