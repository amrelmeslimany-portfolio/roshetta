import React from 'react';
import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { UserOutlined } from '@ant-design/icons';
import { Row, Col, Divider } from 'antd';
import './AuthRegister.scss';

const AuthRegister = () => {
  const [role, setRole] = useState('');
  const [firstName, setFirstName] = useState('');
  const [lastName, setLastName] = useState('');
  const [email, setEmail] = useState('');
  const [governorate, setGovernorate] = useState('');
  const [gender, setGender] = useState('');
  const [ssd, setSsd] = useState(0);
  const [phoneNumber, setPhoneNumber] = useState('');
  const [birthDate, setBirthDate] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [weight, setWeight] = useState(0);
  const [height, setHeight] = useState(0);
  const [specialist, setSpecialist] = useState('');

  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();
    fetch('http://localhost:80/roshetta/api/users/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        role,
        first_name: firstName,
        last_name: lastName,
        email,
        governorate,
        gender,
        ssd,
        phone_number: phoneNumber,
        birth_date: birthDate,
        password,
        confirm_password: confirmPassword,
        weight,
        height,
        specialist,
      }),
    })
      .then((res) => res.json())
      .then((data) => console.log(data));
    setFirstName('');
    setLastName('');
    setEmail('');
    setPassword('');
    setConfirmPassword('');
    navigate('/');
  };
  return (
    <>
      <form onSubmit={handleSubmit} className="app__AuthRegister--form">
        <div className="app__AuthRegister--form-name">
          <label htmlFor="">
            اختر النوع
            <span>
              <UserOutlined />
            </span>
            <input
              name="role"
              type="text"
              placeholder=""
              value={firstName}
              onChange={(e) => setFirstName(e.target.value)}
            />
          </label>
        </div>
        <button type="submit" className="submit-btn2">
          Sign Up{' '}
        </button>
      </form>
      <Row gutter={[24, 16]}>
        <Col span={12}><Divider>Text</Divider></Col>
        <Col span={12}><Divider>Text</Divider></Col>
        <Col span={12}><Divider>Text</Divider></Col>
        <Col span={12}><Divider>Text</Divider></Col>
      </Row>
      <Row gutter={[24, 16]}>
        <Col span={12} />
        <Col span={12} />
      </Row>
    </>
  );
};

export default AuthRegister;
