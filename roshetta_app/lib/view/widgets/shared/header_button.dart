import 'package:flutter/material.dart';

class HeaderButtonFilter extends StatelessWidget {
  final Function() onClear;
  final Widget child;
  const HeaderButtonFilter(
      {super.key, required this.onClear, required this.child});

  @override
  Widget build(BuildContext context) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Expanded(
          child: child,
        ),
        TextButton.icon(
          style: const ButtonStyle(
              padding: MaterialStatePropertyAll(
                  EdgeInsets.symmetric(horizontal: 5, vertical: 0))),
          icon: const Icon(Icons.filter_alt_off, size: 14),
          label: const Text("حذف الفلتر", style: TextStyle(fontSize: 14)),
          onPressed: onClear,
        )
      ],
    );
  }
}
