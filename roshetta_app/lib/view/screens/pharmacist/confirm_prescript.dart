import 'package:flutter/material.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';

class ConfirmPrescriptButton extends StatelessWidget {
  final Function() onClick;
  final RequestStatus status;
  const ConfirmPrescriptButton(
      {super.key, required this.onClick, required this.status});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: 50,
      height: 50,
      child: FloatingActionButton(
          backgroundColor: AppColors.primaryColor,
          onPressed: onClick,
          child: status == RequestStatus.loading
              ? const CircularProgressIndicator(color: Colors.white)
              : const Icon(
                  Icons.add_card_outlined,
                  color: AppColors.whiteColor,
                  size: 28,
                )),
    );
  }
}
