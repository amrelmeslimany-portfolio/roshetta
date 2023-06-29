import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/widgets/shared/text_search.dart';

class FilterAppointment extends StatelessWidget {
  final TextEditingController searchController;
  final Function() onWordSearch;
  final Function() onFilterDate;
  final Function() onStatusSheet;
  final Function(String) onStatusChange;
  const FilterAppointment(
      {super.key,
      required this.searchController,
      required this.onWordSearch,
      required this.onFilterDate,
      required this.onStatusChange,
      required this.onStatusSheet});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
            child: TextSearchField(
                controller: searchController, onSearch: onWordSearch)),
        const SizedBox(width: 5),
        ICButton(
                color: Colors.white,
                padding: const EdgeInsets.all(12),
                iconColor: AppColors.lightTextColor,
                size: 20,
                onPressed: onFilterDate,
                icon: Icons.calendar_month_rounded)
            .init,
        const SizedBox(width: 5),
        ICButton(
                color: Colors.white,
                padding: const EdgeInsets.all(10),
                iconColor: AppColors.lightTextColor,
                size: 23,
                onPressed: onStatusSheet,
                icon: Icons.filter_alt_outlined)
            .init,
      ],
    );
  }
}
