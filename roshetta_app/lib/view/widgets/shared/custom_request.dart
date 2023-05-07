import 'package:flutter/widgets.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/view/widgets/auth/auth_dialogs.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class CustomRequest extends StatelessWidget {
  final RequestStatus status;
  final Widget widget;
  final bool? sameContent;
  final String? errorText;

  const CustomRequest(
      {super.key,
      required this.status,
      required this.widget,
      this.sameContent,
      this.errorText});

  @override
  Widget build(BuildContext context) {
    if (sameContent == null || sameContent == false) {
      return _changeContentWidget(errorText);
    } else {
      return _sameContentWidget(context);
    }
  }

  Widget _sameContentWidget(BuildContext context) {
    switch (status) {
      case RequestStatus.loading:
        return _lottieAndText(AssetPaths.loading, "جاري التحميل",
            repeat: true, size: 100);
      default:
        return widget;
    }
  }

  Widget _changeContentWidget(String? failMessage) {
    switch (status) {
      case RequestStatus.loading:
        return _lottieAndText(AssetPaths.loading, "جاري التحميل",
            repeat: true, size: 100);

      case RequestStatus.offlineFailure:
        return _lottieAndText(AssetPaths.offline, "تأكد من اتصالك بالانترنت");

      case RequestStatus.failure:
      case RequestStatus.serverFailure:
        return _lottieAndText(AssetPaths.server, "هناك مشكله من السيرفر");

      case RequestStatus.empty:
        return _lottieAndText(AssetPaths.empty, "لم يتم العثور علي بيانات");

      case RequestStatus.userFailure:
        return _lottieAndText(
            AssetPaths.error, size: 60, failMessage ?? "حدثت مشكله ما");

      default:
        return widget;
    }
  }

  Widget _lottieAndText(String src, String content,
      {bool? repeat, double? size}) {
    return Center(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Lottie.asset(src,
              repeat: repeat ?? false, width: size ?? 200, height: size ?? 200),
          CustomText(
            text: content,
            color: AppColors.lightTextColor,
            textType: 3,
          )
        ],
      ),
    );
  }
}

class DialogRequestMessages {
  final RequestStatus status;
  final dynamic failureText;
  final BuildContext context;

  DialogRequestMessages(this.context,
      {required this.status, this.failureText}) {
    dialogsRequestMessages();
  }

  dialogsRequestMessages() {
    switch (status) {
      case RequestStatus.offlineFailure:
        displayDialog(context, "تأكد من اتصالك بالانترنت");
        break;

      case RequestStatus.serverFailure:
        displayDialog(context, "هناك مشكله من السيرفر");
        break;

      case RequestStatus.failure:
        displayDialog(context, "حدثت مشكله ما");
        break;

      case RequestStatus.userFailure:
        displayDialog(context, failureText!);
        break;

      default:
        break;
    }
  }

  displayDialog(BuildContext context, dynamic text) {
    Get.defaultDialog(
        content: AuthDiologs(icon: FontAwesomeIcons.xmark, content: text),
        contentPadding: const EdgeInsets.all(15),
        barrierDismissible: true,
        actions: [
          BGButton(context,
              small: true,
              text: "اعاده المحاوله",
              onPressed: () => Get.back()).button
        ]);
  }
}
