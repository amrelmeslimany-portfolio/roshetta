<?php

class Patient
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function getPlace($id,$type)
    {
        $this->db->query("SELECT * FROM $type WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }

    public function addAppointPatient($data = [])
    {
        $this->db->query("INSERT INTO appointment(appoint_date,patient_id,clinic_id,appoint_case) VALUES (:APPOINT_DATE,:PA_ID,:CL_ID,0)");
        $this->db->bind(":APPOINT_DATE", $data['appoint_date']);
        $this->db->bind(":PA_ID", $data['patient_id']);
        $this->db->bind(":CL_ID", $data['clinic_id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }

    public function getDataAppointPatient($id)
    {
        $this->db->query("SELECT appointment.id AS appointment_id,logo,name,phone_number,start_working,end_working,specialist,address,appoint_date FROM clinic,appointment WHERE clinic.id = appointment.clinic_id AND appointment.patient_id = :ID AND appoint_case = 0 ORDER BY appoint_date DESC");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }

    public function editAppointPatient($data = [])
    {
        $this->db->query("UPDATE appointment SET appoint_date = :APPOINT_DATE , clinic_id = :CL_ID WHERE id = :AP_ID");
        $this->db->bind(":APPOINT_DATE", $data['appoint_date']);
        $this->db->bind(":AP_ID", $data['appoint_id']);
        $this->db->bind(":CL_ID", $data['clinic_id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }

    public function getAppoint($id)
    {
        $this->db->query("SELECT * FROM appointment WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }

    public function deleteAppointPatient($id)
    {
        $this->db->query("DELETE FROM appointment WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }

    public function getDataClinic()
    {
        $this->db->query("SELECT * FROM activation_place,clinic WHERE activation_place.isActive = 1 AND activation_place.place_id = clinic.id AND activation_place.role = 'clinic'");
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }

    public function filterClinic($filter)
    {
        $this->db->query("SELECT * FROM activation_place,clinic WHERE clinic.specialist = :FILTER AND activation_place.isActive = 1 AND activation_place.place_id = clinic.id AND activation_place.role = 'clinic'");
        $this->db->bind(":FILTER", $filter);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }

    public function viewClinicDetails($clinic_id,$patient_id)
    {
        $this->db->query("SELECT * FROM activation_place,clinic WHERE clinic.id = :ID AND activation_place.isActive = 1 AND activation_place.place_id = clinic.id AND activation_place.role = 'clinic'");
        $this->db->bind(":ID", $clinic_id);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {

            $data = $this->db->fetchObject();

            $this->db->query("SELECT * FROM appointment WHERE clinic_id = :ID");
            $this->db->bind(":ID", $clinic_id);
            $this->db->execute();
            if ($this->db->rowCount() >= 0) {
                $data_appoint_clinic = $this->db->rowCount();
            }

            $this->db->query("SELECT * FROM appointment WHERE clinic_id = :ID AND patient_id = :PA_ID");
            $this->db->bind(":ID", $clinic_id);
            $this->db->bind(":PA_ID", $patient_id);
            $this->db->execute();
            if ($this->db->rowCount() >= 0) {
                $data_appoint_patient = $this->db->rowCount();
            }

            $data_all = [
                "data_clinic" => $data,
                "number_appoint_clinic" => $data_appoint_clinic,
                "number_appoint_patient" => $data_appoint_patient
            ];

            return $data_all ;
        } else {
            false;
        }
    }

    public function getDataDisease($id)
    {
        $this->db->query("SELECT id AS disease_id ,name,place,date FROM disease WHERE patient_id = :ID ORDER BY date DESC");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }

    public function getDataPharmacy()
    {
        $this->db->query("SELECT * FROM activation_place,pharmacy WHERE activation_place.isActive = 1 AND activation_place.place_id = pharmacy.id AND activation_place.role = 'pharmacy'");
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }

    public function viewPharmacyDetails($pharmacy_id,$patient_id)
    {
        $this->db->query("SELECT * FROM activation_place,pharmacy WHERE pharmacy.id = :ID AND activation_place.isActive = 1 AND activation_place.place_id = pharmacy.id AND activation_place.role = 'pharmacy'");
        $this->db->bind(":ID", $pharmacy_id);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {

            $data = $this->db->fetchObject();

            $this->db->query("SELECT * FROM pharmacy_prescript WHERE pharmacy_id = :ID");
            $this->db->bind(":ID", $pharmacy_id);
            $this->db->execute();
            if ($this->db->rowCount() >= 0) {
                $data_prescript_pharmacy = $this->db->rowCount();
            }

            $this->db->query("SELECT * FROM pharmacy_prescript,prescript WHERE pharmacy_id = :ID AND prescript_id = prescript.id AND patient_id = :PA_ID");
            $this->db->bind(":ID", $pharmacy_id);
            $this->db->bind(":PA_ID", $patient_id);
            $this->db->execute();
            if ($this->db->rowCount() >= 0) {
                $data_prescript_patient = $this->db->rowCount();
            }

            $data_all = [
                "data_pharmacy" => $data,
                "number_prescript_pharmacy" => $data_prescript_pharmacy,
                "number_prescript_patient" => $data_prescript_patient
            ];

            return $data_all ;
        } else {
            false;
        }
    }

    public function getDataPrescript($id)
    {
        $this->db->query("SELECT prescript.id AS prescript_id,ser_id,created_date,name AS disease_name FROM disease,prescript WHERE disease.id = prescript.disease_id AND prescript.patient_id = :ID  ORDER BY created_date DESC");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function addOrderPatient($data = [])
    {
        $this->db->query("INSERT INTO pharmacy_order(patient_id,prescript_id,pharmacy_id) VALUES (:PA_ID,:PR_ID,:PH_ID)");
        $this->db->bind(":PA_ID", $data['patient_id']);
        $this->db->bind(":PR_ID", $data['prescript_id']);
        $this->db->bind(":PH_ID", $data['pharmacy_id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }

    public function getPrescriptDetails($prescript_id , $patient_id)
    {
        $this->db->query("SELECT prescript.ser_id AS prescript_ser_id,created_date,patient.name AS patient_name, disease.name AS disease_name,rediscovery_date,doctor.name AS doctor_name,doctor.specialist AS doctor_specialist,logo AS clinic_logo,clinic.name AS clinic_name,clinic.phone_number AS clinic_phone_number,address AS clinic_address,start_working,end_working  FROM  disease,prescript,doctor,clinic,patient WHERE  disease.id = prescript.disease_id AND prescript.patient_id = patient.id AND patient.id = :PA_ID AND prescript.doctor_id = doctor.id AND prescript.clinic_id = clinic.id  AND prescript.id = :PR_ID");
        $this->db->bind(":PR_ID", $prescript_id);
        $this->db->bind(":PA_ID", $patient_id);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }

    public function getPrescriptMedicine($prescript_id , $patient_id)
    {
        $this->db->query("SELECT medicine_data FROM medicine,patient,prescript WHERE medicine.prescript_id = :PR_ID AND patient.id = :PA_ID");
        $this->db->bind(":PR_ID", $prescript_id);
        $this->db->bind(":PA_ID", $patient_id);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
}
