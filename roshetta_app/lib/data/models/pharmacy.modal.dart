class PharmacyModal {
  String? id;
  String? serId;
  String? type;
  String? logo;
  String? name;
  String? phoneNumber;
  String? owner;
  String? startWorking;
  String? endWorking;
  String? governorate;
  String? address;
  dynamic stuff;
  String? status;
  int? numberOfPrescript;
  int? patientPrescriptNumber;
  int? ordersNumber;

  PharmacyModal({
    this.id,
    this.serId,
    this.type,
    this.logo,
    this.name,
    this.phoneNumber,
    this.owner,
    this.startWorking,
    this.endWorking,
    this.governorate,
    this.stuff,
    this.address,
    this.status,
    this.numberOfPrescript,
    this.patientPrescriptNumber,
    this.ordersNumber,
  });

  PharmacyModal.fromJson(Map<String, dynamic> json) {
    id = json['id'] ?? json['pharmacy_id'];
    serId = json['ser_id'];
    type = json['type'];
    logo = json['logo'];
    name = json['name'];
    phoneNumber = json['phone_number'];
    owner = json['owner'];
    stuff = json['stuff'];
    startWorking = json['start_working'];
    endWorking = json['end_working'];
    governorate = json['governorate'];
    address = json['address'];
    status = json['status'] ?? json['isOpen'];
    numberOfPrescript =
        json['number_of_prescript'] ?? json["number_prescript_pharmacy"];
    patientPrescriptNumber = json['number_prescript_patient'];
    ordersNumber = json['number_of_orders'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['id'] = id;
    data['ser_id'] = serId;
    data['type'] = type;
    data['logo'] = logo;
    data['name'] = name;
    data['phone_number'] = phoneNumber;
    data['owner'] = owner;
    data['stuff'] = stuff;
    data['start_working'] = startWorking;
    data['end_working'] = endWorking;
    data['governorate'] = governorate;
    data['address'] = address;
    data['status'] = status;
    data['number_of_prescript'] = numberOfPrescript;
    data['number_prescript_patient'] = patientPrescriptNumber;
    data['number_of_orders'] = ordersNumber;
    return data;
  }
}
