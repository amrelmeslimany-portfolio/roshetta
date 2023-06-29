import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/doctor/chat_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/view/widgets/clinics/chat/chat_header.dart';
import 'package:roshetta_app/view/widgets/clinics/chat/chat_send_form.dart';
import 'package:roshetta_app/view/widgets/clinics/chat/message_container.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';

class DoctorsChat extends StatelessWidget {
  DoctorsChat({super.key});
  final chat = Get.put(ChatController());
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
          child: Obx(
        () => Column(
          children: [
            ChatHeader(
                messagesCounter: chat.messages.length,
                img: chat.user != null || chat.user!.image == null
                    ? chat.user!.image!
                    : AssetPaths.emptyIMG),
            Expanded(
              child: CustomRequest(
                status: chat.messagesStatus.value,
                sameContent: true,
                widget: Container(
                  color: AppColors.lightenWhiteColor.withOpacity(0.15),
                  width: double.maxFinite,
                  child: chat.messages.isNotEmpty
                      ? ListView.builder(
                          reverse: true,
                          controller: chat.scrollController,
                          itemCount: chat.messages.length,
                          itemBuilder: (context, index) {
                            return MessageContainer(
                                topMargin: index == chat.messages.length - 1,
                                message: chat.messages[index],
                                onDelete: (id) {
                                  confirmDialog(context,
                                      text: "هل متاكد من حذف الرساله ؟",
                                      onConfirm: () {
                                    chat.onDeleteMessage(id);
                                  });
                                });
                          },
                        )
                      : emptyLottieList(text: "لا يوجد رسائل"),
                ),
              ),
            ),
            ChatFormSend(
                textController: chat.messageText,
                status: chat.sendStatus.value,
                onSend: () {
                  chat.onSendMessage();
                })
          ],
        ),
      )),
    );
  }
}
