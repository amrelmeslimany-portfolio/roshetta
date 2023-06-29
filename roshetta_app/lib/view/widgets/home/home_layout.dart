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
  final Future<void> Function()? onRefresh;
  HomeLayout(
      {super.key,
      this.body,
      required this.scaffoldKey,
      this.floatingButton,
      this.location,
      this.onRefresh});

  final HomeLayoutConrollerImp controller = Get.put(HomeLayoutConrollerImp());

  @override
  Widget build(BuildContext context) {
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
        body: onRefresh != null
            ? RefreshIndicator(
                onRefresh: onRefresh!,
                child: CustomScrollView(
                  slivers: [
                    SliverFillRemaining(
                      child: _body(),
                    ),
                  ],
                ),
              )
            : _body(),
      );
    });
  }

  Widget _body() =>
      body ?? controller.pages(scaffoldKey)[controller.currentPage];
}
