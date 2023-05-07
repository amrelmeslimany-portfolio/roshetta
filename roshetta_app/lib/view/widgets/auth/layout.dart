import 'package:flutter/material.dart';
import 'package:roshetta_app/core/shared/circle.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class AuthLayout extends StatelessWidget {
  final Widget widget;
  const AuthLayout({super.key, required this.widget});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
          child: Stack(
        children: [
          Positioned(
            top: -120,
            left: -120,
            child: Cirlce(opacity: 0.2).circle,
          ),
          Positioned.fill(
              bottom: 0,
              top: 0,
              left: 0,
              right: 0,
              child: ListView(children: [
                const SizedBox(height: 15),
                widget,
                const SizedBox(height: 25),
                const CustomText(text: "", color: Colors.transparent)
                    .copyrightText(context),
                const SizedBox(height: 15),
              ])),
        ],
      )),
    );
  }
}
