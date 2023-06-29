import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/profile/myprofile_controller.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
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
    final profile = Get.put(MyProfileController());

    return BodyLayout(
      onRefresh: () async => profile.getProfileData(),
      appbar: CustomAppBar(
              onPressed: () => toggleDrawer(drawerState), isBack: false)
          .init,
      content: [
        Obx(() {
          return Column(
            children: [
              ProfileHeader(
                image: profile.auth.localUser.value?.image,
                title: profile.auth.localUser.value!.name!,
                subTitle: profile.auth.localUser.value!.ssd!,
                isVerify: profile.auth.localUser.value?.isVerify,
                icon: FontAwesomeIcons.solidIdCard,
              ),
              const SizedBox(height: 30),
              CustomRequest(
                  status: profile.profileStatus.value,
                  errorText: profile.error.value,
                  widget: Column(
                    children: [
                      ProfileBanner(user: profile.information.value),
                      const SizedBox(height: 30),
                      ProfileInfoList(
                        specialist: profile.information.value.specialist,
                        email: profile.information.value.email,
                        phone: profile.information.value.phoneNumber,
                        governorate: profile.information.value.governorate,
                        height: profile.information.value.height,
                        weight: profile.information.value.weight,
                      ),
                      const SizedBox(height: 30),
                      ProfileBottomBanner(user: profile.information.value),
                      const SizedBox(height: 30),
                      UnconstrainedBox(
                        child: BGButton(context,
                            text: "تعديل الحساب",
                            icon: FontAwesomeIcons.userPen, onPressed: () {
                          profile.goToEditProfile();
                        }).button,
                      )
                    ],
                  )),
              const SizedBox(height: 40),
            ],
          );
        })
      ],
    );
  }
}
