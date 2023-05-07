import 'dart:io';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';

class CustomListTile extends StatelessWidget {
  final String? img;
  final Widget? widget;
  final double? mediaHeight;
  final String title;
  final String? smallTitle;
  final Color? smallTitleColor;
  final Color? mediaColor;
  final String? description;
  final IconData? descriptionIcon;
  final Color? descriptionColor;
  final IconData? buttonIcon;
  final Widget? middleWidget;
  final Widget? moreWidget;
  final Function()? onTilePressed;
  final Function()? onButtonPressed;

  const CustomListTile(
      {super.key,
      required this.title,
      this.img,
      this.widget,
      this.mediaHeight,
      this.smallTitle,
      this.smallTitleColor,
      this.description,
      this.descriptionIcon,
      this.buttonIcon,
      this.onTilePressed,
      this.onButtonPressed,
      this.descriptionColor,
      this.middleWidget,
      this.moreWidget,
      this.mediaColor});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTilePressed,
      child: Container(
        decoration: shadowBoxWhite,
        padding: const EdgeInsets.symmetric(horizontal: 15, vertical: 10),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              height: mediaHeight ?? 68,
              width: mediaHeight ?? 68,
              decoration: BoxDecoration(
                  color: mediaColor ?? AppColors.primaryAColor,
                  borderRadius: const BorderRadius.all(Radius.circular(5))),
              clipBehavior: Clip.hardEdge,
              child: img == null && widget != null
                  ? widget
                  : CachedNetworkImage(
                      imageUrl: img!,
                      fit: BoxFit.fill,
                      errorWidget: (context, url, error) =>
                          Image.asset(AssetPaths.logoIcon),
                    ),
            ),
            const SizedBox(width: 15),
            Expanded(
                child: Column(
              children: [
                Row(
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          smallTitle != null
                              ? Text(
                                  smallTitle!,
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                  style: Theme.of(context)
                                      .textTheme
                                      .bodySmall!
                                      .copyWith(
                                          color: smallTitleColor ??
                                              AppColors.lightTextColor),
                                )
                              : const SizedBox(),
                          Text(title,
                              overflow: TextOverflow.ellipsis,
                              softWrap: true,
                              maxLines: 2,
                              style: Theme.of(context)
                                  .textTheme
                                  .bodyLarge!
                                  .copyWith(fontWeight: FontWeight.bold)),
                          ..._checkDescriptionText(context),
                        ],
                      ),
                    ),
                    if (middleWidget != null)
                      Container(
                          margin: const EdgeInsets.symmetric(horizontal: 2.5),
                          child: middleWidget),
                    ..._checkButton()
                  ],
                ),
                ..._moreWidgets()
              ],
            )),
          ],
        ),
      ),
    );
  }

  List<Widget> _moreWidgets() {
    if (moreWidget != null) {
      return [const SizedBox(height: 5), moreWidget!];
    } else {
      return [Container()];
    }
  }

  List<Widget> _checkDescriptionText(BuildContext context) {
    if (description != null) {
      return [
        const SizedBox(height: 5),
        iconAndWidget(descriptionIcon!,
            iconSize: 14,
            iconColor: descriptionColor ?? AppColors.lightTextColor,
            widget: Expanded(
              child: Text(description!,
                  overflow: TextOverflow.ellipsis,
                  softWrap: true,
                  style: Theme.of(context).textTheme.bodyMedium!.copyWith(
                      color: descriptionColor ?? AppColors.lightTextColor,
                      fontWeight: FontWeight.w600)),
            ))
      ];
    } else {
      return [const SizedBox()];
    }
  }

  List<Widget> _checkButton() {
    if (onButtonPressed != null && buttonIcon != null) {
      return [
        const SizedBox(width: 15),
        ICButton(
                onPressed: onButtonPressed!,
                icon: buttonIcon!,
                size: 18,
                padding: const EdgeInsets.all(5),
                iconColor: AppColors.primaryColor)
            .bordered
      ];
    } else {
      return [const SizedBox()];
    }
  }
}

class CustomListTileUploader extends StatelessWidget {
  final bool isCurrentError;
  final XFile? file;
  final double? width;
  final Function()? onDelete;

  const CustomListTileUploader(
      {super.key,
      required this.isCurrentError,
      this.file,
      this.onDelete,
      this.width});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: width,
      padding: const EdgeInsets.symmetric(horizontal: 15, vertical: 10),
      decoration: isCurrentError ? errorDecoration() : shadowBoxWhite,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            width: 40,
            height: 40,
            clipBehavior: Clip.hardEdge,
            decoration: BoxDecoration(
                color: isCurrentError
                    ? Colors.red.withOpacity(0.3)
                    : AppColors.primaryAColor,
                borderRadius: const BorderRadius.all(Radius.circular(5))),
            child: file != null
                ? Image.file(
                    File(file!.path),
                    fit: BoxFit.fill,
                  )
                : Icon(
                    Icons.upload_rounded,
                    color: isCurrentError
                        ? Colors.red.withOpacity(0.3)
                        : AppColors.primaryColor,
                    size: 24,
                  ),
          ),
          const SizedBox(width: 10),
          Expanded(
              child: Text(
            file?.name ?? "قم باختيار صورة",
            style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.normal,
                color:
                    isCurrentError ? Colors.red : AppColors.primaryTextColor),
          )),
          const SizedBox(width: 15),
          file != null
              ? InkWell(
                  onTap: onDelete,
                  child: const Icon(Icons.delete_rounded,
                      color: AppColors.lightTextColor),
                )
              : const SizedBox()
        ],
      ),
    );
  }

  BoxDecoration errorDecoration() {
    return BoxDecoration(
        border: Border.all(color: Colors.red, width: 1),
        color: AppColors.whiteColor,
        borderRadius: const BorderRadius.all(Radius.circular(15)),
        boxShadow: [
          BoxShadow(
              spreadRadius: 1,
              color: Colors.black.withOpacity(0.06),
              blurRadius: 8)
        ]);
  }
}
