import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/controllers/home/homelayout_controller.dart';
import 'package:roshetta_app/view/widgets/home/body.dart';
import 'package:roshetta_app/view/widgets/home/bottom_navbar.dart';
import 'package:roshetta_app/view/widgets/home/drawer.dart';

class HomeLayout extends StatelessWidget {
  final BodyLayout? body;
  final GlobalKey<ScaffoldState> scaffoldKey;
  final Widget? floatingButton;
  final FloatingActionButtonLocation? location;

  const HomeLayout(
      {super.key,
      this.body,
      required this.scaffoldKey,
      this.floatingButton,
      this.location});

  @override
  Widget build(BuildContext context) {
    Get.put(HomeLayoutConrollerImp());
    return GetBuilder<HomeLayoutConrollerImp>(builder: (controller) {
      return Scaffold(
        drawerEnableOpenDragGesture: false,
        floatingActionButton: floatingButton,
        floatingActionButtonLocation: location,
        key: scaffoldKey,
        drawer: CustomDrawer(
          drawer: scaffoldKey,
        ),
        appBar: AppBar(toolbarHeight: 0),
        bottomNavigationBar: BottomNavbar(controller: controller),
        body: body ?? controller.pages(scaffoldKey)[controller.currentPage],
      );
    });
  }
}
