import 'dart:convert';

import 'package:roshetta_app/core/functions/quick_functions.dart';

class User {
  String? role;
  String? firstName;
  String? lastName;
  String? image;
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
  int? clinicNumbers;
  int? pharmacyNumber;
  int? pharmacyOrderNumber;
  int? todayAppointments;

  User(
      {this.role,
      this.firstName,
      this.lastName,
      this.email,
      this.image,
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
      this.clinicNumbers,
      this.pharmacyNumber,
      this.pharmacyOrderNumber,
      this.prescriptsNumber,
      this.todayAppointments});

  User.fromJson(Map<String, dynamic> json) {
    role = json['role'] ?? json['type'];
    firstName = json['first_name'];
    lastName = json['last_name'];
    email = json['email'];
    image = json['image'];
    governorate = json['governorate'];
    gender = json['gender'];
    ssd = json['ssd'];
    phoneNumber = json['phone_number'];
    birthDate = json['birth_date'] ?? json["age"];
    password = json['password'];
    confirmPassword = json['confirm_password'];
    weight = json['weight'];
    height = json['height'];
    prescriptsNumber = json['number_prescript'];
    diseasesNumber = json['number_disease'];
    appointNumbers = json['number_appoint'] ?? json["number_all_appointment"];
    clinicNumbers = json['number_clinic'];
    pharmacyNumber = json['number_pharmacy'];
    pharmacyOrderNumber = json['number_order'];
    todayAppointments = json['number_today_appoint'];
    specialist = json['specialist'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['role'] = role;
    data['first_name'] = firstName;
    data['last_name'] = lastName;
    data['email'] = email;
    data['image'] = image;
    data['governorate'] = governorate;
    data['gender'] = gender;
    data['ssd'] = ssd;
    data['phone_number'] = phoneNumber;
    data['birth_date'] = getParsedDate(birthDate!);
    data['password'] = password;
    data['confirm_password'] = confirmPassword;
    data['weight'] = weight;
    data['height'] = height;
    data['number_appoint'] = appointNumbers;
    data['number_disease'] = diseasesNumber;
    data['number_prescript'] = prescriptsNumber;
    data['number_clinic'] = clinicNumbers;
    data['number_pharmacy'] = pharmacyNumber;
    data['number_order'] = pharmacyOrderNumber;
    data['specialist'] = specialist;
    data['number_today_appoint'] = todayAppointments;
    return data;
  }
}

class Patient {
  String? id;
  String? name;
  String? image;
  String? phone;
  String? weight;
  String? height;
  String? age;
  String? appointCase;

  Patient({
    this.id,
    this.name,
    this.image,
    this.phone,
    this.age,
    this.weight,
    this.height,
    this.appointCase,
  });

  Patient.fromJson(Map<String, dynamic> json) {
    id = json['patient_id'];
    name = json['name'];
    image = json['image'];
    phone = json['phone_number'];
    age = json['age'];
    weight = json['weight'];
    height = json['height'];
    appointCase = json['appoint_case'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['patient_id'] = id;
    data['name'] = name;
    data['image'] = image;
    data['phone_number'] = phone;
    data['weight'] = weight;
    data['height'] = height;
    data['appoint_case'] = appointCase;

    return data;
  }
}

class LocalUser {
  String? token;
  int? expiredToken;
  String? name;
  String? ssd;
  String? type;
  String? isVerify;
  String? image;

  LocalUser(
      {this.expiredToken,
      this.name,
      this.ssd,
      this.type,
      this.isVerify,
      this.image,
      this.token});

  LocalUser.fromJson(Map<String, dynamic> json) {
    token = json['token'];
    expiredToken = json['expiredToken'];
    type = json['type'];
    name = json['name'];
    isVerify = json['isVerify'];
    ssd = json['ssd'];
    image = json['image'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data["token"] = token;
    data['expiredToken'] = expiredToken;
    data['type'] = type;
    data['name'] = name;
    data['isVerify'] = isVerify;
    data['ssd'] = ssd;
    data['image'] = image;
    return data;
  }

  String get encodeUser => json.encode(toJson());
}
