import React from 'react';
import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { GiWeight } from 'react-icons/gi';
import { MdLocationOn } from 'react-icons/md';
import { TbArrowAutofitHeight } from 'react-icons/tb';
import {
  UserOutlined,
  MailFilled,
  LockFilled,
  PhoneFilled,
  IdcardFilled,
} from '@ant-design/icons';
import { DatePicker, Select } from 'antd';
import './AuthRegister.scss';
import images from '../../../images';

const AuthRegister = () => {
  const [role, setRole] = useState('النوع...');
  const [firstName, setFirstName] = useState('');
  const [lastName, setLastName] = useState('');
  const [email, setEmail] = useState('');
  const [governorate, setGovernorate] = useState('');
  const [gender, setGender] = useState('');
  const [ssd, setSsd] = useState('');
  const [phoneNumber, setPhoneNumber] = useState('');
  const [birthDate, setBirthDate] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [weight, setWeight] = useState('');
  const [height, setHeight] = useState('');
  const [specialist, setSpecialist] = useState('');

  const navigate = useNavigate();

  const handleSubmit = (e) => {
    console.log({
      type: role,
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
    });
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
    // setRole('');
    // setFirstName('');
    // setLastName('');
    // setEmail('');
    // setPassword('');
    // setConfirmPassword('');
    // setGovernorate('');
    // setGender('');
    // setSsd('');
    // setPhoneNumber('');
    // setBirthDate('');
    // setWeight('');
    // setHeight('');
    // setSpecialist('');
    // navigate('/');
  };

  const onDateChange = (date, dateString) => {
    console.log(date, dateString);
  };

  const handleChange = (value) => {
    console.log(`selected ${value}`);
  };

  return (
    <>
      <div className="auth-register">
        <div className="auth-register__img">
          <img src={images.logo1} alt="logo" />
        </div>
        <h2 className="auth-register__title">انشاء حساب جديد</h2>
        <p className="auth-register__text">
          يرجي مليء البيانات لانشاء حساب جديد
        </p>
        <form className="auth-register__form" onSubmit={handleSubmit}>
          <div className="auth-register__form--container">
            <div className="auth-register__form-right">
              <div className="auth-register__form--form-input">
                <span>
                  <UserOutlined />
                </span>
                <input
                  name="firstName"
                  type="text"
                  placeholder="ادخل الاسم الاول..."
                  value={firstName}
                  onChange={(e) => setFirstName(e.target.value)}
                />
              </div>
              <div className="auth-register__form--form-input">
                <span>
                  <UserOutlined />
                </span>
                <input
                  name="lastName"
                  type="text"
                  placeholder="ادخل الاسم الاخير..."
                  value={lastName}
                  onChange={(e) => setLastName(e.target.value)}
                />
              </div>
              <div className="auth-register__form--form-input">
                <span>
                  <MailFilled />
                </span>
                <input
                  name="email"
                  type="text"
                  placeholder="ادخل البريد الالكتروني..."
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                />
              </div>
              <div className="auth-register__form--form-input">
                <span>
                  <IdcardFilled />
                </span>
                <input
                  name="ssd"
                  type="text"
                  placeholder="ادخل الرقم القومي..."
                  value={ssd}
                  onChange={(e) => setSsd(e.target.value)}
                />
              </div>
              <div className="auth-register__form--form-input">
                <span>
                  <PhoneFilled />
                </span>
                <input
                  name="phoneNumber"
                  type="text"
                  placeholder="ادخل رقم التليفون..."
                  value={phoneNumber}
                  onChange={(e) => setPhoneNumber(e.target.value)}
                />
              </div>
              <div className="auth-register__form--form-custom-input">
                <DatePicker
                  size="large"
                  style={{
                    width: 450,
                    marginBottom: 15,
                  }}
                  onChange={(value, stringDate) => setBirthDate(stringDate)}
                />
              </div>
              <div className="auth-register__form--form-input">
                <span>
                  <MdLocationOn />
                </span>
                <input
                  name="governorate"
                  type="text"
                  placeholder="ادخل المحافظة..."
                  value={governorate}
                  onChange={(e) => setGovernorate(e.target.value)}
                />
              </div>
            </div>
            <div className="auth-register__form-left">
              <div className="auth-register__form--form-custom-input">
                <Select
                  placeholder="اختر النوع..."
                  style={{
                    width: 450,
                    marginTop: 5,
                    marginBottom: 35,
                  }}
                  onChange={(value) => setGender(value)}
                  options={[
                    {
                      value: 'male',
                      label: 'ذكر',
                    },
                    {
                      value: 'female',
                      label: 'انثى',
                    },
                  ]}
                />
              </div>
              <div className="auth-register__form--form-custom-input">
                <Select
                  placeholder="اختر نوع الحساب..."
                  style={{
                    width: 450,
                    marginTop: 5,
                    marginBottom: 35,
                  }}
                  onChange={(value) => setRole(value)}
                  options={[
                    {
                      value: 'patient',
                      label: 'مريض',
                    },
                    {
                      value: 'doctor',
                      label: 'دكتور',
                    },
                    {
                      value: 'admin',
                      label: 'ادمن',
                      disabled: true,
                    },
                  ]}
                />
              </div>
              <div className="auth-register__form--form-custom-input">
                <Select
                  placeholder="اختر تخصصك الطبي..."
                  style={{
                    width: 450,
                    marginTop: 5,
                    marginBottom: 35,
                  }}
                  onChange={(value) => setSpecialist(value)}
                  options={[
                    {
                      value: '',
                      label: 'سمك',
                    },
                    {
                      value: '',
                      label: 'لبن',
                    },
                    {
                      value: '',
                      label: 'تمر هندي',
                    },
                  ]}
                />
              </div>
              <div className="auth-register__form--form-input">
                <span>
                  <GiWeight />
                </span>
                <input
                  name="weight"
                  type="text"
                  placeholder="ادخل وزنك..."
                  value={weight}
                  onChange={(e) => setWeight(e.target.value)}
                />
              </div>
              <div className="auth-register__form--form-input">
                <span>
                  <TbArrowAutofitHeight />
                </span>
                <input
                  name="height"
                  type="text"
                  placeholder="ادخل ارتفاعك..."
                  value={height}
                  onChange={(e) => setHeight(e.target.value)}
                />
              </div>
              <div className="auth-register__form--form-input">
                <span>
                  <LockFilled />
                </span>
                <input
                  name="password"
                  type="text"
                  placeholder="ادخل كلمة المرور..."
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                />
              </div>
              <div className="auth-register__form--form-input">
                <span>
                  <LockFilled />
                </span>
                <input
                  name="confirmPassword"
                  type="text"
                  placeholder="اعد ادخال كلمة المرور..."
                  value={confirmPassword}
                  onChange={(e) => setConfirmPassword(e.target.value)}
                />
              </div>
            </div>
          </div>
          <button className="main__btn-fill" type="submit">
            انشاء حساب
          </button>
        </form>
        <p className="auth-register__login-btn">
          لديك حساب بالفعل ؟ <Link to={'/login'}>اضغط هنا لتسجيل الدخول</Link>
        </p>
        <p className="app__footer">
          برمجه فريق <span>روشتة</span> 2023
        </p>
      </div>
    </>
  );
};

export default AuthRegister;
