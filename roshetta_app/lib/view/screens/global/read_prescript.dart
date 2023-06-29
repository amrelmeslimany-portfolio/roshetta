import 'dart:io';

import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/global/read_prescript_controller.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_notes.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_boxes.dart';
import 'package:roshetta_app/view/widgets/shared/custom_listtile.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';

class ReadPrescript extends StatelessWidget {
  ReadPrescript({super.key});
  final GlobalKey<ScaffoldState> scaffold = GlobalKey<ScaffoldState>();
  final ReadPrescriptController readPrescriptController =
      Get.put(ReadPrescriptController());
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
            HeaderContent(
                header: "قراءة روشتة",
                content: Column(
                  children: [
                    Notes(
                            icon: FontAwesomeIcons.exclamation,
                            text:
                                "لن يتم حفظ الصورة , عند تحليلها والانتهاء والخروج من هذة الصفحة ستتم الحذف")
                        .init,
                    const SizedBox(height: 15),
                    Obx(
                      () => CustomRequest(
                        sameContent: true,
                        status: readPrescriptController.imageStatus.value,
                        widget: InkWell(
                          onTap: () => readPrescriptController.onUploadImage(),
                          child: CustomListTileUploader(
                            isCurrentError: readPrescriptController.isError,
                            file: readPrescriptController.imgFile,
                          ),
                        ),
                      ),
                    ),
                  ],
                )),
            const SizedBox(height: 30),
            HeaderContent(
                header: "النتيجة",
                content: CustomShadowBox(
                  padding: 10,
                  width: double.infinity,
                  child: Obx(
                    () => CustomRequest(
                      sameContent: false,
                      status: readPrescriptController.imageStatus.value,
                      widget: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          if (readPrescriptController.imageStatus.value !=
                              RequestStatus.empty) ...[
                            Align(
                              alignment: Alignment.center,
                              child: ICButton(
                                      onPressed: () {
                                        copyToClip(readPrescriptController
                                            .extracted.value);
                                      },
                                      iconColor: AppColors.primaryColor,
                                      padding: const EdgeInsets.all(6),
                                      size: 20,
                                      icon: Icons.copy)
                                  .bordered,
                            ),
                            const SizedBox(height: 10),
                            Text(
                              readPrescriptController.extracted.value,
                              textAlign: TextAlign.center,
                              style: const TextStyle(fontSize: 17),
                            ),
                            const Divider(),
                          ],
                          readPrescriptController.isImage
                              ? Image.file(
                                  File(readPrescriptController.imgFile!.path),
                                  height: 300,
                                  width: double.infinity,
                                  fit: BoxFit.fill,
                                )
                              : Image.network(AssetPaths.emptyIMG, height: 300),
                        ],
                      ),
                    ),
                  ),
                )),
            const SizedBox(
              height: 35,
            )
          ],
        ));
  }
}
