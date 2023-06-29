import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/bottom_sheets.dart';

class StaticData {
  static List<DropdownMenuItem<String>> usersList = Users.values
      .map((user) => DropdownMenuItem(
            value: user.name,
            child: Text(usersAR[user.name]!),
          ))
      .toList();

  // Goverments
  static Future<List<DropdownMenuItem<String>>> getGoverments() async {
    List data = await readJson(AssetPaths.governmentsJson);

    return data
        .map((goverment) => DropdownMenuItem(
              value: goverment["governorate_name_ar"].toString(),
              child: Text(goverment["governorate_name_ar"]),
            ))
        .toList();
  }

  // Drawer Links
  static Map drawerLinks = {
    Users.patient.name: [
      {
        "icon": FontAwesomeIcons.cartShopping,
        "title": "الطلبات",
        "page": AppRoutes.patientOrders
      },
      {
        "icon": FontAwesomeIcons.listUl,
        "title": "الروشتات",
        "page": AppRoutes.patientPrescripts
      },
      {
        "icon": FontAwesomeIcons.disease,
        "title": "الامراض",
        "page": AppRoutes.patientDiseases
      },
      {
        "icon": FontAwesomeIcons.solidHospital,
        "title": "العيادات",
        "page": AppRoutes.patientClinics
      },
      {
        "icon": FontAwesomeIcons.prescriptionBottleMedical,
        "title": "الصيدليات",
        "page": AppRoutes.patientPharmacys
      },
      {
        "icon": FontAwesomeIcons.calendarDays,
        "title": "الحجوزات",
        "page": AppRoutes.patientappointments
      },
    ],
    Users.assistant.name: [
      {
        "icon": FontAwesomeIcons.solidHospital,
        "title": "العيادات",
        "page": AppRoutes.assistantClinics
      },
    ],
    Users.doctor.name: [
      {
        "icon": FontAwesomeIcons.solidHospital,
        "title": "العيادات",
        "page": AppRoutes.doctorClinics
      },
      {
        "icon": FontAwesomeIcons.solidComments,
        "title": "الدردشة",
        "page": AppRoutes.doctorChat
      },
      {
        "icon": FontAwesomeIcons.solidSquarePlus,
        "title": "اضافة عياده",
        "page": AppRoutes.doctorAddClinic
      },
    ],
    Users.pharmacist.name: [
      {
        "icon": FontAwesomeIcons.receipt,
        "title": "صرف روشتة",
        "page": AppRoutes.pharmacySellPrescript
      },
      {
        "icon": FontAwesomeIcons.houseMedical,
        "title": "الصيدليات",
        "page": AppRoutes.pharmacistPharmacys
      },
      {
        "icon": FontAwesomeIcons.circlePlus,
        "title": "اضافة صيدلية",
        "page": AppRoutes.addPharmacy
      },
    ],
  };

  // Verify Images
  static List<Map<String, dynamic>> verifyImages = [
    {
      "label": "الصورة الأمامية للبطاقه الشخصيه",
      "file": null,
      "name": "front_nationtional_card"
    },
    {
      "label": "الصورة الخلفيه للبطاقه الشخصيه",
      "file": null,
      "name": "back_nationtional_card"
    },
    {"label": "صورة شهاده التخرج", "file": null, "name": "graduation_cer"},
    {"label": "صورة الكارنيه", "file": null, "name": "card_id_img"},
  ];

  // Doctor Clinic Setting Buttons
  static List<ButtonSheetItem> clinicDoctorSettings(
          {Function()? onEditClinic,
          Function()? onAssitantEdit,
          Function()? onExitClinic,
          String? status,
          String? text = "العياده"}) =>
      [
        ButtonSheetItem(
            icon: Icons.edit_square, text: "تعديل $text", onTap: onEditClinic),
        if (onAssitantEdit != null)
          ButtonSheetItem(
              icon: Icons.mode_edit,
              text: "تعديل المساعد",
              onTap: onAssitantEdit),
        if (status == "1")
          ButtonSheetItem(
              icon: Icons.logout, text: "غلق $text", onTap: onExitClinic),
      ];

  static List<Map> medicineInputs(controller) {
    return [
      {
        "controller": controller.medicineName,
        "hint": "اسم الدواء",
        "icon": Icons.medical_information
      },
      {
        "controller": controller.medicineSize,
        "hint": "حجم الدواء",
        "icon": Icons.monitor_weight
      },
      {
        "controller": controller.medicineDuration,
        "hint": "مدة الدواء",
        "icon": Icons.calendar_month
      },
      {
        "controller": controller.medicineDescription,
        "hint": "وصف الدواء",
        "icon": Icons.receipt
      },
    ];
  }
}
