import 'package:flutter/material.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/date_functions.dart';
import 'package:roshetta_app/data/models/clinic.modal.dart';
import 'package:roshetta_app/data/models/pharmacy.modal.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class ProfileBanner extends StatelessWidget {
  final User? user;
  final Patient? patient;
  final ClinicModal? clinic;
  final PharmacyModal? pharmacy;
  final String? clinicType;
  final Widget? middleWidget;
  const ProfileBanner(
      {super.key,
      this.user,
      this.clinic,
      this.pharmacy,
      this.clinicType,
      this.middleWidget,
      this.patient});

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: shadowBoxWhite,
      padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 15),
      margin: const EdgeInsets.symmetric(horizontal: 7.5),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        children: _checkBannarType(),
      ),
    );
  }

  Column _handleItems(String label, String value) {
    return Column(
      children: [
        CustomText(
          text: label,
          color: AppColors.lightTextColor,
          textType: 5,
        ),
        const SizedBox(height: 5),
        CustomText(
          text: value,
          color: AppColors.primaryTextColor,
          textType: 2,
        ),
      ],
    );
  }

  List<Widget> _checkBannarType() {
    if (user != null) {
      return _userProfile();
    } else if (patient != null && clinicType == Users.patient.name) {
      return _patientDetailsDoctor();
    } else if (clinic != null && clinicType == Users.doctor.name) {
      return _clinic();
    } else if (clinic != null && clinicType == Users.patient.name) {
      return _patientClinic();
    } else if (pharmacy != null && clinicType == Users.pharmacist.name) {
      return _pharmacy();
    } else if (pharmacy != null && clinicType == Users.patient.name) {
      return _patitentPharmacy();
    } else {
      return [const Text("حدثت مشكله")];
    }
  }

  List<Widget> _userProfile() {
    return [
      _handleItems("الجنس", handleGender(user?.gender ?? "فارغ")),
      _handleItems("تاريخ الميلاد", user?.birthDate ?? "فارغ"),
      _handleItems("نوع الحساب", usersAR[user?.role] ?? "فارغ"),
    ];
  }

  List<Widget> _patientDetailsDoctor() {
    return [
      _handleItems(
          "الطول", patient?.height != null ? "${patient!.height} سم" : "فارغ"),
      _handleItems(
          "الوزن", patient?.weight != null ? "${patient!.weight} كجم" : "فارغ"),
      _handleItems("العمر", patient?.age ?? "فارغ"),
    ];
  }

  List<Widget> _clinic() {
    String rangeTime = _handleTimeRangeErrors(
        start: clinic?.startWorking, end: clinic?.endWorking);

    return [
      _handleItems("موعد العمل", rangeTime),
      _handleItems(
          "السعر", clinic?.price != null ? "${clinic!.price} ج" : "فارغ"),
      _handleItems("الموظفين", handleNumbers(clinic?.stuff.length ?? 0)),
    ];
  }

  List<Widget> _patitentPharmacy() {
    String rangeTime = _handleTimeRangeErrors(
        start: pharmacy?.startWorking, end: pharmacy?.endWorking);

    return [
      _handleItems("موعد العمل", rangeTime),
      _handleItems("رقم الموبايل", pharmacy!.phoneNumber!),
    ];
  }

  List<Widget> _pharmacy() {
    String rangeTime = _handleTimeRangeErrors(
        start: pharmacy?.startWorking, end: pharmacy?.endWorking);
    return [
      _handleItems("موعد العمل", rangeTime),
      _handleItems("السيريال", pharmacy!.serId.toString()),
    ];
  }

  List<Widget> _patientClinic() {
    String rangeTime = _handleTimeRangeErrors(
        start: clinic?.startWorking, end: clinic?.endWorking);
    return [
      _handleItems("موعد العمل", rangeTime),
      if (middleWidget != null) middleWidget!,
      _handleItems(
          "السعر", clinic?.price != null ? "${clinic!.price} ج" : "فارغ"),
    ];
  }

  String _handleTimeRangeErrors({String? start, String? end}) {
    return getRangeTime(
        start: start ?? DateTime.now().toString(),
        end: end ?? DateTime.now().add(const Duration(hours: 2)).toString());
  }
}

String handleGender(String gender) {
  switch (gender) {
    case "male":
      return "ذكر";
    case "female":
      return "مؤنث";
    default:
      return gender;
  }
}
