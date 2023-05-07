import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';

class Stuff extends StatelessWidget {
  final List items;
  const Stuff({super.key, required this.items});

  @override
  Widget build(BuildContext context) {
    return HeaderContent(
        header: "طاقم العمل",
        spacer: 0,
        content: SizedBox(
          height: 240,
          child: ListView.separated(
              padding: const EdgeInsets.all(15),
              shrinkWrap: false,
              scrollDirection: Axis.horizontal,
              physics: const BouncingScrollPhysics(),
              itemBuilder: (context, index) {
                return _item(items[index], context);
              },
              separatorBuilder: (context, index) => const SizedBox(width: 15),
              itemCount: items.length),
        ));
  }

  Container _item(Map item, BuildContext context) {
    String type = usersAR[item["type"]]!;
    return Container(
      height: double.minPositive,
      width: 180,
      alignment: Alignment.center,
      decoration: shadowBoxWhite,
      padding: const EdgeInsets.all(15),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          shadowCircleAvatar(item["image"] ?? AssetPaths.emptyIMG, radius: 40),
          const SizedBox(height: 15),
          CustomText(
            text: item["name"],
            color: AppColors.primaryTextColor,
            fontWeight: FontWeight.w600,
            align: TextAlign.center,
          ).truncateText(
            context,
            maxLines: 2,
          ),
          const SizedBox(height: 5),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: CustomText(
                    text: type,
                    color: item["type"] == "doctor"
                        ? AppColors.primaryColor
                        : null,
                    align: TextAlign.start,
                    textType: 5),
              ),
              const SizedBox(width: 10),
              item["age"] == "new"
                  ? const CustomText(
                      text: "جديد",
                      textType: 5,
                      color: AppColors.lightTextColor)
                  : iconAndWidget(FontAwesomeIcons.solidCalendarDays,
                      mainAlign: MainAxisAlignment.center,
                      iconSize: 14,
                      iconColor: AppColors.lightTextColor,
                      widget: CustomText(
                          text: item["age"],
                          textType: 5,
                          color: AppColors.lightTextColor)),
            ],
          )
        ],
      ),
    );
  }
}
