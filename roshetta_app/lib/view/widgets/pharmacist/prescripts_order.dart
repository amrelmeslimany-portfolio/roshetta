import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:intl/intl.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/date_functions.dart';
import 'package:roshetta_app/core/functions/styles_functions.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class PrescriptOrders extends StatelessWidget {
  final List orders;
  final bool? withStatus;
  final bool? isPaied;
  final bool? isPatient;
  final bool? isLoading;
  final Function(Map) onOrderPress;
  final Function(Map)? onDelete;
  const PrescriptOrders({
    super.key,
    required this.orders,
    required this.onOrderPress,
    this.withStatus = false,
    this.isPaied = false,
    this.isPatient = false,
    this.isLoading = false,
    this.onDelete,
  });

  @override
  Widget build(BuildContext context) {
    return ListView.separated(
      separatorBuilder: (context, index) {
        return const SizedBox(height: 15);
      },
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: orders.length,
      itemBuilder: (context, index) {
        var item = orders[index];

        return CustomListTile(
          onTilePressed: () {
            if (isLoading!) return;
            onOrderPress(item);
          },
          widget: Padding(
            padding: const EdgeInsets.all(8.0),
            child: isLoading!
                ? const CircularProgressIndicator(color: AppColors.primaryColor)
                : Center(
                    child: isPaied!
                        ? FaIcon(
                            isPatient!
                                ? FontAwesomeIcons.cartShopping
                                : FontAwesomeIcons.solidCircleCheck,
                            color: AppColors.primaryColor,
                            size: 28,
                          )
                        : Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            crossAxisAlignment: CrossAxisAlignment.center,
                            children: [
                                CustomText(
                                    text: DateFormat("a", "Ar").format(
                                        getParesedDTFRString(item["time"])),
                                    color: AppColors.primaryColor,
                                    textType: 4),
                                _bigText(DateFormat("h:mm")
                                    .format(getParesedDTFRString(item["time"])))
                              ]),
                  ),
          ),
          smallTitle: item["ser_id"] ?? item["prescript_ser_id"],
          title: item[isPatient! ? "pharmacy_name" : "patient_name"],
          descriptionIcon:
              isPaied! ? FontAwesomeIcons.solidClock : FontAwesomeIcons.phone,
          description: isPaied!
              ? _getPaiedDate(item[isPatient! ? "time" : "date_pay"])
              : item["patient_phone_number"],
          descriptionColor: AppColors.primaryTextColor.withOpacity(0.8),
          middleWidget: withStatus!
              ? CustomBadge(
                  badgeText: prescriptStatusWord(item["orderStatus"])["text"],
                  badgeColor: prescriptStatusWord(item["orderStatus"])["color"],
                  badgeTextColor: AppColors.primaryTextColor,
                  fontSize: 12)
              : null,
          buttonIcon: isPatient! ? Icons.delete_outlined : null,
          onButtonPressed: isPatient!
              ? () {
                  if (isLoading!) return;
                  onDelete!(item);
                }
              : null,
        );
      },
    );
  }

  CustomText _bigText(String text, {int? level = 2}) {
    return CustomText(
        text: text,
        color: AppColors.primaryColor,
        textType: level!,
        fontWeight: FontWeight.bold);
  }

  String _getPaiedDate(String date) {
    return DateFormat("h:mm a , yyyy/MM/dd", "ar")
        .format(getParesedDTFRString(date));
  }
}
