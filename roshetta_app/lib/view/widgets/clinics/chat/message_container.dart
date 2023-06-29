import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/models/chat.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class MessageContainer extends StatelessWidget {
  final MessageModel message;
  final bool topMargin;
  final Function(String)? onDelete;
  const MessageContainer(
      {super.key,
      required this.message,
      required this.topMargin,
      this.onDelete});

  @override
  Widget build(BuildContext context) {
    _handleTime();
    bool isYou = message.name == "1" ? true : false;
    return Container(
      margin: EdgeInsets.fromLTRB(15, topMargin ? 15 : 0, 15, 15),
      child: Row(
        mainAxisAlignment:
            !isYou ? MainAxisAlignment.end : MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (isYou) ...[
            shadowCircleAvatar(message.image ?? AssetPaths.emptyIMG,
                isCached: false, radius: 22),
            const SizedBox(width: 10)
          ],
          ConstrainedBox(
            constraints: const BoxConstraints(maxWidth: 260),
            child: Column(
              crossAxisAlignment:
                  isYou ? CrossAxisAlignment.start : CrossAxisAlignment.end,
              children: [
                CustomText(
                  text: isYou ? "أنت" : message.name!.capitalize!,
                  align: isYou ? TextAlign.start : TextAlign.end,
                  fontWeight: isYou ? FontWeight.w700 : null,
                  textType: 3,
                  color:
                      isYou ? AppColors.primaryColor : AppColors.lightTextColor,
                ),
                const SizedBox(height: 5),
                GestureDetector(
                  onLongPress: isYou
                      ? () {
                          onDelete!(message.id!);
                        }
                      : null,
                  child: Container(
                    padding: const EdgeInsets.symmetric(
                        horizontal: 15, vertical: 10),
                    decoration: BoxDecoration(
                        color: isYou
                            ? AppColors.primaryColor
                            : AppColors.primaryAColor,
                        borderRadius: _radiousBox(isYou)),
                    child: CustomText(
                        text: message.message!,
                        align: TextAlign.start,
                        color:
                            isYou ? Colors.white : AppColors.primaryTextColor,
                        fontWeight: FontWeight.w500,
                        textType: 3),
                  ),
                ),
                const SizedBox(height: 5),
                CustomText(
                  text: _handleTime(),
                  align: TextAlign.start,
                  fontWeight: FontWeight.w400,
                  textType: 4,
                  color: AppColors.lightTextColor,
                )
              ],
            ),
          ),
          if (!isYou) ...[
            const SizedBox(width: 10),
            shadowCircleAvatar(message.image ?? AssetPaths.emptyIMG,
                isCached: false, radius: 22),
          ],
        ],
      ),
    );
  }

  BorderRadiusGeometry _radiousBox(bool isYou) {
    Radius radius = const Radius.circular(20);
    return BorderRadius.only(
        bottomLeft: radius,
        bottomRight: radius,
        topLeft: isYou ? radius : Radius.zero,
        topRight: isYou ? Radius.zero : radius);
  }

  String _handleTime() {
    DateTime current = DateFormat("yyyy-MM-dd HH:mm:ss").parse(message.time!);
    String time = DateFormat("hh:mm a", "ar").format(current);
    String date = DateFormat("d MMM ,yyyy  ", "ar").format(current);
    return "$time , $date";
  }
}
