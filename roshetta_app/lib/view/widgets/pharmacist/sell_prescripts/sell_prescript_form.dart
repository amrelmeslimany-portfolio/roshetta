import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/pharmacist/sell_prescript_controller.dart';
import 'package:roshetta_app/core/class/fields_interface.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/view/widgets/auth/label_divider.dart';
import 'package:roshetta_app/view/widgets/pharmacist/sell_prescripts/empty_pharmacys.dart';
import 'package:roshetta_app/view/widgets/pharmacist/sell_prescripts/pharmacys_list.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/floating_button.dart';

List<Map<String, String>> numberTypes = [
  {"label": "الرقم القومي", "value": "ssd"},
  {"label": "سيريال الروشته", "value": "ser_id"},
  {"label": "معرف الروشته (ID)", "value": "id"},
];

class SellPrescriptForm extends StatelessWidget {
  SellPrescriptForm({
    super.key,
  });

  final sellController = Get.put(SellPrescriptPharmacy());

  @override
  Widget build(BuildContext context) {
    sellController.checkPharmacyId();
    return Scaffold(
      backgroundColor: AppColors.whiteColor,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        iconTheme: const IconThemeData(color: AppColors.primaryColor, size: 30),
        title: const Text("صرف روشته"),
        centerTitle: true,
        titleTextStyle: Theme.of(context)
            .textTheme
            .titleMedium!
            .copyWith(color: AppColors.primaryColor),
      ),
      floatingActionButton: Obx(
        () => CustomFloatingIcon(
          isLoading:
              sellController.prescriptStatus.value == RequestStatus.loading,
          icon: FontAwesomeIcons.solidPaperPlane,
          onPressed: () {
            sellController.onSubmit();
          },
        ),
      ),
      body: ListView(
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 30, vertical: 20),
            child: Obx(
              () {
                List pharmacys = sellController.initPharmacies();
                return CustomRequest(
                    sameContent: false,
                    status: sellController.pharmacyController.status.value,
                    widget: pharmacys.isEmpty
                        ? const EmptyPharmcys()
                        : Form(
                            key: sellController.formkey,
                            child: CustomRequest(
                                sameContent: true,
                                status: sellController.prescriptStatus.value,
                                widget: Column(
                                  children: [
                                    ChoosePharmacysList(
                                      pharmacys: pharmacys,
                                      pharmacyId:
                                          sellController.pharmacyId.value,
                                      onItemPressed: (value) {
                                        sellController.onChoosePharmacy(value);
                                        print(sellController.pharmacyId);
                                      },
                                    ),
                                    const DividerText(text: "نوع الرقم"),
                                    const SizedBox(height: 10),
                                    CustomDropdown(
                                        context: context,
                                        initalVal: sellController
                                                .numberType.value.isNotEmpty
                                            ? sellController.numberType.value
                                            : numberTypes[0]["value"],
                                        onValidator: (value) =>
                                            dropdownValidator(value),
                                        hintText: "نوع الرقم",
                                        items: _numberTypes(),
                                        onChange: (value) {
                                          sellController
                                              .onNumberTypeChange(value!);
                                        }).dropdown,
                                    const SizedBox(height: 15),
                                    CustomTextField(
                                            context: context,
                                            onValidator: (value) =>
                                                fieldValidor(value!,
                                                    type: FieldsTypes.number),
                                            controller: sellController.numberID,
                                            hintText: "الرقم",
                                            keyboardType: TextInputType.number,
                                            onFieldSubmitted: (_) {
                                              sellController.onSubmit();
                                            },
                                            icon: FontAwesomeIcons.tag)
                                        .textfield,
                                    const SizedBox(height: 15),
                                  ],
                                ))));
              },
            ),
          ),
        ],
      ),
    );
  }

  List<DropdownMenuItem<String>> _numberTypes() {
    return numberTypes
        .map((numberType) => DropdownMenuItem(
              value: numberType["value"],
              child: Text(numberType["label"]!),
            ))
        .toList();
  }
}
