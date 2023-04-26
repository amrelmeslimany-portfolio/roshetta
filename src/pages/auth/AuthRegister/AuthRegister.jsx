import React from 'react';
import { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { GiWeight } from 'react-icons/gi';
import { MdLocationOn } from 'react-icons/md';
import { TbArrowAutofitHeight } from 'react-icons/tb';
import { FaHandHoldingMedical } from 'react-icons/fa';
import {
  UserOutlined,
  MailFilled,
  LockFilled,
  PhoneFilled,
  IdcardFilled,
} from '@ant-design/icons';
import { DatePicker, Select, Alert } from 'antd';
import images from '../../../images';
import { useGlobalContext } from '../../../context';
import './AuthRegister.scss';

const AuthRegister = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();
  const [auth, setAuth] = useState('');

  const [role, setRole] = useState('');
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

  let formData = new FormData();

  const handleSubmit = (e) => {
    e.preventDefault();

    formData.append('role', role);
    formData.append('first_name', firstName);
    formData.append('last_name', lastName);
    formData.append('email', email);
    formData.append('governorate', governorate);
    formData.append('gender', gender);
    formData.append('ssd', ssd);
    formData.append('phone_number', phoneNumber);
    formData.append('birth_date', birthDate);
    formData.append('password', password);
    formData.append('confirm_password', confirmPassword);
    formData.append('weight', weight);
    formData.append('height', height);
    formData.append('specialist', specialist);
    if (password < 6 && confirmPassword < 6) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth',
      });
      setAlert({
        msg: 'ادخل باسوورد مكون من 6 ارقام او اكثر',
        show: true,
        type: 'error',
      });
    } else if (password !== confirmPassword) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth',
      });
      setAlert({
        msg: 'الباسوورد غير متشابة',
        show: true,
        type: 'error',
      });
    } else if (ssd < 14) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth',
      });
      setAlert({
        msg: 'الرقم القومي يجب ان يكون 14 رقم',
        show: true,
        type: 'error',
      });
    } else if (phoneNumber < 11) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth',
      });
      setAlert({
        msg: 'رقم الهاتف يجب ان يكون 11 رقم',
        show: true,
        type: 'error',
      });
    } else {
      fetch('http://localhost:80/roshetta/api/users/register', {
        method: 'POST',
        // headers: {
        //   'content-type': 'multipart/form-data',
        //   // 'Content-Type': 'application/json',
        // },
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          console.log(data);
          setAuth(data.Status);
          if (data.Status > 299) {
            window.scrollTo({
              top: 0,
              left: 0,
              behavior: 'smooth',
            });
            setAlert({
              msg: 'يوجد خطأ في تسجيل الحساب',
              show: true,
              type: 'error',
            });
          } else {
            localStorage.setItem('registerData', JSON.stringify([role, email]))
            navigate('/active-email');

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
          }
        });
    }
  };
  useEffect(() => {
    const myTimeout = setTimeout(() => {
      setAlert({ msg: '', show: false, type: '' });
    }, 3000);

    return () => {
      clearTimeout(myTimeout);
    };
  }, [alert.show]);

  return (
    <>
      <div className="auth-register">
        {alert.show && (
          <Alert
            style={{
              marginBottom: 20,
            }}
            message="حدثت مشكلة"
            description={alert.msg}
            type={alert.type}
            showIcon
          />
        )}
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
                  d
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
                      value: 'assistant',
                      label: 'مساعد',
                    },
                    {
                      value: 'pharmacist',
                      label: 'صيدلي',
                    },
                    {
                      value: 'admin',
                      label: 'ادمن',
                    },
                  ]}
                />
              </div>
              <div className="auth-register__form--form-input">
                <span>
                  <FaHandHoldingMedical />
                </span>
                <input
                  disabled={role === 'doctor' ? false : true}
                  name="specialist"
                  type="text"
                  placeholder="اختر تخصصك الطبي..."
                  value={specialist}
                  onChange={(e) => setSpecialist(e.target.value)}
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
                  type="password"
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
                  type="password"
                  placeholder="اعد ادخال كلمة المرور..."
                  value={confirmPassword}
                  onChange={(e) => setConfirmPassword(e.target.value)}
                />
              </div>
            </div>
          </div>
          <button
            className="text-white bg-roshetta text-2xl px-44 py-5 rounded-full hover:px-48 hover:py-6 transition-all "
            type="submit"
          >
            انشاء حساب
          </button>
        </form>
        <p className="auth-register__login-btn">
          لديك حساب بالفعل ؟ <Link to={'/login'}>اضغط هنا لتسجيل الدخول</Link>
        </p>
      </div>
      <p className="app__footer">
        برمجه فريق <span>روشتة</span> 2023
      </p>
    </>
  );
};

export default AuthRegister;
