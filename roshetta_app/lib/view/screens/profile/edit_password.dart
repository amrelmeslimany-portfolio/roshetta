import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/profile/editpassword_controller.dart';
import 'package:roshetta_app/core/class/fields_interface.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/profile/header.dart';
import 'package:roshetta_app/view/widgets/shared/floating_button.dart';

class EditPassword extends StatelessWidget {
  const EditPassword({super.key});

  @override
  Widget build(BuildContext context) {
    GlobalKey<ScaffoldState> scaffold = GlobalKey<ScaffoldState>();
    Get.put(EditPasswordControllerImp());
    return GetBuilder<EditPasswordControllerImp>(builder: (editpassword) {
      return HomeLayout(
        scaffoldKey: scaffold,
        floatingButton: CustomFloatingIcon(
            isLoading: editpassword.status == RequestStatus.loading,
            icon: FontAwesomeIcons.solidFloppyDisk,
            onPressed: () {
              editpassword.onSubmit();
            }),
        body: BodyLayout(
            appbar: CustomAppBar(
                isBack: true,
                onPressed: () {
                  toggleDrawer(scaffold);
                }).init,
            content: [
              ProfileHeader(
                  title: editpassword.auth.localUser.value!.name!,
                  image: editpassword.auth.localUser.value!.image,
                  subTitle: editpassword.auth.localUser.value!.ssd!,
                  isVerify: editpassword.auth.localUser.value!.isVerify,
                  icon: FontAwesomeIcons.solidIdCard),
              const SizedBox(height: 30),
              Form(
                  key: editpassword.form,
                  child: CustomRequest(
                    sameContent: true,
                    status: editpassword.status,
                    widget: Column(
                      children: [
                        CustomTextField(
                          context: context,
                          onValidator: (value) => fieldValidor(value!),
                          controller: editpassword.password,
                          hintText: "كلمة المرور",
                          icon: passwordVisibleIcon(
                              editpassword.isVisiblePassword),
                          secure: editpassword.isVisiblePassword,
                          keyboardType: TextInputType.visiblePassword,
                          passwordTap: () {
                            editpassword.onPasswordVisibleChange();
                          },
                        ).textfield,
                        const SizedBox(height: 15),
                        CustomTextField(
                          context: context,
                          onValidator: (value) {
                            return fieldValidor(value!,
                                type: FieldsTypes.repassword,
                                passwordsEquals:
                                    editpassword.checkPasswordEquals());
                          },
                          controller: editpassword.repassword,
                          hintText: "اعد كلمة المرور",
                          icon: passwordVisibleIcon(
                              editpassword.isVisiblePassword),
                          secure: editpassword.isVisiblePassword,
                          keyboardType: TextInputType.visiblePassword,
                          passwordTap: () {
                            editpassword.onPasswordVisibleChange();
                          },
                        ).textfield,
                      ],
                    ),
                  )),
            ]),
      );
    });
  }
}
