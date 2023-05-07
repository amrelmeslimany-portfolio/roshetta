import 'package:roshetta_app/core/class/enums.dart';

class Prescript {
  String? prescriptId;
  String? prescriptSerId;
  String? createdDate;
  String? patientName;
  String? diseaseName;
  String? rediscoveryDate;
  String? doctorName;
  String? doctorSpecialist;
  String? clinicLogo;
  String? clinicName;
  String? clinicPhoneNumber;
  String? clinicAddress;
  String? startWorking;
  String? endWorking;
  PrescriptStatus? prescriptStatus;
  List? medicineData;

  Prescript(
      {this.prescriptId,
      this.prescriptSerId,
      this.createdDate,
      this.patientName,
      this.diseaseName,
      this.rediscoveryDate,
      this.doctorName,
      this.doctorSpecialist,
      this.clinicLogo,
      this.clinicName,
      this.clinicPhoneNumber,
      this.clinicAddress,
      this.startWorking,
      this.endWorking,
      this.prescriptStatus,
      this.medicineData});

  Prescript.fromJson(Map<String, dynamic> json) {
    prescriptId = json['prescript_id'];
    prescriptSerId = json['prescript_ser_id'];
    createdDate = json['created_date'];
    patientName = json['patient_name'];
    diseaseName = json['disease_name'];
    rediscoveryDate = json['rediscovery_date'];
    doctorName = json['doctor_name'];
    doctorSpecialist = json['doctor_specialist'];
    clinicLogo = json['clinic_logo'];
    clinicName = json['clinic_name'];
    clinicPhoneNumber = json['clinic_phone_number'];
    clinicAddress = json['clinic_address'];
    startWorking = json['start_working'];
    endWorking = json['end_working'];
    prescriptStatus = _checkPrescriptStatus(json['prescriptStatus'] ?? "none");
    medicineData = json['medicine_data'];
  }

  PrescriptStatus _checkPrescriptStatus(String status) {
    switch (status) {
      case "isOrders":
        return PrescriptStatus.isOrder;
      case "done":
        return PrescriptStatus.done;
      default:
        return PrescriptStatus.none;
    }
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['prescript_id'] = prescriptId;
    data['prescript_ser_id'] = prescriptSerId;
    data['created_date'] = createdDate;
    data['patient_name'] = patientName;
    data['disease_name'] = diseaseName;
    data['rediscovery_date'] = rediscoveryDate;
    data['doctor_name'] = doctorName;
    data['doctor_specialist'] = doctorSpecialist;
    data['clinic_logo'] = clinicLogo;
    data['clinic_name'] = clinicName;
    data['clinic_phone_number'] = clinicPhoneNumber;
    data['clinic_address'] = clinicAddress;
    data['start_working'] = startWorking;
    data['end_working'] = endWorking;
    data['medicine_data'] = medicineData;
    data['prescriptStatus'] = prescriptStatus;
    return data;
  }
}
