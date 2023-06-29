import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';

class ChatFormSend extends StatelessWidget {
  final TextEditingController textController;
  final RequestStatus status;
  final Function() onSend;
  const ChatFormSend(
      {super.key,
      required this.textController,
      required this.onSend,
      required this.status});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(15),
      decoration: _boxDecoration(),
      child: SafeArea(
          child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 15, vertical: 5),
        decoration: BoxDecoration(
            borderRadius: const BorderRadius.all(Radius.circular(20)),
            color: AppColors.lightenWhiteColor.withOpacity(0.25)),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            Expanded(
              child: TextField(
                controller: textController,
                enabled: status != RequestStatus.loading,
                minLines: 1,
                maxLines: 5,
                keyboardType: TextInputType.multiline,
                style: const TextStyle(
                    fontSize: 16, fontWeight: FontWeight.normal),
                decoration: const InputDecoration(
                    border: InputBorder.none, hintText: "الرسالة..."),
              ),
            ),
            const SizedBox(width: 10),
            status == RequestStatus.loading
                ? const CircularProgressIndicator()
                : ICButton(
                        onPressed: onSend,
                        color: AppColors.primaryAColor,
                        iconColor: AppColors.primaryColor,
                        padding: const EdgeInsets.all(8),
                        size: 20,
                        icon: FontAwesomeIcons.solidPaperPlane)
                    .init
          ],
        ),
      )),
    );
  }

  BoxDecoration _boxDecoration() {
    return BoxDecoration(
        color: Colors.white,
        borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 15,
              offset: const Offset(0, -5),
              spreadRadius: 1)
        ]);
  }
}
