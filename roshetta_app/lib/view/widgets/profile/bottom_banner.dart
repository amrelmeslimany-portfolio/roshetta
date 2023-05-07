import 'package:flutter/material.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/data/models/clinic.modal.dart';
import 'package:roshetta_app/data/models/pharmacy.modal.dart';
import 'package:roshetta_app/data/models/user.model.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';

class ProfileBottomBanner extends StatelessWidget {
  final User? user;
  final Map? assistant;
  final ClinicModal? clinic;
  final PharmacyModal? pharmacy;
  final String? type;
  const ProfileBottomBanner(
      {super.key,
      this.user,
      this.clinic,
      this.type,
      this.assistant,
      this.pharmacy});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 15),
      margin: const EdgeInsets.symmetric(horizontal: 7.5),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        children: _checkType(),
      ),
    );
  }

  List<Widget> _checkType() {
    if (user != null && user!.role == Users.patient.name) {
      return _patient();
    } else if (user != null && user!.role == Users.doctor.name) {
      return _doctorProfile();
    } else if (user != null && user!.role == Users.pharmacist.name) {
      return _pharmacyProfile();
    } else if (user != null && user!.role == Users.assistant.name) {
      return _assistantProfile();
    } else if (clinic != null && type == null) {
      return _clinic();
    } else if (pharmacy != null && type == null) {
      return _pharmacy();
    } else if (pharmacy != null && type == Users.patient.name) {
      return _pharmacyPatient();
    } else if (clinic != null && type == Users.patient.name) {
      return _patientClinic();
    } else {
      return [const Text("نوع الاحصائية غير متوفر")];
    }
  }

  List<Widget> _patient() {
    return [
      _item("الحجوزات", handleNumbers(user?.appointNumbers)),
      _vDivider(),
      _item("الامراض", handleNumbers(user?.diseasesNumber)),
      _vDivider(),
      _item("الروشتات", handleNumbers(user?.prescriptsNumber)),
    ];
  }

  List<Widget> _assistantProfile() {
    return [
      _item("الحجوزات", handleNumbers(user?.appointNumbers)),
      _vDivider(),
      _item("حجوزات اليوم", handleNumbers(user?.todayAppointments)),
      _vDivider(),
      _item("العيادات", handleNumbers(user?.clinicNumbers)),
    ];
  }

  List<Widget> _doctorProfile() {
    return [
      _item("الحجوزات", handleNumbers(user?.appointNumbers)),
      _vDivider(),
      _item("الروشتات", handleNumbers(user?.prescriptsNumber)),
      _vDivider(),
      _item("العيادات", handleNumbers(user?.clinicNumbers)),
    ];
  }

  List<Widget> _pharmacyProfile() {
    return [
      _item("الطلبات", handleNumbers(user?.pharmacyOrderNumber)),
      _vDivider(),
      _item("الروشتات", handleNumbers(user?.prescriptsNumber)),
      _vDivider(),
      _item("الصيدليات", handleNumbers(user?.pharmacyNumber)),
    ];
  }

  List<Widget> _clinic() {
    return [
      _item("الروشتات", handleNumbers(clinic?.numberOfPrescript)),
      _vDivider(),
      _item("الحجوزات", handleNumbers(clinic?.appointAll)),
      _vDivider(),
      _item("حجوزات اليوم", handleNumbers(clinic?.appointDay)),
    ];
  }

  List<Widget> _pharmacyPatient() {
    return [
      _item("روشتات الصيدلية", handleNumbers(pharmacy?.numberOfPrescript)),
      _vDivider(),
      _item("روشتاتك", handleNumbers(pharmacy?.patientPrescriptNumber)),
    ];
  }

  List<Widget> _pharmacy() {
    return [
      _item("الروشتات", handleNumbers(pharmacy?.numberOfPrescript)),
      _vDivider(),
      _item("الطلبات", handleNumbers(pharmacy?.ordersNumber)),
    ];
  }

  List<Widget> _patientClinic() {
    return [
      _item("حجوزات العياده", handleNumbers(clinic?.appointAll)),
      _vDivider(),
      _item("حجوزاتك مع العياده", handleNumbers(clinic?.numberAppointPatient)),
    ];
  }

  Column _item(String lebal, String value) {
    return Column(
      children: [
        CustomText(
          text: lebal,
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

  Container _vDivider() {
    return Container(
      color: AppColors.lightenWhiteColor,
      height: 40,
      width: 2,
    );
  }
}
