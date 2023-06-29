import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/home/home_controller.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_notes.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/home/important_links.dart';
import 'package:roshetta_app/view/widgets/home/usage_video.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

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
            ...verifyAlert(homeController.auth.localUser.value!),
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
                    status: homeController.videoStatus,
                    error: homeController.errorText),
              ),
            ),
            const SizedBox(height: 15),
            homeController.auth.localUser.value?.type == Users.assistant.name
                ? Container(
                    margin: const EdgeInsets.symmetric(horizontal: 8),
                    child: CustomListTile(
                      widget: const Center(
                          child: FaIcon(
                        FontAwesomeIcons.circleExclamation,
                        color: AppColors.primaryColor,
                        size: 30,
                      )),
                      smallTitle: "رقم المعرف (ID)",
                      title: homeController.assistantId!,
                      moreWidget: const CustomText(
                          text:
                              "يمكنك التواصل مع العياده واعطاء رقم المعرف (ID) الخاص بك لهم لاضافتك في العياده",
                          align: TextAlign.start,
                          textType: 5,
                          color: AppColors.greyColor),
                      buttonIcon: Icons.copy,
                      onButtonPressed: () => copyToClip(
                          homeController.assistantId!,
                          successText: "رقم المعرف الخاص بك"),
                    ),
                  )
                : HeaderContent(
                    header: "اهم اللينكات",
                    content: ImportantLinks(
                        role: homeController.auth.localUser.value?.type ??
                            "error")),
            const SizedBox(height: 40),
          ]);
    });
  }

  verifyAlert(LocalUser user) {
    if (user.isVerify == null ||
        user.isVerify == "none" ||
        user.isVerify == "success" ||
        user.isVerify == "waiting") {
      return [const SizedBox()].toList();
    }
    if ((user.type == Users.doctor.name ||
            user.type == Users.pharmacist.name) &&
        (user.isVerify == "none" || user.isVerify == "error")) {
      String text = user.isVerify == "error"
          ? "حدثت مشكله اثناء توثيق حسابك"
          : "يرجي تأكيد هويتك لاستخدام المميزات الخاصه بالدكتور";
      return [
        Container(
          margin: const EdgeInsets.symmetric(horizontal: 8, vertical: 5),
          child: Notes(
              buttonText: "أكد الأن",
              icon: FontAwesomeIcons.solidCircleCheck,
              text: text,
              onTap: () {
                Get.toNamed(AppRoutes.verifyDPAccount);
              }).init,
        ),
        const SizedBox(height: 15),
      ].toList();
    }
  }
}
