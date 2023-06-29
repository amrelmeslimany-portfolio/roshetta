import 'package:flutter/material.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_notes.dart';
import 'package:roshetta_app/data/models/clinic.modal.dart';
import 'package:roshetta_app/data/models/pharmacy.modal.dart';
import 'package:roshetta_app/view/widgets/clinics/stuff.dart';
import 'package:roshetta_app/view/widgets/profile/banner.dart';
import 'package:roshetta_app/view/widgets/profile/bottom_banner.dart';
import 'package:roshetta_app/view/widgets/profile/header.dart';
import 'package:roshetta_app/view/widgets/profile/info_list.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';

class ClinicDetailsItem extends StatelessWidget {
  final RequestStatus status;
  final ClinicModal? clinic;
  final PharmacyModal? pharmacy;
  final Widget? headerWidget;
  final Function() onSetting;

  const ClinicDetailsItem(
      {super.key,
      required this.status,
      this.clinic,
      this.pharmacy,
      required this.onSetting,
      this.headerWidget});

  @override
  Widget build(BuildContext context) {
    bool isClinic = clinic == null ? false : true;
    return CustomRequest(
        status: status,
        sameContent: false,
        widget: Column(
          children: [
            if (clinic?.status == "0" || pharmacy?.status == "0")
              Container(
                margin: const EdgeInsets.fromLTRB(8, 8, 8, 30),
                child: Notes(
                        icon: Icons.login_rounded,
                        text: isClinic
                            ? "العياده مغلقة الان, هذا يعني انه لن تظهر عند المرضي ولن تستقبل حجوزات."
                            : "الصيدلية مغلقة الان, لن تستلم طلبات حتي تقوم بفتحها")
                    .init,
              )
            else
              Container(),
            ProfileHeader(
                image: clinic?.logo ?? pharmacy?.logo ?? AssetPaths.emptyIMG,
                title: clinic?.name ?? pharmacy?.name ?? "لا يوجد",
                subTitle:
                    formatCloseOrNot(clinic?.status ?? pharmacy?.status ?? ""),
                icon: clinic?.status == "0" || pharmacy?.status == "0"
                    ? Icons.close_rounded
                    : Icons.check_circle,
                subTitleColor: clinic?.status == "1" || pharmacy?.status == "1"
                    ? AppColors.primaryColor
                    : null,
                onSettings: onSetting,
                bottomWidget: headerWidget),
            const SizedBox(height: 30),
            ProfileBanner(
                clinic: isClinic ? clinic : null,
                pharmacy: !isClinic ? pharmacy : null,
                clinicType:
                    isClinic ? Users.doctor.name : Users.pharmacist.name),
            const SizedBox(height: 30),
            ProfileInfoList(
              address: clinic?.address ?? pharmacy?.address,
              governorate: clinic?.governorate ?? pharmacy?.governorate,
              phone: clinic?.phoneNumber ?? pharmacy?.phoneNumber,
              specialist: clinic?.specialist,
            ),
            const SizedBox(height: 30),
            ProfileBottomBanner(
                pharmacy: !isClinic ? pharmacy : null,
                clinic: isClinic ? clinic : null),
            SizedBox(height: isClinic ? 30 : 0),
            if (isClinic) Stuff(items: clinic?.stuff ?? pharmacy?.stuff)
          ],
        ));
  }
}
