import 'dart:io';
import 'package:flutter/widgets.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';

class ExistApp extends StatelessWidget {
  final Widget child;
  const ExistApp({super.key, required this.child});

  @override
  Widget build(BuildContext context) {
    return WillPopScope(
      onWillPop: () async {
        confirmDialog(context, text: "متأكد انك تريد الخروج من التطبيق ؟",
            onConfirm: () {
          exit(0);
        });

        return true;
      },
      child: child,
    );
  }
}
