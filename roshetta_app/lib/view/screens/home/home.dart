import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/home/home_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_notes.dart';
import 'package:roshetta_app/view/widgets/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';

import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/home/important_links.dart';
import 'package:roshetta_app/view/widgets/home/usage_video.dart';

class Home extends StatelessWidget {
  final GlobalKey<ScaffoldState> drawerState;
  const Home({super.key, required this.drawerState});

  @override
  Widget build(BuildContext context) {
    Get.put<HomeControllerImp>(HomeControllerImp());
    return GetBuilder<HomeControllerImp>(builder: (homeController) {
      return BodyLayout(
          appbar: CustomAppBar(
            isBack: false,
            onPressed: () {
              toggleDrawer(drawerState);
            },
          ).init,
          content: [
            Container(
              margin: const EdgeInsets.symmetric(horizontal: 8, vertical: 5),
              child: Notes(
                      buttonText: "أكد الأن",
                      icon: FontAwesomeIcons.solidCircleCheck,
                      text:
                          "يرجي تأكيد هويتك لاستخدام المميزات الخاصه بالدكتور",
                      onTap: () {})
                  .init,
            ),
            const SizedBox(height: 15),
            HeaderContent(
              header: "شرح الإستخدام",
              content: CustomRequest(
                sameContent: false,
                errorText: homeController.errorText,
                status: homeController.videoStatus,
                widget: UsageVideo(
                  src: homeController.explainVideoURL,
                  isNetwork: true,
                  height: 250,
                ),
              ),
            ),
            const SizedBox(height: 15),
            const HeaderContent(
                header: "اهم اللينكات", content: ImportantLinks()),
            const SizedBox(height: 40),
          ]);
    });
  }
}
