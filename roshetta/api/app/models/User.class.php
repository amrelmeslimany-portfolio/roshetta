<?php

class User
{

    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function getActivation($id, $type)
    {
        if ($type == 'doctor' || $type == 'pharmacist') {
            $table_name = 'activation_person';
            $user_id = 'user_id';
        } else {
            $table_name = 'activation_place';
            $user_id = 'place_id';
        }

        $this->db->query("SELECT isActive FROM $table_name WHERE $user_id = :ID AND role = :TYPE");
        $this->db->bind(":TYPE", $type);
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            return false;
        }

    }
    public function getUserEmail($email, $table_name)  // Check User Email
    {
        $this->db->query("SELECT * FROM $table_name WHERE email = :EMAIL");
        $this->db->bind(":EMAIL", $email);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function getUserSSD($ssd, $table_name)  // Check User SSD
    {
        $this->db->query("SELECT * FROM $table_name WHERE ssd = :SSD");
        $this->db->bind(":SSD", $ssd);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function getUserPhone($phone, $table_name)  // Check User Phone
    {
        $this->db->query("SELECT * FROM $table_name WHERE phone_number = :PHONE_NUMBER");
        $this->db->bind(":PHONE_NUMBER", $phone);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            return false;
        }
    }
    public function registerPatient($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("INSERT INTO $table_name (name,ssd,email,phone_number,gender,birth_date,weight,height,governorate,password,security_code,profile_img,email_isActive,role) 
                                        VALUES (:NAME,:SSD,:EMAIL,:PHONE_NUMBER,:GENDER,:BIRTH_DATE,:WEIGHT,:HEIGHT,:GOVERNORATE,:PASSWORD,:SECURITY_CODE,:IMAGE,0,:ROLE)");
        $this->db->bind(":NAME", $data['name']);
        $this->db->bind(":SSD", $data['ssd']);
        $this->db->bind(":EMAIL", $data['email']);
        $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
        $this->db->bind(":GENDER", $data['gender']);
        $this->db->bind(":BIRTH_DATE", $data['birth_date']);
        $this->db->bind(":WEIGHT", $data['weight']);
        $this->db->bind(":HEIGHT", $data['height']);
        $this->db->bind(":GOVERNORATE", $data['governorate']);
        $this->db->bind(":PASSWORD", $data['password']);
        $this->db->bind(":SECURITY_CODE", $data['security_code']);
        $this->db->bind(":ROLE", $data['type']);
        $this->db->bind(":IMAGE", $data['image']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function registerDoctor($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("INSERT INTO $table_name (name,ssd,email,phone_number,gender,birth_date,specialist,governorate,password,security_code,profile_img,email_isActive,role) 
                                        VALUES (:NAME,:SSD,:EMAIL,:PHONE_NUMBER,:GENDER,:BIRTH_DATE,:SPECIALIST,:GOVERNORATE,:PASSWORD,:SECURITY_CODE,:IMAGE,0,:ROLE)");
        $this->db->bind(":NAME", $data['name']);
        $this->db->bind(":SSD", $data['ssd']);
        $this->db->bind(":EMAIL", $data['email']);
        $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
        $this->db->bind(":GENDER", $data['gender']);
        $this->db->bind(":BIRTH_DATE", $data['birth_date']);
        $this->db->bind(":SPECIALIST", $data['specialist']);
        $this->db->bind(":GOVERNORATE", $data['governorate']);
        $this->db->bind(":PASSWORD", $data['password']);
        $this->db->bind(":SECURITY_CODE", $data['security_code']);
        $this->db->bind(":ROLE", $data['type']);
        $this->db->bind(":IMAGE", $data['image']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function registerOther($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("INSERT INTO $table_name (name,ssd,email,phone_number,gender,birth_date,governorate,password,security_code,profile_img,email_isActive,role) 
                                        VALUES (:NAME,:SSD,:EMAIL,:PHONE_NUMBER,:GENDER,:BIRTH_DATE,:GOVERNORATE,:PASSWORD,:SECURITY_CODE,:IMAGE,0,:ROLE)");
        $this->db->bind(":NAME", $data['name']);
        $this->db->bind(":SSD", $data['ssd']);
        $this->db->bind(":EMAIL", $data['email']);
        $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
        $this->db->bind(":GENDER", $data['gender']);
        $this->db->bind(":BIRTH_DATE", $data['birth_date']);
        $this->db->bind(":GOVERNORATE", $data['governorate']);
        $this->db->bind(":PASSWORD", $data['password']);
        $this->db->bind(":SECURITY_CODE", $data['security_code']);
        $this->db->bind(":ROLE", $data['type']);
        $this->db->bind(":IMAGE", $data['image']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function login($data)
    {
        $table_name = $data['type'];
        $this->db->query("SELECT * FROM $table_name WHERE (ssd = :USER_ID OR email = :USER_ID)");
        $this->db->bind(":USER_ID", $data['user_id']);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            return false;
        }
    }
    public function editToken($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("UPDATE $table_name SET token = :TOKEN WHERE id = :ID");
        $this->db->bind(":TOKEN", $data['token']);
        $this->db->bind(":ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function getToken($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("SELECT token FROM $table_name WHERE id = :ID");
        $this->db->bind(":ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            return false;
        }
    }
    public function activeEmail($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("UPDATE $table_name SET email_isActive = 1 , security_code = :CODE WHERE email = :EMAIL");
        $this->db->bind(":CODE", $data['code']);
        $this->db->bind(":EMAIL", $data['email']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function viewProfile($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("SELECT * FROM $table_name WHERE id = :ID");
        $this->db->bind(":ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            return false;
        }
    }
    public function editPassword($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("UPDATE $table_name SET password = :PASSWORD WHERE (id = :USER_ID OR ssd = :USER_ID OR email = :USER_ID)");
        $this->db->bind(":PASSWORD", $data['password']);
        $this->db->bind(":USER_ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function editPatient($data = [])
    {
        $this->db->query("UPDATE patient SET phone_number = :PHONE_NUMBER , weight = :WEIGHT , height = :HEIGHT , governorate = :GOVERNORATE  WHERE id = :ID");
        $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
        $this->db->bind(":WEIGHT", $data['weight']);
        $this->db->bind(":HEIGHT", $data['height']);
        $this->db->bind(":GOVERNORATE", $data['governorate']);
        $this->db->bind(":ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function editOther($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("UPDATE $table_name SET phone_number = :PHONE , governorate = :GOVERNORATE  WHERE id = :ID");
        $this->db->bind(":PHONE", $data['phone_number']);
        $this->db->bind(":GOVERNORATE", $data['governorate']);
        $this->db->bind(":ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function getSSD($table_name, $id)  // Check User SSD
    {
        $this->db->query("SELECT ssd FROM $table_name WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $ssd = $this->db->fetchObject();
            return $ssd;
        } else {
            return false;
        }
    }
    public function editImage($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("UPDATE $table_name SET profile_img = :IMAGE WHERE id = :ID");
        $this->db->bind(":IMAGE", $data['image']);
        $this->db->bind(":ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount() >= 0)
            return true;
        else
            return false;
    }
    public function addMessageUser($data = [])
    {
        $this->db->query("INSERT INTO message(name,email,ssd,role,message) VALUES(:NAME,:EMAIL,:SSD,:ROLE,:MESSAGE)");
        $this->db->bind(":NAME", $data['name']);
        $this->db->bind(":EMAIL", $data['email']);
        $this->db->bind(":SSD", $data['ssd']);
        $this->db->bind(":ROLE", $data['role']);
        $this->db->bind(":MESSAGE", $data['message']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function resetCode($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("UPDATE $table_name SET security_code = :CODE WHERE id = :ID");
        $this->db->bind(":CODE", $data['code']);
        $this->db->bind(":ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            return false;
    }
    public function editImageActivationPerson($data)
    {
        $this->db->query("SELECT * FROM activation_person WHERE user_id = :ID AND role = :ROLE");
        $this->db->bind(":ID", $data['id']);
        $this->db->bind(":ROLE", $data['type']);
        $this->db->execute();

        if ($this->db->rowCount() > 0) {

            $this->db->query("UPDATE activation_person SET images = :IMAGES , isActive = 0 WHERE user_id = :ID AND role = :ROLE");
            $this->db->bind(":IMAGES", $data['image']);
            $this->db->bind(":ROLE", $data['type']);
            $this->db->bind(":ID", $data['id']);
            $this->db->execute();
            if ($this->db->rowCount() >= 0)
                return true;
            else
                return false;
        } else {

            $this->db->query("INSERT INTO activation_person(images,user_id,role,isActive) values(:IMAGES,:ID,:ROLE,0)");
            $this->db->bind(":IMAGES", $data['image']);
            $this->db->bind(":ROLE", $data['type']);
            $this->db->bind(":ID", $data['id']);
            $this->db->execute();
            if ($this->db->rowCount() > 0)
                return true;
            else
                return false;
        }
    }
    public function getPlace($table_name, $id)  // Check User SSD
    {
        $this->db->query("SELECT * FROM $table_name WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            return false;
        }
    }
    public function editImageActivationPlace($data)
    {
        $this->db->query("SELECT * FROM activation_place WHERE place_id = :ID AND role = :ROLE");
        $this->db->bind(":ID", $data['id']);
        $this->db->bind(":ROLE", $data['type']);
        $this->db->execute();

        if ($this->db->rowCount() > 0) {

            $this->db->query("UPDATE activation_place SET license_img = :IMAGE , isActive = 0 WHERE place_id = :ID AND role = :ROLE");
            $this->db->bind(":IMAGE", $data['image']);
            $this->db->bind(":ROLE", $data['type']);
            $this->db->bind(":ID", $data['id']);
            $this->db->execute();
            if ($this->db->rowCount() >= 0)
                return true;
            else
                return false;
        } else {

            $this->db->query("INSERT INTO activation_place(license_img,place_id,role,isActive) values(:IMAGE,:ID,:ROLE,0)");
            $this->db->bind(":IMAGE", $data['image']);
            $this->db->bind(":ROLE", $data['type']);
            $this->db->bind(":ID", $data['id']);
            $this->db->execute();
            if ($this->db->rowCount() > 0)
                return true;
            else
                return false;
        }
    }
    public function getVideo($type)
    {
        $this->db->query("SELECT video FROM video WHERE  type = :TYPE");
        $this->db->bind(":TYPE", $type);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            return false;
        }
    }
    public function getSpecialist()
    {
        $this->db->query("SELECT name,ar_name FROM Specialist");
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            return false;
        }
    }
    public function numberPatient($id)
    {
        $this->db->query("SELECT prescript.id FROM prescript,patient WHERE patient.id = :ID AND prescript.patient_id = patient.id");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_pre = $this->db->rowCount();
        }

        $this->db->query("SELECT disease.id FROM disease,patient WHERE patient.id = :ID AND disease.patient_id = patient.id");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_dis = $this->db->rowCount();
        }

        $this->db->query("SELECT appointment.id FROM appointment,patient WHERE patient.id = :ID AND appointment.patient_id = patient.id");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_app = $this->db->rowCount();
        }

        $data = [
            "pre" => $data_pre,
            "dis" => $data_dis,
            "app" => $data_app
        ];

        return $data;
    }
    public function numberDoctor($id)
    {
        $this->db->query("SELECT clinic.id FROM clinic,doctor WHERE clinic.doctor_id = doctor.id AND doctor.id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_clinic = $this->db->rowCount();
        }

        $this->db->query("SELECT prescript.id FROM prescript,doctor WHERE prescript.doctor_id = doctor.id AND doctor.id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_prescript = $this->db->rowCount();
        }

        $this->db->query("SELECT appointment.id FROM appointment,clinic,doctor WHERE appointment.clinic_id = clinic.id AND clinic.doctor_id = doctor.id AND doctor.id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_appointment = $this->db->rowCount();
        }

        $data = [
            "clinic" => $data_clinic,
            "prescript" => $data_prescript,
            "appointment" => $data_appointment
        ];

        return $data;
    }
    public function numberAssistant($id)
    {
        $this->db->query("SELECT clinic.id FROM clinic,assistant WHERE clinic.assistant_id = assistant.id AND assistant.id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_clinic = $this->db->rowCount();
        }

        $this->db->query("SELECT appointment.id FROM appointment,clinic,assistant WHERE appointment.clinic_id = clinic.id AND clinic.assistant_id = assistant.id AND assistant.id = :ID AND appointment.appoint_date = :DATE");
        $this->db->bind(":ID", $id);
        $this->db->bind(":DATE", date('Y-m-d'));
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $today_appointment = $this->db->rowCount();
        }

        $this->db->query("SELECT appointment.id FROM appointment,clinic,assistant WHERE appointment.clinic_id = clinic.id AND clinic.assistant_id = assistant.id AND assistant.id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $all_appointment = $this->db->rowCount();
        }

        $data = [
            "clinic" => $data_clinic,
            "today_appointment" => $today_appointment,
            "all_appointment" => $all_appointment
        ];

        return $data;
    }
    public function numberPharmacist($id)
    {
        $this->db->query("SELECT pharmacy.id FROM pharmacy,pharmacist WHERE pharmacy.pharmacist_id = pharmacist.id AND pharmacist.id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_pharmacy = $this->db->rowCount();
        }

        $this->db->query("SELECT pharmacy_prescript.id FROM pharmacy_prescript,pharmacy,prescript,pharmacist WHERE pharmacy_prescript.prescript_id = prescript.id AND pharmacy_prescript.pharmacy_id = pharmacy.id AND pharmacy.pharmacist_id = pharmacist.id AND pharmacist.id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_prescript = $this->db->rowCount();
        }

        $this->db->query("SELECT pharmacy_order.id FROM pharmacy_order,pharmacy,prescript,pharmacist WHERE pharmacy_order.prescript_id = prescript.id AND pharmacy_order.pharmacy_id = pharmacy.id AND pharmacy.pharmacist_id = pharmacist.id AND pharmacist.id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $data_order = $this->db->rowCount();
        }

        $data = [
            "pharmacy" => $data_pharmacy,
            "prescript" => $data_prescript,
            "order" => $data_order
        ];

        return $data;
    }
}
