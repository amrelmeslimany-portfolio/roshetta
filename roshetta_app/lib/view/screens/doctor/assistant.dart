import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/doctor/clinics_controller.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/clinics/assistant_content.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';

class DoctorAssistant extends StatelessWidget {
  DoctorAssistant({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final clinicController = Get.find<DoctorClinicsController>();

  @override
  Widget build(BuildContext context) {
    return GetBuilder<DoctorClinicsController>(
        initState: (_) async {
          await clinicController.onGetAssistant();
        },
        builder: (_) => HomeLayout(
            scaffoldKey: scaffoldKey,
            floatingButton: SizedBox(
              height: 40,
              child: FloatingActionButton(
                elevation: 3,
                backgroundColor: AppColors.primaryColor,
                onPressed: () {
                  if (clinicController.assistantStatus ==
                      RequestStatus.loading) {
                    return;
                  }
                  if (clinicController.assistant != null) {
                    confirmDialog(context,
                        text: "متاكد من حذف المساعد ؟",
                        onConfirm: () => clinicController.onDeleteAssistant());
                  } else {
                    clinicController.onAddAssistant();
                  }
                },
                child: Icon(
                  _submitButtonIcons(),
                  color: Colors.white,
                ),
              ),
            ),
            body: BodyLayout(
                appbar: CustomAppBar(onPressed: () {
                  toggleDrawer(scaffoldKey);
                }).init,
                content: [
                  Container(
                      margin: const EdgeInsets.symmetric(horizontal: 8),
                      child: CustomRequest(
                          sameContent: true,
                          status: clinicController.assistantStatus,
                          widget: DoctorAssistantContent(
                              idController: clinicController.idAssistController,
                              formKey: clinicController.idAssistKeyForm,
                              assistant: clinicController.assistant))),
                  const SizedBox(height: 70)
                ])));
  }

  IconData _submitButtonIcons() {
    if (clinicController.assistantStatus == RequestStatus.loading) {
      return FontAwesomeIcons.spinner;
    } else {
      if (clinicController.assistant != null) {
        return Icons.delete;
      } else {
        return Icons.person_add_alt_1_sharp;
      }
    }
  }
}
