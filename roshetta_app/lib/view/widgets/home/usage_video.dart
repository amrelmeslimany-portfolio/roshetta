import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:video_player/video_player.dart';

class UsageVideo extends StatefulWidget {
  final String src;
  final bool? isNetwork;
  final double? height;

  const UsageVideo({super.key, required this.src, this.height, this.isNetwork});

  @override
  State<UsageVideo> createState() => _UsageVideoState();
}

class _UsageVideoState extends State<UsageVideo> {
  late VideoPlayerController controller;

  @override
  void initState() {
    super.initState();
    controller = widget.isNetwork != null || widget.isNetwork == true
        ? VideoPlayerController.network(widget.src)
        : VideoPlayerController.asset(widget.src);
    controller
      ..addListener(() => setState(() {}))
      ..initialize();
  }

  @override
  void dispose() {
    controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (controller.value.isInitialized) {
      return Container(
        decoration: shadowBoxWhite,
        clipBehavior: Clip.hardEdge,
        height: widget.height ?? 300,
        width: Get.width,
        child: videoStack(),
      );
    } else if (controller.value.hasError) {
      return SizedBox(
        height: widget.height ?? 300,
        width: Get.width,
        child: Center(child: Lottie.asset(AssetPaths.error, height: 50)),
      );
    } else {
      return SizedBox(
        height: widget.height ?? 300,
        width: Get.width,
        child: Center(child: Lottie.asset(AssetPaths.loading, height: 50)),
      );
    }
  }

  Widget videoStack() => Stack(
        alignment: Alignment.center,
        textDirection: TextDirection.rtl,
        children: [buildVideo(), Positioned.fill(child: overlayStopped())],
      );

  Widget buildVideo() => AspectRatio(
      aspectRatio: controller.value.aspectRatio,
      child: VideoPlayer(controller));

  Widget overlayStopped() => GestureDetector(
        behavior: HitTestBehavior.opaque,
        onTap: () =>
            controller.value.isPlaying ? controller.pause() : controller.play(),
        child: Stack(
          children: [
            stoppedStyle(),
            Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              child: Directionality(
                textDirection: TextDirection.ltr,
                child: VideoProgressIndicator(controller,
                    allowScrubbing: true,
                    padding: const EdgeInsets.all(10),
                    colors: VideoProgressColors(
                        playedColor: AppColors.hoveredPrimaryColor,
                        bufferedColor: AppColors.whiteColor.withOpacity(0.4),
                        backgroundColor:
                            AppColors.lightTextColor.withOpacity(0.6))),
              ),
            )
          ],
        ),
      );

  Widget stoppedStyle() {
    if (controller.value.isPlaying) {
      return const SizedBox();
    } else {
      return Container(
        alignment: Alignment.center,
        color: AppColors.primaryColor.withOpacity(0.3),
        child: const CircleAvatar(
            backgroundColor: AppColors.whiteColor,
            radius: 40,
            child: Icon(
              Icons.play_arrow,
              color: AppColors.primaryColor,
              size: 60,
            )),
      );
    }
  }
}
