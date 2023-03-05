<?php

class Doctor
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function getDoctorActivation($id)
    {
        $this->db->query("SELECT * FROM doctor,activation_person WHERE doctor.id = :ID AND activation_person.user_id = doctor.id AND activation_person.role = 'doctor'");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }
    public function getClinic($id)
    {
        $this->db->query("SELECT * FROM clinic WHERE doctor_id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0)
            $data = $this->db->rowCount();
        return $data;
    }

    public function addClinic($data = [])
    {
        $this->db->query("INSERT INTO clinic (name,owner,specialist,phone_number,price,start_working,end_working,governorate,address,ser_id,doctor_id,logo) 
                                        VALUES (:NAME,:OWNER,:SPECIALIST,:PHONE_NUMBER,:PRICE,:START,:END,:GOVERNORATE,:ADDRESS,:SER_ID,:DOC_ID,:LOGO)");
        $this->db->bind(":NAME", $data['name']);
        $this->db->bind(":OWNER", $data['owner']);
        $this->db->bind(":SPECIALIST", $data['specialist']);
        $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
        $this->db->bind(":PRICE", $data['price']);
        $this->db->bind(":START", $data['start_working']);
        $this->db->bind(":END", $data['end_working']);
        $this->db->bind(":GOVERNORATE", $data['governorate']);
        $this->db->bind(":ADDRESS", $data['address']);
        $this->db->bind(":SER_ID", $data['ser_id']);
        $this->db->bind(":DOC_ID", $data['id']);
        $this->db->bind(":LOGO", $data['image']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }

    public function editClinic($data = [])
    {
        $this->db->query("UPDATE clinic SET phone_number = :PHONE_NUMBER,address = :ADDRESS,price = :PRICE,governorate = :GOVERNORATE,start_working = :START,end_working = :END  WHERE id = :ID AND doctor_id = :DOC_ID");
        $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
        $this->db->bind(":PRICE", $data['price']);
        $this->db->bind(":START", $data['start_working']);
        $this->db->bind(":END", $data['end_working']);
        $this->db->bind(":GOVERNORATE", $data['governorate']);
        $this->db->bind(":ADDRESS", $data['address']);
        $this->db->bind(":ID", $data['clinic_id']);
        $this->db->bind(":DOC_ID", $data['doctor_id']);
        $this->db->execute();
        if ($this->db->rowCount() > 0)
            return true;
        else
            false;
    }

    public function numberAppointPres($clinic_id)
    {
        $this->db->query("SELECT * FROM appointment WHERE clinic_id = :ID");
        $this->db->bind(":ID", $clinic_id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_appoint_clinic = $this->db->rowCount();
        }
        $date = date('Y-m-d');
        $this->db->query("SELECT * FROM appointment WHERE clinic_id = :ID AND appoint_date = :DATE");
        $this->db->bind(":ID", $clinic_id);
        $this->db->bind(":DATE", $date);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_appoint_clinic_date = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM prescript WHERE clinic_id = :ID");
        $this->db->bind(":ID", $clinic_id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_prescript_clinic = $this->db->rowCount();
        }

        $this->db->query("SELECT assistant.name,birth_date,profile_img FROM clinic,assistant WHERE clinic.id = :ID AND assistant_id = assistant.id");
        $this->db->bind(":ID", $clinic_id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data_assistant_clinic = $this->db->fetchObject();
        }

        $this->db->query("SELECT doctor.name,birth_date,profile_img FROM clinic,doctor WHERE clinic.id = :ID AND doctor_id = doctor.id");
        $this->db->bind(":ID", $clinic_id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data_doctor_clinic = $this->db->fetchObject();
        }

        $data = [
            "num_appoint" => $data_appoint_clinic,
            "num_ap_day" => $data_appoint_clinic_date,
            "num_pres" => $data_prescript_clinic,
            "data_doctor" => $data_doctor_clinic,
            "data_assistant" => $data_assistant_clinic
        ];
        return $data;
    }

    public function getClinicActivation($id)
    {
        $this->db->query("SELECT * FROM clinic,activation_place WHERE clinic.id = :ID AND activation_place.place_id = clinic.id AND activation_place.role = 'clinic'");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }

    public function loginClinic($cl_id, $do_id)
    {
        $this->db->query("SELECT * FROM clinic WHERE doctor_id = :DO_ID AND id = :CL_ID");
        $this->db->bind(":DO_ID", $do_id);
        $this->db->bind(":CL_ID", $cl_id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }

    public function editImage($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("UPDATE $table_name SET logo = :IMAGE WHERE id = :ID");
        $this->db->bind(":IMAGE", $data['image']);
        $this->db->bind(":ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount() >= 0)
            return true;
        else
            false;
    }
}
