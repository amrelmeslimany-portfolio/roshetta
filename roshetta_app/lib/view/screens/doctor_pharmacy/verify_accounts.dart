import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/controllers/doctor_pharmacy/verifyaccounts_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_notes.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';

class VerifyAccount extends StatelessWidget {
  VerifyAccount({super.key});

  final verifyController =
      Get.put<VerifyAccountsController>(VerifyAccountsController());
  final GlobalKey<ScaffoldState> scaffold = GlobalKey<ScaffoldState>();

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
        scaffoldKey: scaffold,
        body: BodyLayout(
          appbar: CustomAppBar(
                  onPressed: () {
                    toggleDrawer(scaffold);
                  },
                  isBack: true)
              .init,
          content: [
            Obx(
              () {
                var isVerified =
                    verifyController.auth.localUser.value!.isVerify;
                return HeaderContent(
                    header: "توثيق الحساب",
                    content: isVerified != null && isVerified == "none"
                        ? Notes(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                icon: Icons.verified,
                                text:
                                    "يجب توثيق الحساب لنتأكد من مصداقية انك فعلا دكتور, وهذا امر مهم للغايه لانه لا يمكنك استخدام مميزات التطبيق إلا بتوثيق حسابك عن طريق رفع البيانات المطلوبه منك خلال هذة الصفحة وانتظار قبولها اذا كانت صحيحه فعليا.")
                            .init
                        : const SizedBox());
              },
            ),
            const SizedBox(height: 30),
            Obx(() {
              var isVerified = verifyController.auth.localUser.value!.isVerify;
              if (isVerified == "success") {
                return getIMGText(AssetPaths.success, "تم توثيق الحساب بنجاح");
              } else if (isVerified == "waiting") {
                return getIMGText(AssetPaths.waitng, "جاري مراجعه المسؤلين");
              } else {
                return Column(
                  children: [
                    ...verifyController.imagesList.map((element) {
                      return Container(
                          margin: const EdgeInsets.fromLTRB(8, 0, 8, 20),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              CustomText(
                                  text: element["label"],
                                  align: TextAlign.start,
                                  textType: 5,
                                  color: AppColors.primaryTextColor),
                              const SizedBox(height: 10),
                              InkWell(
                                onTap: () {
                                  verifyController.onOpenUploaderSheet(element);
                                },
                                child: Obx(() => CustomRequest(
                                      sameContent: true,
                                      status:
                                          verifyController.uploadStatus.value,
                                      widget: CustomListTileUploader(
                                        isCurrentError: verifyController
                                            .isCurrentError(element),
                                        file: verifyController
                                            .onCheckImg(element)["isImg"],
                                        onDelete: () {
                                          verifyController
                                              .onDeleteImage(element);
                                        },
                                      ),
                                    )),
                              )
                            ],
                          ));
                    }).toList(),
                    UnconstrainedBox(
                        child: BGButton(context, text: "توثيق", onPressed: () {
                      verifyController.onSubmit();
                    }).button),
                  ],
                );
              }
            }),
            const SizedBox(height: 50)
          ],
        ));
  }

  BoxDecoration _errorDecoration() {
    return BoxDecoration(
        border: Border.all(color: Colors.red, width: 1),
        color: AppColors.whiteColor,
        borderRadius: const BorderRadius.all(Radius.circular(15)),
        boxShadow: [
          BoxShadow(
              spreadRadius: 1,
              color: Colors.black.withOpacity(0.06),
              blurRadius: 8)
        ]);
  }

  Column getIMGText(String img, String content) {
    return Column(
      children: [
        Lottie.asset(img, height: 200, width: 200),
        CustomText(
          text: content,
          color: AppColors.lightTextColor,
          textType: 3,
        )
      ],
    );
  }
}
