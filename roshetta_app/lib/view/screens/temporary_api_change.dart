import 'dart:math';

import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/constants/app_api_urls.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/services/init_services.dart';
import 'package:roshetta_app/core/shared/custom_notes.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/floating_button.dart';
import 'package:shared_preferences/shared_preferences.dart';

class APIInitial {
  final String? url;
  final bool isApi;
  APIInitial({this.url, required this.isApi});
}

class APIChangerController extends GetxController {
  final InitServices _services = Get.find<InitServices>();
  final TextEditingController url = TextEditingController();
  final GlobalKey<FormState> formKey = GlobalKey();
  final isAPISaved = false.obs;
  late SharedPreferences store;
  final storedURL = "".obs;

  onSkip() {
    Get.toNamed(AppRoutes.intro);
  }

  onSubmit() {
    if (isAPISaved.value) {
      isAPISaved.value = false;
      url.text = storedURL.value;
      return;
    }
    if (formKey.currentState!.validate()) {
      confirmDialog(Get.context!,
          text: "متاكد من تغيير الرابط نهائيا ؟", onConfirm: onConfirmChange);
    }
  }

  void onConfirmChange() {
    if (Get.isDialogOpen == true) Get.back();
    store.setString(ApiUrls.clientAPI, url.text.trim());
    isAPISaved.value = true;
    _initIsSaved();
    url.clear();
    onSkip();
  }

  void _initIsSaved() {
    storedURL.value = store.getString(ApiUrls.clientAPI) ?? "";
    if (storedURL.isNotEmpty) {
      isAPISaved.value = true;
    } else {
      isAPISaved.value = false;
    }
  }

  @override
  void onInit() {
    super.onInit();
    store = _services.sharedPreferences;
    _initIsSaved();
  }

  @override
  void onClose() {
    url.dispose();
    super.onClose();
  }
}

class APIChanger extends StatelessWidget {
  APIChanger({super.key});
  final controller = Get.put(APIChangerController());

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("API لينك"),
        centerTitle: true,
      ),
      floatingActionButton: Obx(
        () => CustomFloatingIcon(
            icon: controller.isAPISaved.value
                ? FontAwesomeIcons.pencil
                : FontAwesomeIcons.solidFloppyDisk,
            onPressed: () {
              controller.onSubmit();
            }),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.fromLTRB(30, 30, 30, 0),
          child: Column(
            children: [
              Notes(
                      icon: FontAwesomeIcons.circleExclamation,
                      text:
                          "صفحة مؤقتة اثناء التطوير والبرمجه فقط لتغيير ال API")
                  .init,
              const SizedBox(height: 30),
              Expanded(
                child: Container(
                  margin: EdgeInsets.only(top: Get.height / 20),
                  child: Form(
                      key: controller.formKey,
                      child: Obx(
                        () => ListView(
                          children: [
                            _listTile(controller.storedURL.value.isEmpty
                                ? dotenv.get(ApiUrls.clientAPI)
                                : controller.storedURL.value),
                            const SizedBox(height: 15),
                            if (controller.isAPISaved.isFalse)
                              TextFormField(
                                controller: controller.url,
                                maxLines: null,
                                keyboardType: TextInputType.url,
                                textDirection: TextDirection.ltr,
                                style: const TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.normal,
                                ),
                                decoration: const InputDecoration(
                                    labelText: "لينك API",
                                    helperText:
                                        "مثال: (http://localhost:3000/roshetta/api)  بدوون سلاش في الاخر"),
                                validator: (value) => _validator(value!),
                                onFieldSubmitted: (_) => controller.onSubmit(),
                              ),
                            const SizedBox(height: 30),
                            Container(
                              alignment: Alignment.center,
                              child: TextButton.icon(
                                onPressed: () {
                                  controller.onSkip();
                                },
                                icon: const Icon(Icons.navigate_before),
                                label: const Text("تخطي"),
                              ),
                            )
                          ],
                        ),
                      )),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  String? _validator(String value) {
    if (value.isEmpty) return "يرجي ادخال رابط ال API";
    return null;
  }

  ListTile _listTile(String url) {
    return ListTile(
      title: CustomText(
        text: url,
        textType: 2,
        color: AppColors.primaryTextColor,
        align: TextAlign.end,
      ),
      subtitle: const CustomText(
        text: "رابط الApi الحالي",
        textType: 4,
        color: AppColors.lightTextColor,
        align: TextAlign.end,
      ),
      leading: const Icon(Icons.link),
    );
  }
}
