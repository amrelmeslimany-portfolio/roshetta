import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/pharmacist/prescripts_controller.dart';
import 'package:roshetta_app/core/class/enums.dart';
import 'package:roshetta_app/core/class/request_status.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_notes.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/patient/prescripts/prescripts_list.dart';
import 'package:roshetta_app/view/widgets/pharmacist/prescripts_order.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/custom_texts.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';
import 'package:roshetta_app/view/widgets/shared/text_search.dart';

class PharmacistPrescripts extends StatelessWidget {
  PharmacistPrescripts({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final prescriptsController = Get.put(PharmacyPrescriptsController());

  @override
  Widget build(BuildContext context) {
    prescriptsController.getPrescripts();
    return HomeLayout(
        scaffoldKey: scaffoldKey,
        body: BodyLayout(
            appbar: CustomAppBar(
                    onPressed: () {
                      toggleDrawer(scaffoldKey);
                    },
                    children: prescriptsController.pageType.value !=
                            PrescriptStatus.isOrder
                        ? Container(
                            alignment: Alignment.center,
                            padding: const EdgeInsets.symmetric(
                                horizontal: 8, vertical: 5),
                            child: Column(
                              children: [
                                const CustomText(
                                    text: "فلتر ",
                                    textType: 2,
                                    color: Colors.white),
                                const SizedBox(height: 10),
                                Obx(() {
                                  if (prescriptsController
                                          .prescriptStatus.value ==
                                      RequestStatus.loading) {
                                    return const CircularProgressIndicator(
                                      color: AppColors.whiteColor,
                                    );
                                  }
                                  return Container(
                                    margin: const EdgeInsets.symmetric(
                                        horizontal: 20),
                                    child: TextSearchField(
                                        placeholder:
                                            "اسم المريض او سيريال الروشته",
                                        controller: prescriptsController.search,
                                        onSearch: () {
                                          prescriptsController
                                              .onSearchPrescripts();
                                        }),
                                  );
                                }),
                              ],
                            ),
                          )
                        : null)
                .init,
            content: [
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 8),
                child: Obx(() {
                  return Column(
                    children: [
                      if (prescriptsController.isOrders)
                        Notes(
                                icon: FontAwesomeIcons.circleExclamation,
                                text:
                                    "الطلبات التي طلبها المريض , سيتم حذفها تلقائيا بعد 24 ساعه")
                            .init,
                      SizedBox(height: prescriptsController.isOrders ? 15 : 0),
                      HeaderBadge(
                          header: prescriptsController.isOrders
                              ? "الطلبات"
                              : "الروشتات",
                          badgeText: handleNumbers(
                              prescriptsController.prescripts.length),
                          description:
                              "سيتم عرض التفاصيل الخاصه بالروشته عند الضغط عليها"),
                      const SizedBox(height: 15),
                      if (!prescriptsController.isOrders)
                        Notes(
                                icon: FontAwesomeIcons.circleExclamation,
                                text: prescriptsController.userSsd.isNotEmpty
                                    ? "روشتات المريض : ${prescriptsController.userSsd.value}"
                                    : "جميع الروشتات التي صرفتها في هذة الصيدلية")
                            .init,
                      if (!prescriptsController.isOrders)
                        const SizedBox(height: 15),
                      CustomRequest(
                          status: prescriptsController.prescriptStatus.value,
                          widget: _checkListPrescriptsType())
                    ],
                  );
                }),
              ),
              const SizedBox(height: 70)
            ]));
  }

  Widget _checkListPrescriptsType() {
    if (prescriptsController.isOrders) {
      return PrescriptOrders(
          withStatus: true,
          orders: prescriptsController.prescripts,
          onOrderPress: (item) =>
              _onOrder(item["prescript_id"], orderId: item["order_id"]));
    } else if (prescriptsController.pageType.value == PrescriptStatus.isOrder) {
      return PrescriptsList(
          prescripts: prescriptsController.prescripts,
          onPrescriptPress: (value) => _onOrder(value),
          name: "patient_name");
    } else {
      return PrescriptOrders(
          isPaied: true,
          orders: prescriptsController.prescripts,
          onOrderPress: (item) => _onOrder(item["prescript_id"]));
    }
  }

  _onOrder(String prescriptId, {String? orderId}) {
    if (prescriptsController.isOrders && orderId != null) {
      prescriptsController.setOrderId(orderId);
    } else {
      prescriptsController.setOrderId("");
    }
    prescriptsController.setPrescriptId(prescriptId);
    prescriptsController.getPrescriptDetails(prescriptId, "id");
  }
}
