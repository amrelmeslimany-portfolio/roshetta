import 'package:flutter/material.dart';

class BodyLayout extends StatelessWidget {
  final Widget appbar;
  final List<Widget> content;
  const BodyLayout({super.key, required this.appbar, required this.content});

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Column(children: [
        appbar,
        Expanded(
          child: Container(
            padding: const EdgeInsets.only(left: 15, right: 15),
            child: ListView(
              physics: const BouncingScrollPhysics(),
              shrinkWrap: true,
              children: content,
            ),
          ),
        )
      ]),
    );
  }
}
