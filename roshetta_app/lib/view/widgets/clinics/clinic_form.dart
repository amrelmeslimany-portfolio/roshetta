import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/core/class/fields_interface.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/view/widgets/auth/label_divider.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class ClinicForm extends StatelessWidget {
  final Users? placeType;
  final bool isEdit;
  final String imgSrc;
  final String? spcialistValue;
  final String governorateValue;
  final GlobalKey<FormState> formKey;
  final List<DropdownMenuItem<String>>? spcialistsList;
  final List<DropdownMenuItem<String>> governorateList;
  final RequestStatus? specialistsStatus;
  final TextEditingController nameController;
  final TextEditingController phoneController;
  final TextEditingController? priceController;
  final TextEditingController locationController;
  final TextEditingController rangeTimeController;
  final Function() onPickImage;
  final Function() onSelectStartTime;
  final Function() onSubmit;
  final Function(String)? onSpecialistChange;
  final Function(String) onGovernmentChange;
  const ClinicForm({
    super.key,
    required this.formKey,
    required this.isEdit,
    required this.imgSrc,
    required this.governorateValue,
    required this.governorateList,
    required this.nameController,
    required this.phoneController,
    required this.locationController,
    required this.rangeTimeController,
    required this.onPickImage,
    required this.onSelectStartTime,
    required this.onSubmit,
    required this.onGovernmentChange,
    this.placeType = Users.doctor,
    this.priceController,
    this.specialistsStatus,
    this.spcialistValue,
    this.spcialistsList,
    this.onSpecialistChange,
  });

  @override
  Widget build(BuildContext context) {
    String placeName = placeType == Users.doctor ? "عيادة" : "صيدلية";
    return Form(
      key: formKey,
      child: Column(
        children: [
          Align(
            alignment: Alignment.centerRight,
            child: CustomText(
              text: isEdit ? "تعديل ال$placeName" : "اضافة $placeName",
              align: TextAlign.start,
            ).subHeader(context),
          ),
          const SizedBox(height: 15),
          isEdit
              ? Column(
                  children: [
                    DividerText(text: "لوجو ال$placeName"),
                    const SizedBox(height: 10),
                    Center(
                      child: UploadImageCircle(
                              imgWidgt: shadowCircleAvatar(imgSrc,
                                  isNetwork: true, radius: 60),
                              onUpload: onPickImage)
                          .defaultField,
                    ),
                    const SizedBox(height: 15),
                  ],
                )
              : Container(),
          const DividerText(text: "البيانات العامه"),
          const SizedBox(height: 10),
          !isEdit
              ? CustomTextField(
                      context: context,
                      onValidator: (value) => fieldValidor(value!),
                      controller: nameController,
                      hintText: "اسم ال$placeName",
                      icon: FontAwesomeIcons.solidHospital)
                  .textfield
              : Container(),
          SizedBox(height: isEdit ? 0 : 15),
          CustomTextField(
                  context: context,
                  onValidator: (value) =>
                      fieldValidor(value!, type: FieldsTypes.phone),
                  controller: phoneController,
                  hintText: "رقم الهاتف",
                  keyboardType: TextInputType.phone,
                  icon: FontAwesomeIcons.phone)
              .textfield,
          const SizedBox(height: 15),
          if (placeType == Users.doctor)
            CustomTextField(
                    context: context,
                    onValidator: (value) =>
                        fieldValidor(value!, type: FieldsTypes.number, min: 2),
                    controller: priceController!,
                    hintText: "سعر الكشف",
                    keyboardType: TextInputType.number,
                    icon: FontAwesomeIcons.moneyBill)
                .textfield,
          SizedBox(height: placeType == Users.doctor ? 15 : 0),
          !isEdit && placeType == Users.doctor
              ? CustomRequest(
                  status: specialistsStatus!,
                  sameContent: true,
                  widget: CustomDropdown(
                      initalVal:
                          spcialistValue!.isNotEmpty ? spcialistValue : null,
                      context: context,
                      onValidator: (value) => dropdownValidator(value),
                      hintText: "التخصص",
                      items: spcialistsList!,
                      onChange: (value) {
                        onSpecialistChange!(value!);
                      }).dropdown,
                )
              : Container(),
          SizedBox(height: !isEdit && placeType == Users.doctor ? 15 : 0),
          const DividerText(text: "مواعيد العمل"),
          const SizedBox(height: 10),
          CustomTextField(
                  context: context,
                  onTap: onSelectStartTime,
                  readOnly: true,
                  onValidator: (value) => fieldValidor(value!),
                  controller: rangeTimeController,
                  hintText: "من الساعه الي الساعه",
                  icon: FontAwesomeIcons.solidClock)
              .textfield,
          const SizedBox(height: 15),
          const DividerText(text: "بيانات العنوان"),
          const SizedBox(height: 10),
          Obx(() => CustomDropdown(
              initalVal: governorateValue.isNotEmpty ? governorateValue : null,
              context: context,
              onValidator: (value) => dropdownValidator(value),
              hintText: "المحافظة",
              items: governorateList.toList(),
              onChange: (value) {
                onGovernmentChange(value!);
              }).dropdown),
          const SizedBox(height: 15),
          CustomTextField(
                  context: context,
                  onValidator: (value) => fieldValidor(value!),
                  controller: locationController,
                  hintText: "تفاصيل العنوان",
                  icon: FontAwesomeIcons.locationDot)
              .textfield,
          const SizedBox(height: 15),
          BGButton(context,
                  text: isEdit ? "تعديل" : "اضافة", onPressed: onSubmit)
              .button
        ],
      ),
    );
  }
}
