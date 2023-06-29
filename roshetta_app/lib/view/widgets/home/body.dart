import 'package:flutter/material.dart';

class BodyLayout extends StatelessWidget {
  final Widget appbar;
  final List<Widget> content;
  final ScrollController? scrollController;
  final Future<void> Function()? onRefresh;
  const BodyLayout(
      {super.key,
      required this.appbar,
      required this.content,
      this.scrollController,
      this.onRefresh});

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Column(children: [
        appbar,
        Expanded(
            child: onRefresh != null
                ? RefreshIndicator(onRefresh: onRefresh!, child: _content())
                : _content())
      ]),
    );
  }

  Widget _content() => Container(
        padding: const EdgeInsets.only(left: 15, right: 15),
        child: ListView(
          controller: scrollController,
          physics: const BouncingScrollPhysics(),
          shrinkWrap: true,
          children: content,
        ),
      );
}
