import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/auth/createaccount_controller.dart';
import 'package:roshetta_app/core/class/fields_interface.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/validator_function.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_buttons.dart';
import 'package:roshetta_app/core/shared/custom_fields.dart';
import 'package:roshetta_app/data/source/static/static_data.dart';
import 'package:roshetta_app/view/widgets/auth/accounttypes_widgets.dart';
import 'package:roshetta_app/view/widgets/auth/label_divider.dart';
import 'package:roshetta_app/view/widgets/auth/layout.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class CreateAccount extends StatelessWidget {
  const CreateAccount({super.key});

  @override
  Widget build(BuildContext context) {
    return AuthLayout(
        widget: Container(
      padding: const EdgeInsets.only(top: 40, bottom: 8, left: 8, right: 8),
      child: GetBuilder<CreateAccControllerImp>(builder: (controller) {
        return Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.asset(AssetPaths.logoIcon, width: 110),
            const SizedBox(height: 15),
            const CustomText(
              text: "انشاء حساب جديد",
              color: AppColors.primaryColor,
              fontWeight: FontWeight.w800,
            ),
            const CustomText(
              text: "يرجي ملئ البيانات لانشاء حساب جديد",
              color: AppColors.lightTextColor,
              textType: 3,
            ),
            const SizedBox(height: 30),
            Form(
              key: controller.createAccountForm,
              child: CustomRequest(
                sameContent: true,
                status: controller.requestStatus,
                widget: Column(
                  children: [
                    const DividerText(text: "البيانات الشخصيه"),
                    const SizedBox(height: 10),
                    CustomTextField(
                            context: context,
                            onValidator: (value) => fieldValidor(value!),
                            controller: controller.firstName,
                            hintText: "الاسم الاول",
                            icon: FontAwesomeIcons.solidUser)
                        .textfield,
                    const SizedBox(height: 15),
                    CustomTextField(
                            context: context,
                            onValidator: (value) => fieldValidor(value!),
                            controller: controller.lastName,
                            hintText: "الاسم الاخير",
                            icon: FontAwesomeIcons.solidUser)
                        .textfield,
                    const SizedBox(height: 15),
                    CustomTextField(
                            context: context,
                            onValidator: (value) =>
                                fieldValidor(value!, type: FieldsTypes.ssd),
                            controller: controller.ssd,
                            hintText: "الرقم القومي",
                            keyboardType: TextInputType.number,
                            icon: FontAwesomeIcons.idCard)
                        .textfield,
                    const SizedBox(height: 15),
                    CustomTextField(
                            context: context,
                            onValidator: (value) =>
                                fieldValidor(value!, type: FieldsTypes.phone),
                            controller: controller.phone,
                            hintText: "رقم الهاتف",
                            keyboardType: TextInputType.phone,
                            icon: FontAwesomeIcons.phone)
                        .textfield,
                    const SizedBox(height: 15),
                    const DividerText(text: "اختر الجنس"),
                    const SizedBox(height: 10),
                    SizedBox(
                      width: 320,
                      child: Row(
                        children: [
                          Expanded(
                            child: CustomRadio(
                                    context: context,
                                    hintText: "ذكر",
                                    value: "male",
                                    onChange: (value) {
                                      controller.onGenderChange(value!);
                                    },
                                    groupValue: controller.gender)
                                .radio,
                          ),
                          const SizedBox(width: 15),
                          Expanded(
                            child: CustomRadio(
                                    context: context,
                                    hintText: "انثي",
                                    value: "female",
                                    onChange: (value) {
                                      controller.onGenderChange(value!);
                                    },
                                    groupValue: controller.gender)
                                .radio,
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 15),
                    CustomTextField(
                            context: context,
                            onTap: () {
                              controller.onShowDatePicker(context);
                            },
                            readOnly: true,
                            onValidator: (value) => fieldValidor(value!),
                            controller: controller.birthDate,
                            hintText: "تاريخ ميلادك",
                            icon: FontAwesomeIcons.solidCalendarPlus)
                        .textfield,
                    const SizedBox(height: 15),
                    const DividerText(text: "العنوان"),
                    const SizedBox(height: 10),
                    CustomDropdown(
                        context: context,
                        initalVal: controller.government.isNotEmpty
                            ? controller.government
                            : null,
                        onValidator: (value) => dropdownValidator(value),
                        hintText: "المحافظة",
                        items: controller.governmentsList,
                        onChange: (value) {
                          controller.onGovernmentChange(value!);
                        }).dropdown,
                    const SizedBox(height: 15),
                    const DividerText(text: "بيانات الحساب"),
                    const SizedBox(height: 10),
                    CustomDropdown(
                        context: context,
                        initalVal: controller.accountType.isNotEmpty
                            ? controller.accountType
                            : null,
                        onValidator: (value) => dropdownValidator(value),
                        hintText: "نوع الحساب",
                        items: StaticData.usersList,
                        onChange: (value) {
                          controller.onAccountTypeChange(value!);
                        }).dropdown,
                    const SizedBox(height: 15),
                    CustomRequest(
                      sameContent: true,
                      status: controller.requestStatusSpecialists,
                      widget: AccountTypeWidget(
                          accountType: controller.accountType,
                          onSpecialistChange: (value) {
                            controller.onSpcialistsChange(value!);
                          },
                          specialistsList: controller.specialistsList,
                          patientWeight: controller.patientWeight,
                          patientHeight: controller.patientHeight),
                    ),
                    CustomTextField(
                            context: context,
                            onValidator: (value) =>
                                fieldValidor(value!, type: FieldsTypes.email),
                            controller: controller.email,
                            hintText: "البريد الالكتروني",
                            keyboardType: TextInputType.emailAddress,
                            icon: FontAwesomeIcons.solidEnvelope)
                        .textfield,
                    const SizedBox(height: 15),
                    CustomTextField(
                      context: context,
                      onValidator: (value) => fieldValidor(value!),
                      controller: controller.password,
                      hintText: "كلمة المرور",
                      icon: passwordVisibleIcon(controller.isVisiblePassword),
                      secure: controller.isVisiblePassword,
                      keyboardType: TextInputType.visiblePassword,
                      passwordTap: () {
                        controller.onPasswordVisibleChange();
                      },
                    ).textfield,
                    const SizedBox(height: 15),
                    CustomTextField(
                      context: context,
                      onValidator: (value) {
                        return fieldValidor(value!,
                            type: FieldsTypes.repassword,
                            passwordsEquals: controller.checkPasswordEquals());
                      },
                      controller: controller.rePassword,
                      hintText: "اعد كلمة المرور",
                      icon: passwordVisibleIcon(controller.isVisiblePassword),
                      secure: controller.isVisiblePassword,
                      keyboardType: TextInputType.visiblePassword,
                      passwordTap: () {
                        controller.onPasswordVisibleChange();
                      },
                    ).textfield,
                    const SizedBox(height: 15),
                    BGButton(context, text: "انشاء", onPressed: () {
                      controller.onSubmit(context);
                    }).button,
                    const SizedBox(height: 15),
                    SizedBox(
                      width: 300,
                      child: Wrap(
                        alignment: WrapAlignment.center,
                        spacing: 5,
                        children: [
                          const CustomText(
                            text: "لديك حساب بالفعل ؟",
                            color: AppColors.lightTextColor,
                            textType: 3,
                            fontWeight: FontWeight.w400,
                          ),
                          InkWell(
                            onTap: () {
                              controller.goToLogin();
                            },
                            child: const CustomText(
                              text: "تسجيل الدخول",
                              color: AppColors.primaryColor,
                              textType: 3,
                              fontWeight: FontWeight.w600,
                            ),
                          )
                        ],
                      ),
                    )
                  ],
                ),
              ),
            ),
          ],
        );
      }),
    ));
  }
}
