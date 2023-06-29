import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/doctor/doctorpatient_controller.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/quick_functions.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/core/shared/custom_notes.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/auth/label_divider.dart';
import 'package:roshetta_app/view/widgets/clinics/medicine.dart';
import 'package:roshetta_app/view/widgets/home/header_content.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class AddMedicinesForm extends StatelessWidget {
  AddMedicinesForm({super.key});
  final controller = Get.find<DoctorPatientDetailsController>();
  final GlobalKey<FormState> medicineForm = GlobalKey<FormState>();
  final GlobalKey<FormState> addPrescriptForm = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const HeaderContent(
            header: "بيانات الادويه",
            spacer: 5,
            content: CustomText(
              text: "يرجي ادخال البيانات الخاصه بادوية الروشته",
              textType: 3,
              color: AppColors.lightTextColor,
              align: TextAlign.start,
            )),
        const SizedBox(height: 15),
        if (controller.formType.value == "rediscovery")
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              iconAndWidget(FontAwesomeIcons.disease,
                  iconSize: 17,
                  iconColor: AppColors.lightTextColor,
                  widget: const CustomText(
                      text: "اسم المرض:",
                      textType: 3,
                      color: AppColors.lightTextColor)),
              const SizedBox(width: 5),
              Expanded(
                child: CustomText(
                    text: Get.arguments ?? "غير معلوم",
                    align: TextAlign.start,
                    fontWeight: FontWeight.w700,
                    textType: 3,
                    color: AppColors.primaryTextColor),
              ),
            ],
          ),
        SizedBox(height: controller.formType.value == "rediscovery" ? 30 : 0),
        Obx(
          () => Form(
              key: addPrescriptForm,
              child: Column(
                children: [
                  CustomTextField(
                          context: context,
                          onTap: () {
                            controller.onPickReappointmentDate(context);
                          },
                          readOnly: true,
                          onValidator: (value) => fieldValidor(value!),
                          controller: controller.reappointmentDate,
                          hintText: "تاريخ اعاده الكشف",
                          icon: FontAwesomeIcons.solidCalendarPlus)
                      .textfield,
                  const SizedBox(height: 15),
                  const DividerText(text: "قائمة الادوية"),
                  const SizedBox(height: 10),
                  controller.medicines.isEmpty
                      ? emptyLottieList()
                      : ListView.separated(
                          separatorBuilder: (context, index) {
                            return const SizedBox(height: 15);
                          },
                          shrinkWrap: true,
                          physics: const NeverScrollableScrollPhysics(),
                          itemCount: controller.medicines.length,
                          itemBuilder: (context, index) {
                            var item = controller.medicines[index];

                            return InkWell(
                              onLongPress: () {
                                controller.onEditMedicine(item);
                                _addMedicineSheet(context,
                                    formType: "edit", itemIndex: index);
                              },
                              child: MedicineCard(
                                  smallTitle: "${index + 1}",
                                  name: item["name"],
                                  weight: item["size"],
                                  duration: item["duration"],
                                  description: item["description"],
                                  onDelete: () {
                                    controller.onDeleteMedcine(item);
                                  }),
                            );
                          },
                        ),
                  const SizedBox(height: 10),
                  UnconstrainedBox(
                    child: BorderedButton(context,
                        text: "اضافة دواء",
                        small: true,
                        icon: FontAwesomeIcons.plus,
                        minWidth: 180, onPressed: () {
                      _addMedicineSheet(context);
                    }).button,
                  ),
                  const SizedBox(height: 30),
                  BGButton(context, text: "اضافة", onPressed: () {
                    if (addPrescriptForm.currentState!.validate()) {
                      if (controller.medicines.isNotEmpty) {
                        confirmDialog(context,
                            text:
                                "لن يمكنك التعديل او اضافة روشته مرة اخري لهذا الحجز وسيتم تحويل حاله الحجز الي 'تم الكشف' ",
                            onConfirm: () => controller.onSubmitPrescript());
                      } else {
                        snackbar(
                            isError: true,
                            title: "مشكله ما",
                            content: "يجب ادخال دواء علي الاقل");
                      }
                    } else {
                      scrollToTop(controller.scrollController);
                    }
                  }).button,
                  ..._checkFormType().toList()
                ],
              )),
        ),
      ],
    );
  }

  List<Widget> _checkFormType() {
    if (controller.formType.value == "new") {
      return [
        const SizedBox(height: 5),
        TextButton(
            onPressed: () {
              controller.changeCurrentForm(0);
            },
            child: const Text("الرجوع للمرض"))
      ];
    } else {
      return [Container()];
    }
  }

  _addMedicineSheet(context, {String? formType = "add", int? itemIndex}) {
    List<Map> inputs = StaticData.medicineInputs(controller);
    Get.bottomSheet(CustomBottomSheets().sheet([
      CustomBottomSheets()
          .header(formType == "add" ? "اضافة دواء جديد" : "تعديل الدواء"),
      const SizedBox(height: 15),
      Form(
          key: medicineForm,
          child: Column(
            children: [
              ...inputs
                  .map((input) => Container(
                        margin: const EdgeInsets.only(bottom: 15),
                        child: CustomTextField(
                                context: context,
                                onValidator: (value) => fieldValidor(value!),
                                controller: input["controller"],
                                hintText: input["hint"],
                                icon: input["icon"])
                            .textfield,
                      ))
                  .toList(),
              SizedBox(
                width: 310,
                child: Notes(
                        icon: FontAwesomeIcons.exclamation,
                        text:
                            "المقصود بوصف الدواء: ان تشرح للمريض متي سياخذ الدواء بالتحديد.")
                    .init,
              ),
            ],
          )),
    ], height: 380, buttonText: formType == "add" ? "اضافة" : "تعديل",
        onSubmit: () {
      if (medicineForm.currentState!.validate()) {
        controller.onAddMedicine(formType: formType, itemIndex: itemIndex);
        if (Get.isBottomSheetOpen == true) {
          Get.back();
        }
      }
    })).then((value) => controller.onClearMedicineInputs());
  }
}
