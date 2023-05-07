import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/pharmacist/add_pharmacy_controller.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/view/widgets/clinics/clinic_form.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';

class AddPharmacy extends StatelessWidget {
  AddPharmacy({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final addPharmactController = Get.put(AddPharmacyController());

  @override
  Widget build(BuildContext context) {
    return HomeLayout(
      scaffoldKey: scaffoldKey,
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
                    onSubmit: () {
                      if (addPharmactController.isEdit.value) {
                        addPharmactController.onEditSubmit();
                      } else {
                        addPharmactController.onSubmit();
                      }
                    },
                  ));
            }),
            const SizedBox(height: 50)
          ]),
    );
  }
}
