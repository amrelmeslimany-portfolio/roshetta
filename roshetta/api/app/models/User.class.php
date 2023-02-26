<?php

class User
{

    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function getUserEmail($email, $table_name)  // Check User Email
    {
        $this->db->query("SELECT * FROM $table_name WHERE email = :EMAIL");
        $this->db->bind(":EMAIL", $email);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }
    public function getUserSSD($ssd, $table_name)  // Check User SSD
    {
        $this->db->query("SELECT * FROM $table_name WHERE ssd = :SSD");
        $this->db->bind(":SSD", $ssd);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }
    public function getUserPhone($phone, $table_name)  // Check User Phone
    {
        $this->db->query("SELECT * FROM $table_name WHERE phone_number = :PHONE_NUMBER");
        $this->db->bind(":PHONE_NUMBER", $phone);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }

    public function registerPatient($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("INSERT INTO $table_name (name,ssd,email,phone_number,gender,birth_date,weight,height,governorate,password,security_code,email_isActive,role) 
                                        VALUES (:NAME,:SSD,:EMAIL,:PHONE_NUMBER,:GENDER,:BIRTH_DATE,:WEIGHT,:HEIGHT,:GOVERNORATE,:PASSWORD,:SECURITY_CODE,0,:ROLE)");
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

        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }

    public function registerDoctor($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("INSERT INTO $table_name (name,ssd,email,phone_number,gender,birth_date,specialist,governorate,password,security_code,email_isActive,role) 
                                        VALUES (:NAME,:SSD,:EMAIL,:PHONE_NUMBER,:GENDER,:BIRTH_DATE,:SPECIALIST,:GOVERNORATE,:PASSWORD,:SECURITY_CODE,0,:ROLE)");
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

        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }
    public function registerOther($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("INSERT INTO $table_name (name,ssd,email,phone_number,gender,birth_date,governorate,password,security_code,email_isActive,role) 
                                        VALUES (:NAME,:SSD,:EMAIL,:PHONE_NUMBER,:GENDER,:BIRTH_DATE,:GOVERNORATE,:PASSWORD,:SECURITY_CODE,0,:ROLE)");
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

        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }
    public function login($data)
    {
        $table_name = $data['type'];
        $this->db->query("SELECT * FROM $table_name WHERE ssd = :USER_ID OR email = :USER_ID");
        $this->db->bind(":USER_ID", $data['user_id']);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }
    public function editToken($data = [])
    {
        $table_name = $data['type'];
        $this->db->query("UPDATE $table_name SET token = :TOKEN WHERE id = :ID");
        $this->db->bind(":TOKEN", $data['token']);
        $this->db->bind(":ID", $data['id']);
        $this->db->execute();
        if ($this->db->rowCount()) {
            return true;
        } else {
            false;
        }
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
            false;
        }
    }

    public function activeEmail($data = []){
        $table_name = $data['type'];
        $this->db->query("UPDATE $table_name SET email_isActive = 1 , security_code = :CODE WHERE email = :EMAIL");
        $this->db->bind(":CODE", $data['code']);
        $this->db->bind(":EMAIL", $data['email']);
        $this->db->execute();
        if ($this->db->rowCount()) {
            return true;
        } else {
            false;
        }
    }
}
