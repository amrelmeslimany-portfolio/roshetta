import 'dart:async';
import 'dart:math';

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/authentication_controller.dart';
import 'package:roshetta_app/core/class/crud.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/data/models/chat.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/data/source/remote/doctor/chat_data.dart';

class ChatController extends GetxController {
  final auth = Get.find<AuthenticationController>();
  late LocalUser? user = auth.localUser.value;
  DoctorChatData requests = DoctorChatData(Get.find<Crud>());
  late TextEditingController messageText = TextEditingController();
  late Rx<RequestStatus> messagesStatus = RequestStatus.none.obs;
  late Rx<RequestStatus> sendStatus = RequestStatus.none.obs;
  final RxList<MessageModel> messages = RxList<MessageModel>([]);
  final ScrollController scrollController = ScrollController();
  late Timer? timer;

  @override
  void onInit() {
    super.onInit();
    getMessages();
    timer = Timer.periodic(
      const Duration(seconds: 10),
      (_) async {
        print("message");
        var response = await requests.messages(getToken(auth)!);
        if (checkResponseStatus(response) == RequestStatus.success) {
          _whenMessagesGetSuccess(response, istimer: true);
        }
      },
    );
  }

  bool _checkText() {
    if (messageText.text.trim().isEmpty) return false;
    return true;
  }

  initialScroll() {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      scrollController.animateTo(0,
          duration: const Duration(milliseconds: 300), curve: Curves.bounceIn);
    });
  }

  getMessages() async {
    messagesStatus.value = RequestStatus.loading;
    var response = await requests.messages(getToken(auth)!);
    messagesStatus.value = checkResponseStatus(response);
    print(response);
    if (messagesStatus.value == RequestStatus.success) {
      _whenMessagesGetSuccess(response);
    } else {
      handleSnackErrors(response);
    }
  }

  onSendMessage() async {
    if (!_checkText()) {
      _handleMessageEmptyError();
      return;
    }
    sendStatus.value = RequestStatus.loading;
    var response = await requests.messages(getToken(auth)!,
        message: messageText.text.trim());
    sendStatus.value = checkResponseStatus(response);
    print(response);
    if (sendStatus.value == RequestStatus.success) {
      messages.insert(
          0, MessageModel.fromJson(response["Data"], userImg: user!.image));
      messageText.clear();
      initialScroll();
    } else {
      handleSnackErrors(response);
    }
  }

  onDeleteMessage(String messageId) async {
    messagesStatus.value = RequestStatus.loading;
    var response =
        await requests.messages(getToken(auth)!, messageId: messageId);
    messagesStatus.value = checkResponseStatus(response);
    print(response);
    if (Get.isDialogOpen == true) Get.back();
    if (messagesStatus.value == RequestStatus.success) {
      int index = messages.indexWhere((element) => element.id == messageId);
      if (index >= 0) messages.removeAt(index);
      snackbar(title: "حذف الرسالة", content: response["Message"]);
    } else {
      handleSnackErrors(response);
      messagesStatus.value = RequestStatus.success;
    }
  }

  _handleMessageEmptyError() {
    if (Get.isSnackbarOpen) return;
    snackbar(
      isError: true,
      title: "ارسال الرساله",
      content: "يجب ادخال نص الرسالة",
    );
  }

  _whenMessagesGetSuccess(response, {bool? istimer = false}) {
    if (response["Data"] == null) {
      messagesStatus.value = RequestStatus.empty;
      return;
    }
    if (messages.isNotEmpty) messages.clear();

    for (var item in response["Data"]) {
      messages.add(MessageModel.fromJson(item,
          userImg: user!.image ?? AssetPaths.emptyIMG));
    }

    if (!istimer!) {
      initialScroll();
    }
  }

  @override
  void onClose() {
    timer?.cancel();
    scrollController.dispose();
    messageText.dispose();
    super.onClose();
  }
}
