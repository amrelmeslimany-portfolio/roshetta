import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/profile/myprofile_controller.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/widgets/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/profile/banner.dart';
import 'package:roshetta_app/view/widgets/profile/bottom_banner.dart';
import 'package:roshetta_app/view/widgets/profile/header.dart';
import 'package:roshetta_app/view/widgets/profile/info_list.dart';

class MyProfile extends StatelessWidget {
  final GlobalKey<ScaffoldState> drawerState;
  const MyProfile({super.key, required this.drawerState});

  @override
  Widget build(BuildContext context) {
    Get.put<MyProfileControllerImp>(MyProfileControllerImp());
    return GetBuilder<MyProfileControllerImp>(builder: (profile) {
      return BodyLayout(
        appbar: CustomAppBar(
                onPressed: () => toggleDrawer(drawerState), isBack: false)
            .init,
        content: [
          ProfileHeader(user: profile.user!),
          const SizedBox(height: 30),
          CustomRequest(
              status: profile.profileStatus,
              errorText: profile.error ?? "",
              widget: Column(
                children: [
                  ProfileBanner(user: profile.information),
                  const SizedBox(height: 30),
                  ProfileInfoList(user: profile.information),
                  const SizedBox(height: 30),
                  ProfileBottomBanner(user: profile.information),
                  const SizedBox(height: 30),
                  UnconstrainedBox(
                    child: BGButton(context,
                            text: "تعديل الحساب",
                            icon: FontAwesomeIcons.userPen,
                            onPressed: () {})
                        .button,
                  )
                ],
              )),
          const SizedBox(height: 40),
        ],
      );
    });
  }
}
