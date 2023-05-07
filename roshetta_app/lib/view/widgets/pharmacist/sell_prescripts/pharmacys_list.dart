import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/view/widgets/shared/custom_boxes.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class ChoosePharmacysList extends StatelessWidget {
  final List pharmacys;
  final String pharmacyId;
  final Function(String) onItemPressed;
  const ChoosePharmacysList(
      {super.key,
      required this.pharmacys,
      required this.onItemPressed,
      required this.pharmacyId});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        HeaderBadge(
          header: "اختر الصيدلية",
          badgeText: handleNumbers(pharmacys.length),
          isSmallText: true,
          badgeTextColor: AppColors.greyColor,
        ),
        const SizedBox(height: 15),
        pharmacys.isEmpty
            ? Center(child: emptyLottieList())
            : SizedBox(
                height: 210,
                child: ListView.separated(
                  padding: const EdgeInsets.all(10),
                  separatorBuilder: (context, index) =>
                      const SizedBox(width: 15),
                  scrollDirection: Axis.horizontal,
                  itemCount: pharmacys.length,
                  itemBuilder: (context, index) {
                    Map item = pharmacys[index];
                    return GestureDetector(
                      onTap: () {
                        onItemPressed(item["id"]);
                      },
                      child: CustomShadowBox(
                          padding: 10,
                          width: 150,
                          isBorder: pharmacyId == item["id"],
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            crossAxisAlignment: CrossAxisAlignment.center,
                            children: [
                              shadowCircleAvatar(
                                  item["logo"] ?? AssetPaths.emptyIMG,
                                  radius: 40),
                              const SizedBox(height: 10),
                              Text(item["name"],
                                  textAlign: TextAlign.center,
                                  softWrap: false,
                                  overflow: TextOverflow.ellipsis,
                                  maxLines: 2,
                                  style: const TextStyle(
                                      fontSize: 18,
                                      color: AppColors.primaryTextColor)),
                              const SizedBox(height: 2),
                              iconAndWidget(
                                  checkOpenStatus(
                                      item["status"],
                                      Icons.lock_outline_rounded,
                                      Icons.lock_open_rounded),
                                  iconColor: checkOpenStatus(
                                      item["status"],
                                      AppColors.greyColor,
                                      AppColors.primaryColor),
                                  mainAlign: MainAxisAlignment.center,
                                  iconSize: 14,
                                  space: 2,
                                  widget: checkOpenStatus(
                                      item["status"],
                                      const CustomText(
                                          text: "مغلقة", textType: 4),
                                      const CustomText(
                                        text: "مفتوحة",
                                        textType: 5,
                                        color: AppColors.primaryColor,
                                      )))
                            ],
                          )),
                    );
                  },
                ),
              ),
        const SizedBox(height: 15),
      ],
    );
  }
}
