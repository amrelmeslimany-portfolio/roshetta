import 'package:flutter/material.dart';
import 'package:get/get.dart';

import 'package:lottie/lottie.dart';
import 'package:roshetta_app/controllers/doctor_pharmacy/verifyplace_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/widgets/auth/auth_dialogs.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class VerifyPlace extends StatelessWidget {
  final String? type;
  final String? placeID;
  final String? placeName;
  VerifyPlace(
      {super.key,
      required this.type,
      required this.placeID,
      required this.placeName});

  final verifyController = Get.put(VerifyPlaceController());

  @override
  Widget build(BuildContext context) {
    String placeText = type == "pharmacy" ? "الصيدلية" : "العيادة";
    return Scaffold(
      backgroundColor: AppColors.whiteColor,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        iconTheme: const IconThemeData(color: AppColors.primaryColor, size: 30),
      ),
      body: Container(
        alignment: Alignment.center,
        margin: const EdgeInsets.only(top: 60),
        padding: const EdgeInsets.all(15),
        child: ListView(
            children: type == null || placeID == null || placeID!.isEmpty
                ? _whenError()
                : [
                    AuthDiologs(
                      icon: Icons.verified,
                      title: "توثيق $placeName",
                    ),
                    CustomText(
                      text: "قم برفع صورة الترخيص الخاصه ب$placeText للتوثيق",
                      textType: 3,
                      color: AppColors.lightTextColor,
                    ),
                    const SizedBox(height: 15),
                    Obx(() {
                      return CustomRequest(
                        sameContent: true,
                        status: verifyController.status.value,
                        widget: InkWell(
                          onTap: () {
                            verifyController.onUploadPlaceImg();
                          },
                          child: UnconstrainedBox(
                            child: CustomListTileUploader(
                              width: 320,
                              isCurrentError: verifyController.isError.value,
                              file: verifyController.file,
                              onDelete: () {
                                verifyController.onDeleteImg();
                              },
                            ),
                          ),
                        ),
                      );
                    }),
                    const SizedBox(height: 15),
                    UnconstrainedBox(
                      child: BGButton(
                        context,
                        text: "ارسال",
                        onPressed: () {
                          verifyController.onSubmit(placeID, type: type);
                        },
                      ).button,
                    )
                  ]),
      ),
    );
  }

  List<Widget> _whenError() {
    return [
      const SizedBox(height: 60),
      Lottie.asset(AssetPaths.error, height: 80),
      const SizedBox(height: 15),
      const CustomText(
          text: "لا يمكنك الدخول الي هذة الصفحة مباشره",
          textType: 3,
          color: AppColors.lightTextColor)
    ];
  }
}
