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
        $this->db->query("INSERT INTO clinic (name,owner,specialist,phone_number,price,start_working,end_working,governorate,address,ser_id,doctor_id,logo,status) 
                                        VALUES (:NAME,:OWNER,:SPECIALIST,:PHONE_NUMBER,:PRICE,:START,:END,:GOVERNORATE,:ADDRESS,:SER_ID,:DOC_ID,:LOGO,0)");
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

        $this->db->query("SELECT assistant.name,birth_date,profile_img,role FROM clinic,assistant WHERE clinic.id = :ID AND assistant_id = assistant.id");
        $this->db->bind(":ID", $clinic_id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_assistant_clinic = $this->db->fetchObject();
        }

        $this->db->query("SELECT doctor.name,birth_date,profile_img,role FROM clinic,doctor WHERE clinic.id = :ID AND doctor_id = doctor.id");
        $this->db->bind(":ID", $clinic_id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
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
    public function getClinicDoc($do_id)
    {
        $this->db->query("SELECT id,logo,name,start_working,end_working,status FROM clinic WHERE doctor_id = :DO_ID");
        $this->db->bind(":DO_ID", $do_id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchAll();
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
    public function editStatus($id, $status)
    {
        $this->db->query("UPDATE clinic SET status = :STATUS WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->bind(":STATUS", $status);
        $this->db->execute();
        if ($this->db->rowCount() >= 0)
            return true;
        else
            false;
    }
    public function addDisease($data = [])
    {
        $this->db->query("INSERT INTO disease(name,place,date,patient_id,doctor_id,clinic_id) VALUES (:NAME,:PLACE,:DATE,:PA_ID,:DO_ID,:CL_ID)");
        $this->db->bind(":NAME", $data['name']);
        $this->db->bind(":PLACE", $data['place']);
        $this->db->bind(":DATE", $data['date']);
        $this->db->bind(":PA_ID", $data['patient_id']);
        $this->db->bind(":DO_ID", $data['doctor_id']);
        $this->db->bind(":CL_ID", $data['clinic_id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }

    public function getDiseaseNew($data = [])
    {
        $this->db->query("SELECT id,patient_id FROM disease WHERE doctor_id = :DO_ID AND clinic_id = :CL_ID AND  patient_id = :PA_ID AND date = :DATE AND name = :NAME AND place = :PLACE");
        $this->db->bind(":NAME", $data['name']);
        $this->db->bind(":PLACE", $data['place']);
        $this->db->bind(":DATE", $data['date']);
        $this->db->bind(":PA_ID", $data['patient_id']);
        $this->db->bind(":DO_ID", $data['doctor_id']);
        $this->db->bind(":CL_ID", $data['clinic_id']);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }
    public function addPrescript($data = [])
    {
        $this->db->query("INSERT INTO prescript(created_date,rediscovery_date,patient_id,doctor_id,disease_id,clinic_id,ser_id) VALUES (:DATE,:RE_DATE,:PA_ID,:DO_ID,:DE_ID,:CL_ID,:SER_ID)");
        $this->db->bind(":DATE", $data['created_date']);
        $this->db->bind(":RE_DATE", $data['rediscovery_date']);
        $this->db->bind(":PA_ID", $data['patient_id']);
        $this->db->bind(":DO_ID", $data['doctor_id']);
        $this->db->bind(":DE_ID", $data['disease_id']);
        $this->db->bind(":CL_ID", $data['clinic_id']);
        $this->db->bind(":SER_ID", $data['ser_id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }
    public function getPrescriptNew($ser_id)
    {
        $this->db->query("SELECT * FROM prescript WHERE ser_id = :SER_ID");
        $this->db->bind(":SER_ID", $ser_id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }
    public function addMedicine($data = [])
    {
        $this->db->query("INSERT INTO medicine(medicine_data,prescript_id) VALUES (:DATA,:PR_ID)");
        $this->db->bind(":DATA", $data['medicine_data']);
        $this->db->bind(":PR_ID", $data['prescript_id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }
    public function getAssistant($clinic_id)
    {
        $this->db->query("SELECT assistant.id AS assistant_id,profile_img,assistant.name,assistant.phone_number FROM assistant,clinic WHERE assistant.id = clinic.assistant_id AND clinic.id = :CL_ID ");
        $this->db->bind(":CL_ID", $clinic_id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function editAssistant($clinic_id, $assistant_id)
    {
        $this->db->query("UPDATE clinic SET assistant_id = :AS_ID WHERE id = :CL_ID");
        $this->db->bind(":CL_ID", $clinic_id);
        $this->db->bind(":AS_ID", $assistant_id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0)
            return true;
        else
            false;
    }
    public function editAppointStatus($clinic_id, $appoint_id,$status)
    {
        $this->db->query("UPDATE appointment SET appoint_case = :STATUS WHERE id = :ID AND clinic_id = :CL_ID");
        $this->db->bind(":CL_ID", $clinic_id);
        $this->db->bind(":ID", $appoint_id);
        $this->db->bind(":STATUS", $status);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }
    public function getAppointClinic($id, $date, $Status)
    {
        $this->db->query("SELECT appointment.id AS appointment_id,appoint_date,appoint_case,patient.id AS patient_id,patient.name,patient.phone_number  FROM  patient,appointment  WHERE appoint_date = :DATE AND appointment.clinic_id = :ID AND appointment.patient_id = patient.id AND appointment.appoint_case = :STATUS ORDER BY appointment.appoint_date");
        $this->db->bind(":ID", $id);
        $this->db->bind(":DATE", $date);
        $this->db->bind(":STATUS", $Status);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function filterAppoint($id, $date, $Status, $filter)
    {
        $this->db->query("SELECT appointment.id AS appointment_id,appoint_date,appoint_case,patient.id AS patient_id,patient.name,patient.phone_number  FROM  patient,appointment WHERE appoint_date = :DATE AND appointment.clinic_id = :ID AND appointment.patient_id = patient.id AND appointment.appoint_case = :STATUS AND (patient.name REGEXP :FILTER OR patient.ssd REGEXP :FILTER OR patient.phone_number REGEXP :FILTER) ORDER BY appointment.appoint_date");
        $this->db->bind(":ID", $id);
        $this->db->bind(":DATE", $date);
        $this->db->bind(":STATUS", $Status);
        $this->db->bind(":FILTER", $filter);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function getDiseasePrescript($id)
    {
        $this->db->query("SELECT prescript.id AS prescript_id,prescript.ser_id AS prescript_ser_id,disease.name AS disease_name FROM prescript,disease WHERE prescript.disease_id = disease.id AND disease.id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function getDiseasePrescriptDetails($prescript_id)
    {
        $this->db->query("SELECT prescript.ser_id AS prescript_ser_id,created_date,patient.name AS patient_name, disease.name AS disease_name,rediscovery_date,doctor.name AS doctor_name,doctor.specialist AS doctor_specialist,logo AS clinic_logo,clinic.name AS clinic_name,clinic.phone_number AS clinic_phone_number,address AS clinic_address,start_working,end_working  FROM  disease,prescript,doctor,clinic,patient WHERE  disease.id = prescript.disease_id AND prescript.patient_id = patient.id  AND prescript.doctor_id = doctor.id AND prescript.clinic_id = clinic.id  AND prescript.id = :PR_ID");
        $this->db->bind(":PR_ID", $prescript_id);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function addChat($data = [])
    {
        $this->db->query("INSERT INTO chat(name,time,image,message,doctor_id) VALUES(:NAME,:TIME,:IMAGE,:MESSAGE,:DOC_ID)");
        $this->db->bind(":NAME", $data['name']);
        $this->db->bind(":TIME", $data['time']);
        $this->db->bind(":IMAGE", $data['image']);
        $this->db->bind(":MESSAGE", $data['message']);
        $this->db->bind(":DOC_ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount() > 0 )
            return true;
        else
            false;
    }
    public function getChat($data = [])
    {
        $this->db->query("SELECT * FROM chat ORDER BY id DESC");
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function deleteChat($id,$do_id)
    {
        $this->db->query("DELETE FROM chat WHERE id = :ID AND doctor_id = :DOC_ID");
        $this->db->bind(":ID", $id);
        $this->db->bind(":DOC_ID", $do_id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0 ) {
            return true;
        } else {
            false;
        }
    }
    public function deleteAppointOld($cl_id,$id)
    {
        $this->db->query("DELETE FROM appointment WHERE clinic_id = :CL_ID AND appoint_case = 0 AND id = :ID");
        $this->db->bind(":CL_ID", $cl_id);
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            return true;
        } else {
            false;
        }
    }
    public function getDateAppoint($id)
    {
        $this->db->query("SELECT * FROM appointment WHERE clinic_id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0 ) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }

}
