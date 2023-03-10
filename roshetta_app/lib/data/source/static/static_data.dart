import 'package:flutter/material.dart';
import 'package:roshetta_app/core/class/users_interfaces.dart';
import 'package:roshetta_app/core/constants/app_routes.dart';
import 'package:roshetta_app/core/functions/reused_functions.dart';

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
}
