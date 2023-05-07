import 'package:flutter/material.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:get/get.dart';
import 'package:roshetta_app/bindings/bindings.dart';
import 'package:roshetta_app/core/shared/custom_pages.dart';
import 'package:roshetta_app/core/constants/app_themes.dart';
import 'package:roshetta_app/core/localization/custom_translations.dart';
import 'package:roshetta_app/core/services/init_services.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await initServices();
  // Init App
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return GetMaterialApp(
      debugShowCheckedModeBanner: false,
      theme: arTheme,
      initialBinding: InitBinding(),
      translations: CustomTranslation(),
      getPages: routes,
      localizationsDelegates: const [
        GlobalMaterialLocalizations.delegate,
        GlobalCupertinoLocalizations.delegate,
        GlobalWidgetsLocalizations.delegate,
      ],
      locale: const Locale("ar", "AE"),
      fallbackLocale: const Locale("ar", "AE"),
    );
  }
}
