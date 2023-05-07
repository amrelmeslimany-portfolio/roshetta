class ClinicModal {
  String? id;
  String? serId;
  String? type;
  String? logo;
  String? name;
  String? specialist;
  String? phoneNumber;
  String? owner;
  String? price;
  String? startWorking;
  String? endWorking;
  String? governorate;
  String? address;
  dynamic stuff;
  String? status;
  String? appointDate;
  int? appointCase;
  int? appointAll;
  int? appointDay;
  int? numberOfPrescript;
  int? numberAppointClinic;
  int? numberAppointPatient;

  ClinicModal(
      {this.id,
      this.serId,
      this.type,
      this.logo,
      this.name,
      this.specialist,
      this.phoneNumber,
      this.owner,
      this.price,
      this.startWorking,
      this.endWorking,
      this.governorate,
      this.stuff,
      this.address,
      this.status,
      this.appointDate,
      this.appointCase,
      this.appointAll,
      this.appointDay,
      this.numberOfPrescript,
      this.numberAppointPatient});

  ClinicModal.fromJson(Map<String, dynamic> json) {
    id = json['id'] ?? json['clinic_id'];
    serId = json['ser_id'];
    type = json['type'];
    logo = json['logo'];
    name = json['name'];
    specialist = json['specialist'];
    phoneNumber = json['phone_number'];
    owner = json['owner'];
    price = json['price'];
    stuff = json['stuff'];
    startWorking = json['start_working'];
    endWorking = json['end_working'];
    governorate = json['governorate'];
    address = json['address'];
    appointDate = json['appoint_date'];
    status = json['status'] ?? json['isOpen'];
    appointCase = json['appoint_case'];
    appointAll = json['appoint_all'] ?? json["number_appoint_clinic"];
    appointDay = json['appoint_day'];
    numberOfPrescript = json['number_of_prescript'];
    numberAppointPatient = json['number_appoint_patient'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['id'] = id;
    data['ser_id'] = serId;
    data['type'] = type;
    data['logo'] = logo;
    data['name'] = name;
    data['specialist'] = specialist;
    data['phone_number'] = phoneNumber;
    data['owner'] = owner;
    data['stuff'] = stuff;
    data['price'] = price;
    data['start_working'] = startWorking;
    data['end_working'] = endWorking;
    data['governorate'] = governorate;
    data['address'] = address;
    data['status'] = status;
    data['appoint_date'] = appointDate;
    data['appoint_case'] = appointCase;
    data['appoint_all'] = appointAll;
    data['appoint_day'] = appointDay;
    data['number_of_prescript'] = numberOfPrescript;
    data['number_appoint_patient'] = numberAppointPatient;
    return data;
  }
}
