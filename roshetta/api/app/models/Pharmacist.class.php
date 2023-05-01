<?php

class Pharmacist
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    public function getPharmacistActivation($id)
    {
        $this->db->query("SELECT * FROM pharmacist,activation_person WHERE pharmacist.id = :ID AND activation_person.user_id = pharmacist.id AND activation_person.role = 'pharmacist'");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }

    public function getPharmacy($id)
    {
        $this->db->query("SELECT * FROM pharmacy WHERE pharmacist_id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0)
            $data = $this->db->rowCount();
        return $data;
    }
    public function addPharmacy($data = [])
    {
        $this->db->query("INSERT INTO pharmacy (name,owner,phone_number,start_working,end_working,governorate,address,ser_id,pharmacist_id,logo,status) 
                                        VALUES (:NAME,:OWNER,:PHONE_NUMBER,:START,:END,:GOVERNORATE,:ADDRESS,:SER_ID,:PHA_ID,:LOGO,0)");
        $this->db->bind(":NAME", $data['name']);
        $this->db->bind(":OWNER", $data['owner']);
        $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
        $this->db->bind(":START", $data['start_working']);
        $this->db->bind(":END", $data['end_working']);
        $this->db->bind(":GOVERNORATE", $data['governorate']);
        $this->db->bind(":ADDRESS", $data['address']);
        $this->db->bind(":SER_ID", $data['ser_id']);
        $this->db->bind(":PHA_ID", $data['id']);
        $this->db->bind(":LOGO", $data['image']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }
    public function getPharmacyActivation($id)
    {
        $this->db->query("SELECT * FROM pharmacy,activation_place WHERE pharmacy.id = :ID AND activation_place.place_id = pharmacy.id AND activation_place.role = 'pharmacy'");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }
    public function editPharmacy($data = [])
    {
        $this->db->query("UPDATE pharmacy SET phone_number = :PHONE_NUMBER,address = :ADDRESS,governorate = :GOVERNORATE,start_working = :START,end_working = :END  WHERE id = :ID AND pharmacist_id = :PHA_ID");
        $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
        $this->db->bind(":START", $data['start_working']);
        $this->db->bind(":END", $data['end_working']);
        $this->db->bind(":GOVERNORATE", $data['governorate']);
        $this->db->bind(":ADDRESS", $data['address']);
        $this->db->bind(":ID", $data['pharmacy_id']);
        $this->db->bind(":PHA_ID", $data['pharmacist_id']);
        $this->db->execute();
        if ($this->db->rowCount() > 0)
            return true;
        else
            false;
    }
    public function numberPrescript($pharmacy_id)
    {
        $this->db->query("SELECT * FROM pharmacy_prescript,pharmacy WHERE pharmacy.id = :ID AND pharmacy.id = pharmacy_prescript.pharmacy_id");
        $this->db->bind(":ID", $pharmacy_id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_pres = $this->db->rowCount();
        }

        $this->db->query("SELECT pharmacist.name,birth_date,profile_img FROM pharmacy,pharmacist WHERE pharmacy.id = :ID AND pharmacist_id = pharmacist.id");
        $this->db->bind(":ID", $pharmacy_id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_pharmacist = $this->db->fetchObject();
        }

        $this->db->query("SELECT * FROM pharmacy_order WHERE pharmacy_id = :ID");
        $this->db->bind(":ID", $pharmacy_id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_order = $this->db->rowCount();
        }

        $data = [
            "num_pres" => $data_pres,
            "data_pharmacist" => $data_pharmacist,
            'data_order' => $data_order,
        ];

        return $data;
    }
    public function loginPharmacy($ph_id, $do_id)
    {
        $this->db->query("SELECT * FROM pharmacy WHERE pharmacist_id = :DO_ID AND id = :PH_ID");
        $this->db->bind(":DO_ID", $do_id);
        $this->db->bind(":PH_ID", $ph_id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }
    public function editStatus($id, $status)
    {
        $this->db->query("UPDATE pharmacy SET status = :STATUS WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->bind(":STATUS", $status);
        $this->db->execute();
        if ($this->db->rowCount() >= 0)
            return true;
        else
            false;
    }
    public function getPharmacistDoc($do_id)
    {
        $this->db->query("SELECT id,logo,name,start_working,end_working,status FROM pharmacy WHERE pharmacist_id = :DO_ID");
        $this->db->bind(":DO_ID", $do_id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function getPrescript($ssd)
    {
        $this->db->query("SELECT prescript.id AS prescript_id,ser_id,created_date,name AS patient_name FROM patient,prescript WHERE patient.id = prescript.patient_id AND patient.ssd = :SSD  ORDER BY created_date DESC");
        $this->db->bind(":SSD", $ssd);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function getPrescriptDetails($prescript_id)
    {
        $this->db->query("SELECT prescript.ser_id AS prescript_ser_id,created_date,patient.name AS patient_name, disease.name AS disease_name,rediscovery_date,doctor.name AS doctor_name,doctor.specialist AS doctor_specialist,logo AS clinic_logo,clinic.name AS clinic_name,clinic.phone_number AS clinic_phone_number,address AS clinic_address,start_working,end_working  FROM  disease,prescript,doctor,clinic,patient WHERE  disease.id = prescript.disease_id AND prescript.patient_id = patient.id AND prescript.doctor_id = doctor.id AND prescript.clinic_id = clinic.id  AND (prescript.id = :PR_ID XOR prescript.ser_id = :PR_ID)");
        $this->db->bind(":PR_ID", $prescript_id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function getPrescriptMedicine($prescript_id)
    {
        $this->db->query("SELECT medicine_data FROM medicine,prescript WHERE (medicine.prescript_id = :PR_ID OR (prescript.ser_id = :PR_ID AND medicine.prescript_id = prescript.id))");
        $this->db->bind(":PR_ID", $prescript_id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function getOrder($id)
    {
        $this->db->query("SELECT pharmacy_order.id AS order_id,pharmacy_order.time,pharmacy_order.prescript_id,prescript.ser_id,patient.name AS patient_name,patient.phone_number AS patient_phone_number,pharmacy_order.status FROM pharmacy_order,patient,prescript,pharmacy WHERE pharmacy.id = :ID AND pharmacy_order.pharmacy_id = pharmacy.id AND pharmacy_order.prescript_id = prescript.id AND pharmacy_order.patient_id = patient.id ");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function getOrderFilter($id, $filter)
    {
        $this->db->query("SELECT pharmacy_order.id AS order_id,pharmacy_order.time,pharmacy_order.prescript_id,prescript.ser_id,patient.name AS patient_name,patient.phone_number AS patient_phone_number,pharmacy_order.status FROM pharmacy_order,patient,prescript,pharmacy WHERE pharmacy.id = :ID AND pharmacy_order.pharmacy_id = pharmacy.id AND pharmacy_order.prescript_id = prescript.id AND pharmacy_order.patient_id = patient.id AND (patient.name REGEXP :FILTER OR patient.ssd REGEXP :FILTER OR prescript.ser_id REGEXP :FILTER)");
        $this->db->bind(":ID", $id);
        $this->db->bind(":FILTER", $filter);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function getOrderPay($id)
    {
        $this->db->query("SELECT prescript.id AS prescript_id,prescript.ser_id AS prescript_ser_id,date_pay,patient.name AS patient_name FROM prescript,patient,pharmacy_prescript,pharmacy WHERE prescript.patient_id = patient.id AND pharmacy.id = :ID AND pharmacy_prescript.pharmacy_id = pharmacy.id AND pharmacy_prescript.prescript_id = prescript.id  ORDER BY date_pay DESC");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function getOrderPayFilter($id, $filter)
    {
        $this->db->query("SELECT prescript.id AS prescript_id,prescript.ser_id AS prescript_ser_id,date_pay,patient.name AS patient_name FROM prescript,patient,pharmacy_prescript,pharmacy WHERE prescript.patient_id = patient.id AND pharmacy.id = :ID AND pharmacy_prescript.pharmacy_id = pharmacy.id AND pharmacy_prescript.prescript_id = prescript.id AND (patient.name REGEXP :FILTER OR patient.ssd REGEXP :FILTER OR prescript.ser_id REGEXP :FILTER) ORDER BY date_pay DESC");
        $this->db->bind(":ID", $id);
        $this->db->bind(":FILTER", $filter);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function payPrescript($ph_id, $pr_id)
    {
        $this->db->query("INSERT INTO pharmacy_prescript(prescript_id,pharmacy_id) VALUES(:PR_ID,:PH_ID)");
        $this->db->bind(":PH_ID", $ph_id);
        $this->db->bind(":PR_ID", $pr_id);
        $this->db->execute();
        if ($this->db->rowCount() > 0)
            return true;
        else
            false;
    }
    public function editStatusPre($id)
    {
        $this->db->query("UPDATE pharmacy_order SET status = 1 WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0)
            return true;
        else
            false;
    }
}