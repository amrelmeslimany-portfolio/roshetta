import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/settings_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';

class Settings extends StatelessWidget {
  final GlobalKey<ScaffoldState> drawerState;
  Settings({super.key, required this.drawerState});
  final settings = Get.put(SettingsController());
  final Color textColor = AppColors.primaryTextColor;

  @override
  Widget build(BuildContext context) {
    return BodyLayout(
        appbar: CustomAppBar(
          isBack: false,
          onPressed: () {
            toggleDrawer(drawerState);
          },
        ).init,
        content: [
          HeaderContent(
            header: "الاعدادت",
            content: Column(
              children: [
                ListTile(
                    onTap: () => settings.goToChangeServerApi(),
                    leading: Icon(
                      Icons.link_rounded,
                      color: textColor,
                      size: 24,
                    ),
                    title: _normalText("تعديل API"),
                    minLeadingWidth: 0,
                    trailing: const Icon(Icons.arrow_right_rounded, size: 45)),
                Obx(
                  () => SwitchListTile(
                    value: settings.isPrescripsSave.value,
                    onChanged: (value) => settings.presecriptSave = value,
                    activeColor: AppColors.primaryColor,
                    title: Row(
                      children: [
                        const Icon(Icons.download,
                            color: AppColors.primaryTextColor, size: 24),
                        const SizedBox(width: 18),
                        _normalText("حفظ الروشتات")
                      ],
                    ),
                  ),
                )
              ],
            ),
          )
        ]);
  }

  Widget _normalText(String text) {
    return Text(text,
        style: Theme.of(Get.context!)
            .textTheme
            .bodyLarge
            ?.copyWith(color: textColor));
  }
}
