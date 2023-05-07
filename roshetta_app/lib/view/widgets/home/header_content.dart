import 'package:flutter/material.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class HeaderContent extends StatelessWidget {
  final String header;
  final Widget content;
  final double? spacer;

  const HeaderContent(
      {super.key,
      required this.header,
      required this.content,
      this.spacer = 15});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 8, vertical: 5),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CustomText(text: header).subHeader(context),
          SizedBox(height: spacer),
          content,
        ],
      ),
    );
  }
}
