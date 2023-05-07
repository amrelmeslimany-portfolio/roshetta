import 'package:flutter/material.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/view/widgets/clinics/date_box.dart';
import 'package:roshetta_app/view/widgets/patient/prescripts/prescript_status.dart';
import 'package:roshetta_app/view/widgets/shared/custom_boxes.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class PharmacyPrescriptsList extends StatefulWidget {
  final List prescripts;
  final Function(String) onItemPressed;
  const PharmacyPrescriptsList(
      {super.key, required this.prescripts, required this.onItemPressed});

  @override
  State<PharmacyPrescriptsList> createState() => _PharmacyPrescriptsListState();
}

class _PharmacyPrescriptsListState extends State<PharmacyPrescriptsList> {
  List<String> selected = [];
  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        const SizedBox(height: 10),
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 8.0),
          child: HeaderBadge(
              header: "الروشتات",
              badgeText: handleNumbers(widget.prescripts.length),
              isSmallText: true),
        ),
        const SizedBox(height: 5),
        SizedBox(
          height: 150,
          child: ListView.separated(
            padding: const EdgeInsets.all(10),
            scrollDirection: Axis.horizontal,
            separatorBuilder: (context, index) => SizedBox(
                width: (index + 1) == widget.prescripts.length ? 0 : 15),
            itemCount: widget.prescripts.length,
            itemBuilder: (context, index) {
              Map item = widget.prescripts[index];
              return GestureDetector(
                onTap: () {
                  setState(() {
                    if (selected.contains(item["prescript_id"])) {
                      selected.remove(item["prescript_id"]);
                    } else {
                      if (selected.isNotEmpty) {
                        snackbar(
                            color: Colors.red,
                            title: "الروشته",
                            content: "يجب اختيار روشته واحده فقط");
                      } else {
                        selected.insert(0, item["prescript_id"]);
                      }
                    }
                  });
                  widget.onItemPressed(item["prescript_id"]);
                },
                child: CustomShadowBox(
                    isBorder: selected.contains(item["prescript_id"]),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        PrescriptStatusBadge(
                            status: item["prescriptStatus"], size: 9),
                        const SizedBox(height: 10),
                        ListView(
                          shrinkWrap: true,
                          children: [
                            DateBox(date: item["created_date"]),
                            const SizedBox(height: 5),
                            Text(item["disease_name"],
                                textAlign: TextAlign.center,
                                softWrap: false,
                                overflow: TextOverflow.ellipsis,
                                maxLines: 2,
                                style: const TextStyle(
                                    fontSize: 12,
                                    color: AppColors.lightTextColor)),
                          ],
                        )
                      ],
                    )),
              );
            },
          ),
        ),
        const SizedBox(height: 10),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            CustomText(
                text: selected.isNotEmpty
                    ? "قمت باختيار روشته"
                    : "يجب اختيار روشته واحده للارسال",
                color: AppColors.lightTextColor,
                textType: 5),
            if (selected.isNotEmpty)
              TextButton(
                onPressed: () {
                  if (selected.isNotEmpty) {
                    setState(() {
                      selected.clear();
                    });
                  }
                  ;
                },
                child: const CustomText(
                    text: "حذف الاختيار",
                    color: AppColors.primaryColor,
                    textType: 5),
              )
          ],
        )
      ],
    );
  }
}
