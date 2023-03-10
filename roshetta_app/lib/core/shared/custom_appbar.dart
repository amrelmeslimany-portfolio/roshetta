import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';

class CustomAppBar {
  final Function() onPressed;
  Widget? children;
  bool isBack = true;
  CustomAppBar({required this.onPressed, this.children, this.isBack = true});

  get init {
    return SizedBox(
      width: double.maxFinite,
      child: Stack(
        children: [
          Positioned.fill(
              child: ShaderMask(
            blendMode: BlendMode.srcATop,
            shaderCallback: (bound) {
              return LinearGradient(
                  transform: const GradientRotation(11),
                  stops: const [
                    0.2,
                    0.7
                  ],
                  colors: [
                    AppColors.primaryColor.withOpacity(0.78),
                    const Color.fromARGB(255, 27, 137, 85).withOpacity(0.78)
                  ]).createShader(bound);
            },
            child: Image.asset(AssetPaths.appbar, fit: BoxFit.cover),
          )),
          Container(
            padding:
                const EdgeInsets.only(left: 15, right: 15, top: 15, bottom: 45),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    isBack
                        ? ICButton(
                                onPressed: () {
                                  Get.back();
                                },
                                icon: FontAwesomeIcons.arrowRight)
                            .init
                        : const SizedBox(),
                    ICButton(onPressed: onPressed, icon: FontAwesomeIcons.bars)
                        .init,
                  ],
                ),
                children ?? const SizedBox()
              ],
            ),
          ),
          Positioned(
              bottom: -1,
              child: Container(
                height: 30,
                width: Get.width,
                decoration: const BoxDecoration(
                    color: AppColors.whiteColor,
                    borderRadius: BorderRadius.only(
                        topLeft: Radius.circular(30),
                        topRight: Radius.circular(30))),
              ))
        ],
      ),
    );
  }
}
