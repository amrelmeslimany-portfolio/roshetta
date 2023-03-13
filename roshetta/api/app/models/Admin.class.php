<?php

class Admin
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function getActivationData($type, $status, $filter)
    {
        if (empty($filter)) {
            if (empty($type)) {
                $this->db->query("SELECT activation_person.id AS activation_id,doctor.id AS user_id,name,ssd,profile_img,doctor.role AS type,activation_person.isActive AS status FROM doctor,activation_person WHERE doctor.id = activation_person.user_id AND activation_person.isActive = :STATUS AND activation_person.role = 'doctor'");
                $this->db->bind(":STATUS", $status);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_doctor = $this->db->fetchAll();
                } else {
                    $data_doctor = null;
                }

                $this->db->query("SELECT activation_person.id AS activation_id,pharmacist.id AS user_id,name,ssd,profile_img,pharmacist.role AS type,activation_person.isActive AS status FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.user_id AND activation_person.isActive = :STATUS AND activation_person.role = 'pharmacist'");
                $this->db->bind(":STATUS", $status);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_pharmacist = $this->db->fetchAll();
                } else {
                    $data_pharmacist = null;
                }

                $this->db->query("SELECT activation_place.id AS activation_id,clinic.id AS place_id,name,ser_id,logo,activation_place.role AS type,activation_place.isActive AS status FROM clinic,activation_place WHERE clinic.id = activation_place.place_id AND activation_place.isActive = :STATUS AND activation_place.role = 'clinic'");
                $this->db->bind(":STATUS", $status);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_clinic = $this->db->fetchAll();
                } else {
                    $data_clinic = null;
                }

                $this->db->query("SELECT activation_place.id AS activation_id,pharmacy.id AS place_id,name,ser_id,logo,activation_place.role AS type,activation_place.isActive AS status FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.place_id AND activation_place.isActive = :STATUS AND activation_place.role = 'pharmacy'");
                $this->db->bind(":STATUS", $status);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_pharmacy = $this->db->fetchAll();
                } else {
                    $data_pharmacy = null;
                }
            } else {
                if ($type == 'doctor') {
                    $this->db->query("SELECT activation_person.id AS activation_id,doctor.id AS user_id,name,ssd,profile_img,doctor.role AS type,activation_person.isActive AS status FROM doctor,activation_person WHERE doctor.id = activation_person.user_id AND activation_person.isActive = :STATUS AND activation_person.role = 'doctor'");
                    $this->db->bind(":STATUS", $status);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_doctor = $this->db->fetchAll();
                    } else {
                        $data_doctor = null;
                    }
                } elseif ($type == 'pharmacist') {
                    $this->db->query("SELECT activation_person.id AS activation_id,pharmacist.id AS user_id,name,ssd,profile_img,pharmacist.role AS type,activation_person.isActive AS status FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.user_id AND activation_person.isActive = :STATUS AND activation_person.role = 'pharmacist'");
                    $this->db->bind(":STATUS", $status);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_pharmacist = $this->db->fetchAll();
                    } else {
                        $data_pharmacist = null;
                    }
                } elseif ($type == 'clinic') {
                    $this->db->query("SELECT activation_place.id AS activation_id,clinic.id AS place_id,name,ser_id,logo,activation_place.role AS type,activation_place.isActive AS status FROM clinic,activation_place WHERE clinic.id = activation_place.place_id AND activation_place.isActive = :STATUS AND activation_place.role = 'clinic'");
                    $this->db->bind(":STATUS", $status);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_clinic = $this->db->fetchAll();
                    } else {
                        $data_clinic = null;
                    }
                } else {
                    $this->db->query("SELECT activation_place.id AS activation_id,pharmacy.id AS place_id,name,ser_id,logo,activation_place.role AS type,activation_place.isActive AS status FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.place_id AND activation_place.isActive = :STATUS AND activation_place.role = 'pharmacy'");
                    $this->db->bind(":STATUS", $status);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_pharmacy = $this->db->fetchAll();
                    } else {
                        $data_pharmacy = null;
                    }
                }
            }
        } else {
            if (empty($type)) {
                $this->db->query("SELECT activation_person.id AS activation_id,doctor.id AS user_id,name,ssd,profile_img,doctor.role AS type,activation_person.isActive AS status FROM doctor,activation_person WHERE doctor.id = activation_person.user_id AND activation_person.isActive = :STATUS AND activation_person.role = 'doctor' AND (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                $this->db->bind(":STATUS", $status);
                $this->db->bind(":FILTER", $filter);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_doctor = $this->db->fetchAll();
                } else {
                    $data_doctor = null;
                }

                $this->db->query("SELECT activation_person.id AS activation_id,pharmacist.id AS user_id,name,ssd,profile_img,pharmacist.role AS type,activation_person.isActive AS status FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.user_id AND activation_person.isActive = :STATUS AND activation_person.role = 'pharmacist' AND (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                $this->db->bind(":STATUS", $status);
                $this->db->bind(":FILTER", $filter);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_pharmacist = $this->db->fetchAll();
                } else {
                    $data_pharmacist = null;
                }

                $this->db->query("SELECT activation_place.id AS activation_id,clinic.id AS place_id,name,ser_id,logo,activation_place.role AS type,activation_place.isActive AS status FROM clinic,activation_place WHERE clinic.id = activation_place.place_id AND activation_place.isActive = :STATUS AND activation_place.role = 'clinic' AND (ser_id = :FILTER XOR name = :FILTER XOR owner = :FILTER)");
                $this->db->bind(":STATUS", $status);
                $this->db->bind(":FILTER", $filter);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_clinic = $this->db->fetchAll();
                } else {
                    $data_clinic = null;
                }

                $this->db->query("SELECT activation_place.id AS activation_id,pharmacy.id AS place_id,name,ser_id,logo,activation_place.role AS type,activation_place.isActive AS status FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.place_id AND activation_place.isActive = :STATUS AND activation_place.role = 'pharmacy' AND (ser_id = :FILTER XOR name = :FILTER XOR owner = :FILTER)");
                $this->db->bind(":STATUS", $status);
                $this->db->bind(":FILTER", $filter);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_pharmacy = $this->db->fetchAll();
                } else {
                    $data_pharmacy = null;
                }
            } else {
                if ($type == 'doctor') {
                    $this->db->query("SELECT activation_person.id AS activation_id,doctor.id AS user_id,name,ssd,profile_img,doctor.role AS type,activation_person.isActive AS status FROM doctor,activation_person WHERE doctor.id = activation_person.user_id AND activation_person.isActive = :STATUS AND activation_person.role = 'doctor' AND (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                    $this->db->bind(":STATUS", $status);
                    $this->db->bind(":FILTER", $filter);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_doctor = $this->db->fetchAll();
                    } else {
                        $data_doctor = null;
                    }
                } elseif ($type == 'pharmacist') {
                    $this->db->query("SELECT activation_person.id AS activation_id,pharmacist.id AS user_id,name,ssd,profile_img,pharmacist.role AS type,activation_person.isActive AS status FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.user_id AND activation_person.isActive = :STATUS AND activation_person.role = 'pharmacist' AND (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                    $this->db->bind(":STATUS", $status);
                    $this->db->bind(":FILTER", $filter);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_pharmacist = $this->db->fetchAll();
                    } else {
                        $data_pharmacist = null;
                    }
                } elseif ($type == 'clinic') {
                    $this->db->query("SELECT activation_place.id AS activation_id,clinic.id AS place_id,name,ser_id,logo,activation_place.role AS type,activation_place.isActive AS status FROM clinic,activation_place WHERE clinic.id = activation_place.place_id AND activation_place.isActive = :STATUS AND activation_place.role = 'clinic' AND (ser_id = :FILTER XOR name = :FILTER XOR owner = :FILTER)");
                    $this->db->bind(":STATUS", $status);
                    $this->db->bind(":FILTER", $filter);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_clinic = $this->db->fetchAll();
                    } else {
                        $data_clinic = null;
                    }
                } else {
                    $this->db->query("SELECT activation_place.id AS activation_id,pharmacy.id AS place_id,name,ser_id,logo,activation_place.role AS type,activation_place.isActive AS status FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.place_id AND activation_place.isActive = :STATUS AND activation_place.role = 'pharmacy' AND (ser_id = :FILTER XOR name = :FILTER XOR owner = :FILTER)");
                    $this->db->bind(":STATUS", $status);
                    $this->db->bind(":FILTER", $filter);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_pharmacy = $this->db->fetchAll();
                    } else {
                        $data_pharmacy = null;
                    }
                }
            }
        }

        $data = [
            "doctor" => $data_doctor,
            "pharmacist" => $data_pharmacist,
            "clinic" => $data_clinic,
            "pharmacy" => $data_pharmacy
        ];

        return $data;
    }
    public function editStatus($type, $role, $id, $status)
    {
        $this->db->query("UPDATE $type SET isActive = :STATUS WHERE id = :ID AND role = :ROLE");
        $this->db->bind(":ID", $id);
        $this->db->bind(":STATUS", $status);
        $this->db->bind(":ROLE", $role);
        $this->db->execute();
        if ($this->db->rowCount() > 0)
            return true;
        else
            false;
    }
    public function editVideo($data)
    {
        $this->db->query("SELECT * FROM video WHERE type = :TYPE");
        $this->db->bind(":TYPE", $data['type']);
        $this->db->execute();

        if ($this->db->rowCount() > 0) {

            $this->db->query("UPDATE video SET video = :VIDEO  WHERE type = :TYPE");
            $this->db->bind(":VIDEO", $data['video']);
            $this->db->bind(":TYPE", $data['type']);
            $this->db->execute();
            if ($this->db->rowCount() > 0)
                return true;
            else
                false;
        } else {

            $this->db->query("INSERT INTO video(video,type) values(:VIDEO,:TYPE)");
            $this->db->bind(":VIDEO", $data['video']);
            $this->db->bind(":TYPE", $data['type']);
            $this->db->execute();
            if ($this->db->rowCount() > 0)
                return true;
            else
                false;
        }
    }
    public function getVideoUser()
    {
        $this->db->query("SELECT video,type FROM video");
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function getVideoByType($type)
    {
        $this->db->query("SELECT * FROM video WHERE type = :TYPE");
        $this->db->bind(":TYPE", $type);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            $data = $this->db->fetchObject();
            return $data;
        } else {
            false;
        }
    }
    public function getDataUser($type, $filter)
    {
        if (empty($filter)) {
            if (empty($type)) {
                $this->db->query("SELECT id,name,ssd,profile_img,role FROM doctor");
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_doctor = $this->db->fetchAll();
                } else {
                    $data_doctor = null;
                }

                $this->db->query("SELECT id,name,ssd,profile_img,role FROM pharmacist");
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_pharmacist = $this->db->fetchAll();
                } else {
                    $data_pharmacist = null;
                }

                $this->db->query("SELECT id,name,ssd,profile_img,role FROM assistant");
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_assistant = $this->db->fetchAll();
                } else {
                    $data_assistant = null;
                }

                $this->db->query("SELECT id,name,ssd,profile_img,role FROM patient");
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_patient = $this->db->fetchAll();
                } else {
                    $data_patient = null;
                }

                $this->db->query("SELECT id,name,ser_id,logo FROM clinic");
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_clinic = $this->db->fetchAll();
                } else {
                    $data_clinic = null;
                }

                $this->db->query("SELECT id,name,ser_id,logo FROM pharmacy");
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_pharmacy = $this->db->fetchAll();
                } else {
                    $data_pharmacy = null;
                }
            } else {
                if ($type == 'doctor') {
                    $this->db->query("SELECT id,name,ssd,profile_img,role FROM doctor");
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_doctor = $this->db->fetchAll();
                    } else {
                        $data_doctor = null;
                    }
                } elseif ($type == 'pharmacist') {
                    $this->db->query("SELECT id,name,ssd,profile_img,role FROM pharmacist");
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_pharmacist = $this->db->fetchAll();
                    } else {
                        $data_pharmacist = null;
                    }
                } elseif ($type == 'patient') {
                    $this->db->query("SELECT id,name,ssd,profile_img,role FROM patient");
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_patient = $this->db->fetchAll();
                    } else {
                        $data_patient = null;
                    }
                } elseif ($type == 'assistant') {
                    $this->db->query("SELECT id,name,ssd,profile_img,role FROM assistant");
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_assistant = $this->db->fetchAll();
                    } else {
                        $data_assistant = null;
                    }
                } elseif ($type == 'clinic') {
                    $this->db->query("SELECT id,name,ser_id,logo FROM clinic");
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_clinic = $this->db->fetchAll();
                    } else {
                        $data_clinic = null;
                    }
                } else {
                    $this->db->query("SELECT id,name,ser_id,logo FROM pharmacy");
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_pharmacy = $this->db->fetchAll();
                    } else {
                        $data_pharmacy = null;
                    }
                }
            }
        } else {
            if (empty($type)) {
                $this->db->query("SELECT id,name,ssd,profile_img,role FROM doctor WHERE (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                $this->db->bind(":FILTER", $filter);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_doctor = $this->db->fetchAll();
                } else {
                    $data_doctor = null;
                }

                $this->db->query("SELECT id,name,ssd,profile_img,role FROM pharmacist WHERE (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                $this->db->bind(":FILTER", $filter);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_pharmacist = $this->db->fetchAll();
                } else {
                    $data_pharmacist = null;
                }

                $this->db->query("SELECT id,name,ssd,profile_img,role FROM patient WHERE (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                $this->db->bind(":FILTER", $filter);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_patient = $this->db->fetchAll();
                } else {
                    $data_patient = null;
                }

                $this->db->query("SELECT id,name,ssd,profile_img,role FROM assistant WHERE (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                $this->db->bind(":FILTER", $filter);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_assistant = $this->db->fetchAll();
                } else {
                    $data_assistant = null;
                }

                $this->db->query("SELECT id,name,ser_id,logo FROM clinic WHERE (ser_id = :FILTER XOR name = :FILTER XOR owner = :FILTER)");
                $this->db->bind(":FILTER", $filter);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_clinic = $this->db->fetchAll();
                } else {
                    $data_clinic = null;
                }

                $this->db->query("SELECT id,name,ser_id,logo FROM clinic WHERE (ser_id = :FILTER XOR name = :FILTER XOR owner = :FILTER)");
                $this->db->bind(":FILTER", $filter);
                $this->db->execute();
                if ($this->db->rowCount() > 0) {
                    $data_pharmacy = $this->db->fetchAll();
                } else {
                    $data_pharmacy = null;
                }
            } else {
                if ($type == 'doctor') {
                    $this->db->query("SELECT id,name,ssd,profile_img,role FROM doctor WHERE (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                    $this->db->bind(":FILTER", $filter);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_doctor = $this->db->fetchAll();
                    } else {
                        $data_doctor = null;
                    }
                } elseif ($type == 'pharmacist') {
                    $this->db->query("SELECT id,name,ssd,profile_img,role FROM pharmacist WHERE (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                    $this->db->bind(":FILTER", $filter);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_pharmacist = $this->db->fetchAll();
                    } else {
                        $data_pharmacist = null;
                    }
                } elseif ($type == 'patient') {
                    $this->db->query("SELECT id,name,ssd,profile_img,role FROM patient WHERE (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                    $this->db->bind(":FILTER", $filter);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_patient = $this->db->fetchAll();
                    } else {
                        $data_patient = null;
                    }
                } elseif ($type == 'assistant') {
                    $this->db->query("SELECT id,name,ssd,profile_img,role FROM assistant WHERE (ssd = :FILTER XOR name = :FILTER XOR email = :FILTER)");
                    $this->db->bind(":FILTER", $filter);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_assistant = $this->db->fetchAll();
                    } else {
                        $data_assistant = null;
                    }
                } elseif ($type == 'clinic') {
                    $this->db->query("SELECT id,name,ser_id,logo FROM clinic WHERE (ser_id = :FILTER XOR name = :FILTER XOR owner = :FILTER)");
                    $this->db->bind(":FILTER", $filter);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_clinic = $this->db->fetchAll();
                    } else {
                        $data_clinic = null;
                    }
                } else {
                    $this->db->query("SELECT id,name,ser_id,logo FROM pharmacy WHERE (ser_id = :FILTER XOR name = :FILTER XOR owner = :FILTER)");
                    $this->db->bind(":FILTER", $filter);
                    $this->db->execute();
                    if ($this->db->rowCount() > 0) {
                        $data_pharmacy = $this->db->fetchAll();
                    } else {
                        $data_pharmacy = null;
                    }
                }
            }
        }

        $data = [
            "doctor" => $data_doctor,
            "pharmacist" => $data_pharmacist,
            "patient" => $data_patient,
            "assistant" => $data_assistant,
            "clinic" => $data_clinic,
            "pharmacy" => $data_pharmacy
        ];

        return $data;
    }
    public function getDetailsPlace($type, $id)
    {
        $this->db->query("SELECT * FROM $type WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount()) {
            $data = $this->db->fetchAll();
            return $data;
        } else {
            false;
        }
    }
    public function deleteUserPlace($type, $id)
    {
        $this->db->query("DELETE FROM $type WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }
    public function getMessageUser($type, $status)
    {
        if (empty($type)) {
            $this->db->query("SELECT name,email,message,role FROM message WHERE m_case = :STATUS ORDER BY time ASC");
            $this->db->bind(":STATUS", $status);
            $this->db->execute();
            if ($this->db->rowCount()) {
                $data = $this->db->fetchAll();
                return $data;
            } else {
                false;
            }
        } else {
            $this->db->query("SELECT name,email,message,role FROM message WHERE m_case = :STATUS AND role = :TYPE ORDER BY time ASC");
            $this->db->bind(":STATUS", $status);
            $this->db->bind(":TYPE", $type);
            $this->db->execute();
            if ($this->db->rowCount()) {
                $data = $this->db->fetchAll();
                return $data;
            } else {
                false;
            }
        }
    }
    public function replyMessageUser($id)
    {
        $this->db->query("UPDATE message SET m_case = 1 WHERE id = :ID");
        $this->db->bind(":ID", $id);
        $this->db->execute();
        if ($this->db->rowCount())
            return true;
        else
            false;
    }
    public function getNumberAll()
    {
        $this->db->query("SELECT * FROM admin");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_admin = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM doctor");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_doctor = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM pharmacist");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_pharmacist = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM patient");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_patient = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM assistant");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_assistant = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM clinic");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_clinic = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM pharmacy");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_pharmacy = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM admin WHERE token != 'Null'");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_admin_now = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM doctor WHERE token != 'Null'");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_doctor_now = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM pharmacist WHERE token != 'Null'");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_pharmacist_now = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM patient WHERE token != 'Null'");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_patient_now = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM assistant WHERE token != 'Null'");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_assistant_now = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM clinic WHERE status = 1");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_clinic_now = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM pharmacy WHERE status = 1");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_pharmacy_now = $this->db->rowCount();
        }

        $this->db->query("SELECT * FROM prescript");
        $this->db->execute();
        if ($this->db->rowCount() >= 0) {
            $num_prescript = $this->db->rowCount();
        }

        $data = [
            "admin" => ["all" => $num_admin, "active_now" => $num_admin_now],
            "doctor" => ["all" => $num_doctor, "active_now" => $num_doctor_now],
            "pharmacist" => ["all" => $num_pharmacist, "active_now" => $num_pharmacist_now],
            "patient" => ["all" => $num_patient, "active_now" => $num_patient_now],
            "assistant" => ["all" => $num_assistant, "active_now" => $num_assistant_now],
            "clinic" => ["all" => $num_clinic, "active_now" => $num_clinic_now],
            "pharmacy" => ["all" => $num_pharmacy, "active_now" => $num_pharmacy_now],
            "prescript" => $num_prescript
        ];

        return $data;
    }
    public function updateEmailSSD($data)
    {
        $table_name = $data['role'];
        $type = $data['type'];

        if ($type == 'email') {
            $this->db->query("UPDATE $table_name SET email_isActive = 0 WHERE id = :ID");
            $this->db->bind(":ID", $data['id']);
            $this->db->execute();
            if ($this->db->rowCount() >= 0) {
                //********** */
            }
        }

        $this->db->query("UPDATE $table_name SET $type = :DATA WHERE id = :ID");
        $this->db->bind(":ID", $data['id']);
        $this->db->bind(":DATA", $data['data']);
        $this->db->execute();
        if ($this->db->rowCount()) {
            return true;
        } else {
            false;
        }
    }
    public function editUserProfile($data)
    {
        if ($data['type'] == 'patient') {

            $this->db->query("UPDATE patient SET phone_number = :PHONE_NUMBER , weight = :WEIGHT , height = :HEIGHT , governorate = :GOVERNORATE , name = :NAME , gender = :GENDER , birth_date = :DATE WHERE id = :ID");
            $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
            $this->db->bind(":WEIGHT", $data['weight']);
            $this->db->bind(":HEIGHT", $data['height']);
            $this->db->bind(":GOVERNORATE", $data['governorate']);
            $this->db->bind(":ID", $data['id']);
            $this->db->bind(":NAME", $data['name']);
            $this->db->bind(":GENDER", $data['gender']);
            $this->db->bind(":DATE", $data['birth_date']);
            $this->db->execute();
            if ($this->db->rowCount())
                return true;
            else
                false;
        } elseif ($data['type'] == 'doctor') {

            $this->db->query("UPDATE doctor SET phone_number = :PHONE , governorate = :GOVERNORATE , name = :NAME , gender = :GENDER , birth_date = :DATE , specialist = :SPECIALIST WHERE id = :ID");
            $this->db->bind(":PHONE", $data['phone_number']);
            $this->db->bind(":GOVERNORATE", $data['governorate']);
            $this->db->bind(":ID", $data['id']);
            $this->db->bind(":NAME", $data['name']);
            $this->db->bind(":GENDER", $data['gender']);
            $this->db->bind(":DATE", $data['birth_date']);
            $this->db->bind(":SPECIALIST", $data['specialist']);
            $this->db->execute();
            if ($this->db->rowCount())
                return true;
            else
                false;
        } else {

            $table_name = $data['type'];
            $this->db->query("UPDATE $table_name SET phone_number = :PHONE , governorate = :GOVERNORATE , name = :NAME , gender = :GENDER , birth_date = :DATE WHERE id = :ID");
            $this->db->bind(":PHONE", $data['phone_number']);
            $this->db->bind(":GOVERNORATE", $data['governorate']);
            $this->db->bind(":ID", $data['id']);
            $this->db->bind(":NAME", $data['name']);
            $this->db->bind(":GENDER", $data['gender']);
            $this->db->bind(":DATE", $data['birth_date']);
            $this->db->execute();
            if ($this->db->rowCount())
                return true;
            else
                false;
        }
    }
    public function editPlaceProfile($data = [])
    {
        if ($data['type'] == 'clinic') {
            $this->db->query("UPDATE clinic SET phone_number = :PHONE_NUMBER,address = :ADDRESS,price = :PRICE,governorate = :GOVERNORATE,start_working = :START,end_working = :END ,name = :NAME , owner = :OWNER , specialist = :SPECIALIST  WHERE id = :ID");
            $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
            $this->db->bind(":PRICE", $data['price']);
            $this->db->bind(":START", $data['start_working']);
            $this->db->bind(":END", $data['end_working']);
            $this->db->bind(":GOVERNORATE", $data['governorate']);
            $this->db->bind(":ADDRESS", $data['address']);
            $this->db->bind(":ID", $data['id']);
            $this->db->bind(":NAME", $data['name']);
            $this->db->bind(":OWNER", $data['owner']);
            $this->db->bind(":SPECIALIST", $data['specialist']);
            $this->db->execute();
            if ($this->db->rowCount() > 0)
                return true;
            else
                false;
        } else {
            $this->db->query("UPDATE pharmacy SET phone_number = :PHONE_NUMBER,address = :ADDRESS,governorate = :GOVERNORATE,start_working = :START,end_working = :END ,name = :NAME , owner = :OWNER  WHERE id = :ID");
            $this->db->bind(":PHONE_NUMBER", $data['phone_number']);
            $this->db->bind(":START", $data['start_working']);
            $this->db->bind(":END", $data['end_working']);
            $this->db->bind(":GOVERNORATE", $data['governorate']);
            $this->db->bind(":ADDRESS", $data['address']);
            $this->db->bind(":ID", $data['id']);
            $this->db->bind(":NAME", $data['name']);
            $this->db->bind(":OWNER", $data['owner']);
            $this->db->execute();
            if ($this->db->rowCount() > 0)
                return true;
            else
                false;
        }
    }
}
