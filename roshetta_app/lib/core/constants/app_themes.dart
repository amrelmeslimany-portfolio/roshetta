import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:roshetta_app/core/constants/app_colors.dart';

ThemeData arTheme = ThemeData(
    primarySwatch: Colors.green,
    fontFamily: "Cairo",
    scaffoldBackgroundColor: AppColors.whiteColor,
    textTheme: const TextTheme(
      headlineLarge: TextStyle(
        height: 1.5,
        fontSize: 30,
        fontWeight: FontWeight.w900,
      ),
      headlineMedium: TextStyle(
        height: 1.5,
        fontSize: 26,
        fontWeight: FontWeight.w900,
      ),
      headlineSmall: TextStyle(
        height: 1.5,
        fontSize: 24,
        fontWeight: FontWeight.w900,
      ),
      titleLarge: TextStyle(
        height: 1.5,
        fontSize: 22,
        fontWeight: FontWeight.w700,
      ),
      titleMedium: TextStyle(
        height: 1.5,
        fontSize: 20,
        fontWeight: FontWeight.w700,
      ),
      titleSmall: TextStyle(
        height: 1.5,
        fontSize: 18,
      ),
      bodyLarge: TextStyle(height: 1.5, fontSize: 16),
      bodyMedium: TextStyle(height: 1.5, fontSize: 14),
      bodySmall: TextStyle(height: 1.5, fontSize: 12),
    ),
    colorScheme: const ColorScheme.light(
        surface: AppColors.primaryColor, primary: AppColors.primaryColor),
    appBarTheme: const AppBarTheme(
        elevation: 0,
        systemOverlayStyle:
            SystemUiOverlayStyle(statusBarColor: Color(0xff4aa77e))));

BoxDecoration shadowBoxWhite = BoxDecoration(
    color: AppColors.whiteColor,
    borderRadius: const BorderRadius.all(Radius.circular(15)),
    boxShadow: [
      BoxShadow(
          spreadRadius: 1, color: Colors.black.withOpacity(0.09), blurRadius: 8)
    ]);
