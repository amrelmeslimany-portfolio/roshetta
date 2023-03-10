import 'package:get/get.dart';
import 'package:roshetta_app/core/class/fields_interface.dart';

dropdownValidator(value) {
  if (value == null) return "قم باختيار من الحقل";
  return null;
}

fieldValidor(String value,
    {FieldsTypes? type, int? min, int? max, bool? passwordsEquals}) {
  var handeledValue = value.trim();

  if (handeledValue.isEmpty) {
    return "يجب ادخال هذا الحقل";
  }

  if (type != null) {
    if (type == FieldsTypes.phone && !GetUtils.isPhoneNumber(value)) {
      return "ادخل رقم الموبايل صحيحا";
    }

    if (type == FieldsTypes.email && !GetUtils.isEmail(value)) {
      return "يجب ان يكون الايميل صحيحا";
    }
    if (type == FieldsTypes.ssd &&
        (!GetUtils.isLengthEqualTo(value, 14) ||
            !GetUtils.isNumericOnly(value))) {
      return "يجب ادخال رقم قومي صحيح";
    }

    if (type == FieldsTypes.repassword &&
        passwordsEquals != null &&
        !passwordsEquals) {
      return "كلمتا المرور غير متطابقان.";
    }

    if (type == FieldsTypes.number && !GetUtils.isNum(value)) {
      return 'يجب ادخال رقم فقط';
    }
  }

  if (min != null && GetUtils.isLowerThan(double.parse(value), min)) {
    return "يجب ان تدخل اكثر من $min ";
  }

  if (max != null && GetUtils.isGreaterThan(double.parse(value), max)) {
    return " يجب ان لا تزيد عن $max";
  }
}
