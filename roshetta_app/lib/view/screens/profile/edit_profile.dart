import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:lottie/lottie.dart';
import 'package:roshetta_app/controllers/profile/editprofile_controller.dart';
import 'package:roshetta_app/core/class/fields_interface.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';

import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/floating_button.dart';

class EditProfile extends StatelessWidget {
  const EditProfile({super.key});

  @override
  Widget build(BuildContext context) {
    GlobalKey<ScaffoldState> scaffold = GlobalKey<ScaffoldState>();
    Get.put<EditProfileControllerImp>(EditProfileControllerImp());
    return GetBuilder<EditProfileControllerImp>(
        builder: (editprofile) => HomeLayout(
              scaffoldKey: scaffold,
              floatingButton: CustomFloatingIcon(
                  isLoading: editprofile.userStatus == RequestStatus.loading,
                  icon: FontAwesomeIcons.pencil,
                  onPressed: () {
                    editprofile.onEdit();
                  }),
              body: BodyLayout(
                  appbar: CustomAppBar(onPressed: () {
                    toggleDrawer(scaffold);
                  }).init,
                  content: [
                    editprofile.pageStatus == RequestStatus.userFailure
                        ? Column(
                            children: [
                              Lottie.asset(AssetPaths.error, height: 100),
                              const SizedBox(height: 5),
                              CustomText(
                                text: editprofile.error!,
                                color: AppColors.lightTextColor,
                                textType: 3,
                              )
                            ],
                          )
                        : CustomRequest(
                            sameContent: true,
                            status: editprofile.userStatus,
                            widget: Form(
                                key: editprofile.formkey,
                                child: Column(
                                  children: [
                                    UploadImageCircle(
                                        imgWidgt: shadowCircleAvatar(
                                            editprofile.picture,
                                            isNetwork: true,
                                            radius: 60),
                                        onUpload: () {
                                          editprofile.onUploadImage();
                                        }).defaultField,
                                    const SizedBox(height: 30),
                                    CustomTextField(
                                            context: context,
                                            onValidator: (value) =>
                                                fieldValidor(value!,
                                                    type: FieldsTypes.phone),
                                            controller: editprofile.phone,
                                            hintText: "رقم الهاتف",
                                            keyboardType: TextInputType.phone,
                                            icon: FontAwesomeIcons.phone)
                                        .textfield,
                                    const SizedBox(height: 15),
                                    CustomDropdown(
                                        context: context,
                                        initalVal: editprofile.governorate,
                                        onValidator: (value) =>
                                            dropdownValidator(value),
                                        hintText: "المحافظة",
                                        items: editprofile.governmentsList,
                                        onChange: (value) {
                                          editprofile
                                              .onGovernmentChange(value!);
                                        }).dropdown,
                                    const SizedBox(height: 15),
                                    patientFields(context, editprofile),
                                    Container(
                                      alignment: Alignment.topRight,
                                      width: 300,
                                      child: InkWell(
                                        onTap: () {
                                          editprofile.goToEditPassword();
                                        },
                                        child: const CustomText(
                                          text: "تغيير كلمة المرور",
                                          color: AppColors.primaryColor,
                                          textType: 3,
                                          fontWeight: FontWeight.w600,
                                        ),
                                      ),
                                    ),
                                  ],
                                )),
                          )
                  ]),
            ));
  }

  Widget patientFields(
      BuildContext context, EditProfileControllerImp controller) {
    if (controller.user == null ||
        controller.user?.role != Users.patient.name) {
      return const SizedBox();
    } else {
      return Column(
        children: [
          CustomTextField(
            context: context,
            onValidator: (value) => fieldValidor(value!,
                type: FieldsTypes.number, min: 5, max: 350),
            controller: controller.weight,
            hintText: "الوزن بال كيلوجرام",
            icon: FontAwesomeIcons.weightHanging,
            keyboardType: TextInputType.number,
          ).textfield,
          const SizedBox(height: 15),
          CustomTextField(
            context: context,
            onValidator: (value) => fieldValidor(value!,
                type: FieldsTypes.number, min: 50, max: 300),
            controller: controller.height,
            hintText: "الطول بال سم",
            icon: FontAwesomeIcons.ruler,
            keyboardType: TextInputType.number,
          ).textfield,
          const SizedBox(height: 15),
        ],
      );
    }
  }
}
