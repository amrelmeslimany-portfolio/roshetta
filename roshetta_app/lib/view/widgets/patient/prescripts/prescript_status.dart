import 'package:flutter/widgets.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/styles_functions.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class PrescriptStatusBadge extends StatelessWidget {
  final String status;
  final double? size;
  const PrescriptStatusBadge({super.key, required this.status, this.size = 12});

  @override
  Widget build(BuildContext context) {
    return CustomBadge(
        badgeText: prescriptStatusWord(status)["text"],
        badgeColor: prescriptStatusWord(status)["color"],
        badgeTextColor: AppColors.primaryTextColor,
        fontSize: size);
  }
}
