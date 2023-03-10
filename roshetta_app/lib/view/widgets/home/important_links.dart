import 'package:flutter/widgets.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';

class ImportantLinks extends StatelessWidget {
  const ImportantLinks({super.key});

  @override
  Widget build(BuildContext context) {
    return pharmacist(context);
  }

  Widget patient(BuildContext context) => Column(
        children: [
          Row(
            children: [
              Expanded(
                child: BorderedButton(context,
                        text: "العيادات",
                        icon: FontAwesomeIcons.hospital,
                        onPressed: () {})
                    .button,
              ),
              const SizedBox(width: 5),
              Expanded(
                child: BorderedButton(context,
                        text: "الصيدليات",
                        icon: FontAwesomeIcons.houseMedical,
                        onPressed: () {})
                    .button,
              ),
            ],
          ),
          const SizedBox(height: 5),
          Row(
            children: [
              Expanded(
                child: BorderedButton(context,
                        text: "الروشتات",
                        icon: FontAwesomeIcons.receipt,
                        onPressed: () {})
                    .button,
              ),
              const SizedBox(width: 5),
              Expanded(
                child: BorderedButton(context,
                        text: "الامراض",
                        icon: FontAwesomeIcons.disease,
                        onPressed: () {})
                    .button,
              ),
            ],
          )
        ],
      );

  Widget doctor(BuildContext context) => Column(
        children: [
          Row(
            children: [
              Expanded(
                child: BorderedButton(context,
                        text: "العيادات",
                        icon: FontAwesomeIcons.hospital,
                        onPressed: () {})
                    .button,
              ),
              const SizedBox(width: 5),
              Expanded(
                child: BorderedButton(context,
                        text: "اضافة عياده",
                        icon: FontAwesomeIcons.circlePlus,
                        onPressed: () {})
                    .button,
              ),
            ],
          )
        ],
      );

  Widget pharmacist(BuildContext context) => Column(
        children: [
          Row(
            children: [
              Expanded(
                child: BorderedButton(context,
                        text: "الصيدليات",
                        icon: FontAwesomeIcons.houseMedical,
                        onPressed: () {})
                    .button,
              ),
              const SizedBox(width: 5),
              Expanded(
                child: BorderedButton(context,
                        text: "اضافه صيدلية",
                        icon: FontAwesomeIcons.circlePlus,
                        onPressed: () {})
                    .button,
              ),
            ],
          ),
          const SizedBox(height: 5),
          Row(
            children: [
              Expanded(
                child: BorderedButton(context,
                        text: "صرف روشته",
                        icon: FontAwesomeIcons.receipt,
                        onPressed: () {})
                    .button,
              ),
            ],
          )
        ],
      );
}
