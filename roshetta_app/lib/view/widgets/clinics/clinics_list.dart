import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/date_functions.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';

class ClinicsList extends StatelessWidget {
  final Users role;
  final Rx<RequestStatus> status;
  final Rx<RequestStatus> loginStatus;
  final List clinics;
  final Function(String) onLogin;
  final Function(Map) onButtonPressed;

  const ClinicsList(
      {super.key,
      required this.status,
      required this.clinics,
      required this.onLogin,
      required this.onButtonPressed,
      required this.role,
      required this.loginStatus});

  @override
  Widget build(BuildContext context) {
    return Obx(
      () => CustomRequest(
          sameContent: false,
          status: status.value,
          widget: ListView.separated(
            separatorBuilder: (context, index) {
              return const SizedBox(height: 15);
            },
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: clinics.length,
            itemBuilder: (context, index) {
              var item = clinics[index];
              String status = formatCloseOrNot(item["status"]);
              Color? color =
                  item["status"] == "1" ? AppColors.primaryColor : null;

              return Obx(
                () => CustomListTile(
                  onTilePressed: () {
                    if (loginStatus.value == RequestStatus.loading) return;
                    onLogin(item["id"]);
                  },
                  img: item["logo"] ?? AssetPaths.emptyIMG,
                  title: item["name"],
                  smallTitle: status,
                  smallTitleColor: color,
                  middleWidget: loginStatus.value == RequestStatus.loading
                      ? Lottie.asset(AssetPaths.loading, height: 40)
                      : null,
                  descriptionIcon: FontAwesomeIcons.solidClock,
                  description: getRangeTime(
                      start: item['start_working'], end: item['end_working']),
                  buttonIcon: role == Users.doctor
                      ? _buttonIconDoctor(item["isVerify"])
                      : Icons.more_horiz_outlined,
                  onButtonPressed: loginStatus.value == RequestStatus.loading
                      ? null
                      : () {
                          onButtonPressed(item);
                        },
                ),
              );
            },
          )),
    );
  }

  _buttonIconDoctor(String isVerified) {
    switch (isVerified) {
      case "success":
        return Icons.more_horiz_outlined;
      case "waiting":
        return Icons.pending_actions_rounded;
      default:
        return Icons.check;
    }
  }
}
