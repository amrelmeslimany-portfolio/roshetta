import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/doctor/addclinic_controller.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/clinics/clinic_form.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/floating_button.dart';

class AddClinic extends StatelessWidget {
  AddClinic({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final addclinicController = Get.put(AddClinicController());

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      floatingButton: Obx(
        () => CustomFloatingIcon(
            isLoading: addclinicController.addClinicStatus.value ==
                RequestStatus.loading,
            icon: addclinicController.isEdit.value
                ? FontAwesomeIcons.pencil
                : FontAwesomeIcons.circlePlus,
            onPressed: () {
              if (addclinicController.isEdit.value) {
                addclinicController.onEditSubmit();
              } else {
                addclinicController.onSubmit();
              }
            }),
      ),
      body: BodyLayout(
          appbar: CustomAppBar(onPressed: () {
            toggleDrawer(scaffoldKey);
          }).init,
          content: [
            Obx(() {
              return CustomRequest(
                  status: addclinicController.addClinicStatus.value,
                  sameContent: true,
                  widget: ClinicForm(
                    formKey: addclinicController.formkey,
                    isEdit: addclinicController.isEdit.value,
                    imgSrc: addclinicController.imgSrc.value,
                    nameController: addclinicController.name,
                    phoneController: addclinicController.phone,
                    priceController: addclinicController.price,
                    rangeTimeController:
                        addclinicController.rangeTimeController,
                    locationController: addclinicController.location,
                    governorateValue: addclinicController.governorate.value,
                    spcialistValue: addclinicController.spcialist.value,
                    specialistsStatus:
                        addclinicController.specialistsStatus.value,
                    governorateList: addclinicController.governorateList,
                    spcialistsList: addclinicController.spcialistsList,
                    onPickImage: () => addclinicController.onPickImage(),
                    onGovernmentChange: (value) =>
                        addclinicController.onGovernmentChange(value),
                    onSpecialistChange: (value) =>
                        addclinicController.onSpecialistChange(value),
                    onSelectStartTime: () =>
                        addclinicController.onSelectStartTime(),
                  ));
            }),
            const SizedBox(height: 50)
          ]),
    );
  }
}
