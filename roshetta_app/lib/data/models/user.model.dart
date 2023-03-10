import 'dart:convert';

import 'package:intl/intl.dart';

class User {
  String? role;
  String? firstName;
  String? lastName;
  String? email;
  String? governorate;
  String? gender;
  String? ssd;
  String? phoneNumber;
  String? birthDate;
  String? password;
  String? confirmPassword;
  String? weight;
  String? height;
  String? specialist;
  int? prescriptsNumber;
  int? diseasesNumber;
  int? appointNumbers;

  User(
      {this.role,
      this.firstName,
      this.lastName,
      this.email,
      this.governorate,
      this.gender,
      this.ssd,
      this.phoneNumber,
      this.birthDate,
      this.password,
      this.confirmPassword,
      this.weight,
      this.height,
      this.specialist,
      this.appointNumbers,
      this.diseasesNumber,
      this.prescriptsNumber});

  User.fromJson(Map<String, dynamic> json) {
    role = json['role'] ?? json['type'];
    firstName = json['first_name'];
    lastName = json['last_name'];
    email = json['email'];
    governorate = json['governorate'];
    gender = json['gender'];
    ssd = json['ssd'];
    phoneNumber = json['phone_number'];
    birthDate = json['birth_date'];
    password = json['password'];
    confirmPassword = json['confirm_password'];
    weight = json['weight'];
    height = json['height'];
    prescriptsNumber = json['number_prescript'];
    diseasesNumber = json['number_disease'];
    appointNumbers = json['number_appoint'];
    specialist = json['specialist'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['role'] = role;
    data['first_name'] = firstName;
    data['last_name'] = lastName;
    data['email'] = email;
    data['governorate'] = governorate;
    data['gender'] = gender;
    data['ssd'] = ssd;
    data['phone_number'] = phoneNumber;
    data['birth_date'] = DateFormat("dd-MM-yyyy").parse(birthDate!);
    data['password'] = password;
    data['confirm_password'] = confirmPassword;
    data['weight'] = weight;
    data['height'] = height;
    data['number_appoint'] = appointNumbers;
    data['number_disease'] = diseasesNumber;
    data['number_prescript'] = prescriptsNumber;
    data['specialist'] = specialist;
    return data;
  }
}

class LocalUser {
  String? token;
  int? expiredToken;
  String? name;
  String? ssd;
  String? type;
  String? image;

  LocalUser(
      {this.expiredToken,
      this.name,
      this.ssd,
      this.type,
      this.image,
      this.token});

  LocalUser.fromJson(Map<String, dynamic> json) {
    token = json['token'];
    expiredToken = json['expiredToken'];
    type = json['type'];
    name = json['name'];
    ssd = json['ssd'];
    image = json['image'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data["token"] = token;
    data['expiredToken'] = expiredToken;
    data['type'] = type;
    data['name'] = name;
    data['ssd'] = ssd;
    data['image'] = image;
    return data;
  }

  String get encodeUser => json.encode(toJson());
}
