import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';

class Settings extends StatelessWidget {
  final GlobalKey<ScaffoldState> drawerState;
  const Settings({super.key, required this.drawerState});

  @override
  Widget build(BuildContext context) {
    // GlobalKey<ScaffoldState> drawerState = GlobalKey<ScaffoldState>();
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
                SwitchListTile(
                  value: true,
                  onChanged: (value) => print(value),
                  activeColor: AppColors.primaryColor,
                  title: Row(
                    children: [
                      const Icon(Icons.download,
                          color: AppColors.primaryTextColor, size: 24),
                      const SizedBox(width: 10),
                      Text("تنزيل الروشتات",
                          style: Theme.of(context)
                              .textTheme
                              .bodyLarge
                              ?.copyWith(color: AppColors.primaryTextColor))
                    ],
                  ),
                )
              ],
            ),
          )
        ]);
  }
}
