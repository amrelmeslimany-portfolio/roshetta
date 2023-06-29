import 'package:flutter/material.dart';
import 'package:roshetta_app/view/widgets/auth/label_divider.dart';
import 'package:roshetta_app/view/widgets/clinics/appointments/list.dart';
import 'package:roshetta_app/view/widgets/shared/header_button.dart';

class FilterAppointHeader extends StatelessWidget {
  final Function() onClearFilter;
  final DateTime? dateFilter;
  final String statusFilter;
  final String? defaultStatusFilter;
  final String searchFilter;
  const FilterAppointHeader(
      {super.key,
      required this.onClearFilter,
      this.dateFilter,
      required this.statusFilter,
      required this.searchFilter,
      this.defaultStatusFilter = "1"});

  @override
  Widget build(BuildContext context) {
    return _checkSearchWord().isEmpty
        ? const DividerText(text: "اليوم", width: double.infinity)
        : HeaderButtonFilter(
            onClear: () {
              onClearFilter();
            },
            child: Column(
              children: _checkSearchWord(),
            ));
  }

  List<Widget> _checkSearchWord() {
    List<Widget> list = [];
    if (dateFilter != null) {
      DateTime date = dateFilter!;
      list.add(DividerText(
          text: "التاريخ: ${date.year}/${date.month}/${date.day}",
          size: 14,
          width: double.infinity));
    }

    if (statusFilter.isNotEmpty && statusFilter != defaultStatusFilter) {
      String status = checkAppointStatusWord(statusFilter);
      list.add(DividerText(
          text: "الحاله: $status", size: 14, width: double.infinity));
    }

    if (searchFilter.isNotEmpty) {
      list.add(DividerText(
          text: "البحث: $searchFilter", size: 14, width: double.infinity));
    }

    return list;
  }
}
