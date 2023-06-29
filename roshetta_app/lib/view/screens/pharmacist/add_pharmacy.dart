import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/pharmacist/add_pharmacy_controller.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/clinics/clinic_form.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/shared/floating_button.dart';

class AddPharmacy extends StatelessWidget {
  AddPharmacy({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final addPharmactController = Get.put(AddPharmacyController());

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
      floatingButton: Obx(
        () => CustomFloatingIcon(
            isLoading: addPharmactController.addPharmacyStatus.value ==
                RequestStatus.loading,
            icon: addPharmactController.isEdit.value
                ? FontAwesomeIcons.pencil
                : FontAwesomeIcons.circlePlus,
            onPressed: () {
              if (addPharmactController.isEdit.value) {
                addPharmactController.onEditSubmit();
              } else {
                addPharmactController.onSubmit();
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
                  status: addPharmactController.addPharmacyStatus.value,
                  sameContent: true,
                  widget: ClinicForm(
                    placeType: Users.pharmacist,
                    formKey: addPharmactController.formkey,
                    isEdit: addPharmactController.isEdit.value,
                    imgSrc: addPharmactController.imgSrc.value,
                    nameController: addPharmactController.name,
                    phoneController: addPharmactController.phone,
                    rangeTimeController:
                        addPharmactController.rangeTimeController,
                    locationController: addPharmactController.location,
                    governorateValue: addPharmactController.governorate.value,
                    governorateList: addPharmactController.governorateList,
                    onPickImage: () => addPharmactController.onPickImage(),
                    onGovernmentChange: (value) =>
                        addPharmactController.onGovernmentChange(value),
                    onSelectStartTime: () =>
                        addPharmactController.onSelectStartTime(),
                  ));
            }),
            const SizedBox(height: 50)
          ]),
    );
  }
}
