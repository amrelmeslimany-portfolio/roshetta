import 'package:flutter/material.dart';

Map prescriptStatusWord(String text) {
  switch (text) {
    case "isOrder":
    case "wating":
      return {"color": Colors.yellow[50], "text": "في الطلب"};
    case "done":
      return {"color": Colors.green[50], "text": "صرفت"};
    default:
      return {"color": Colors.red[50], "text": "لم تصرف"};
  }
}
