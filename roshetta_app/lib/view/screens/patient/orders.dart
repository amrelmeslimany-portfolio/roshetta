import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/patient/orders_controller.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';
import 'package:roshetta_app/core/functions/widget_functions.dart';
import 'package:roshetta_app/core/shared/custom_appbar.dart';
import 'package:roshetta_app/core/shared/custom_notes.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/home_layout.dart';
import 'package:roshetta_app/view/widgets/pharmacist/prescripts_order.dart';
import 'package:roshetta_app/view/widgets/shared/custom_request.dart';
import 'package:roshetta_app/view/widgets/shared/header_badge.dart';

class PatientOrders extends StatelessWidget {
  PatientOrders({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  final ordersController = Get.find<PatientOrdersController>();

  @override
  Widget build(BuildContext context) {
    ordersController.getOrders();
    return HomeLayout(
        scaffoldKey: scaffoldKey,
        body: BodyLayout(
            appbar: CustomAppBar(onPressed: () {
              toggleDrawer(scaffoldKey);
            }).init,
            content: [
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 8),
                child: Obx(() {
                  return Column(
                    children: [
                      Notes(
                              icon: FontAwesomeIcons.circleExclamation,
                              text:
                                  "الطلبات التي طلبتها , سيتم حذفها تلقائيا بعد 24 ساعه")
                          .init,
                      const SizedBox(height: 15),
                      HeaderBadge(
                          header: "الطلبات",
                          badgeText:
                              handleNumbers(ordersController.orders.length),
                          description:
                              "سيتم عرض التفاصيل الخاصه بالروشته عند الضغط عليها"),
                      const SizedBox(height: 15),
                      CustomRequest(
                          status: ordersController.ordersStatus.value,
                          widget: PrescriptOrders(
                              orders: ordersController.orders,
                              isPaied: true,
                              isPatient: true,
                              onDelete: (orderId) {
                                confirmDialog(context,
                                    text: "هل تريد حذف الطلب ؟", onConfirm: () {
                                  ordersController.onDeleteOrder(orderId);
                                });
                              },
                              onOrderPress: (item) => _onOrder(item)))
                    ],
                  );
                }),
              ),
              const SizedBox(height: 70)
            ]));
  }

  _onOrder(Map item) {
    print(item["prescript_id"]);
  }
}
