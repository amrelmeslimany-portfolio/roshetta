<?php

class Assistant
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    
    public function loginClinic($cl_id, $as_id)
    {
        $this->db->query("SELECT * FROM clinic WHERE assistant_id = :AS_ID AND id = :CL_ID");
        $this->db->bind(":AS_ID", $as_id);
        $this->db->bind(":CL_ID", $cl_id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }
    public function editClinic($data = [])
    {
        $this->db->query("UPDATE clinic SET phone_number = :PHONE_NUMBER,address = :ADDRESS,price = :PRICE,governorate = :GOVERNORATE,start_working = :START,end_working = :END  WHERE id = :ID AND assistant_id = :ASS_ID");
        $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
        $this->db->bind(":PRICE", $data['price']);
        $this->db->bind(":START", $data['start_working']);
        $this->db->bind(":END", $data['end_working']);
        $this->db->bind(":GOVERNORATE", $data['governorate']);
        $this->db->bind(":ADDRESS", $data['address']);
        $this->db->bind(":ID", $data['clinic_id']);
        $this->db->bind(":ASS_ID", $data['assistant_id']);
        $this->db->execute();
        if ($this->db->rowCount() > 0)
            return true;
        else
            false;
    }
    public function getClinicAss($as_id)
    {
        $this->db->query("SELECT id,logo,name,start_working,end_working,status FROM clinic WHERE assistant_id = :AS_ID");
        $this->db->bind(":AS_ID", $as_id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
}