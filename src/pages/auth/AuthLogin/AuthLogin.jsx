import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { LockFilled, IdcardFilled } from '@ant-design/icons';
import { Alert, Select } from 'antd';

import { useGlobalContext } from '../../../context';
import images from '../../../images';
import './AuthLogin.scss';

const AuthLogin = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();
  const [auth, setAuth] = useState('');

  const [email, setEmail] = useState('');
  const [role, setRole] = useState('');
  const [ssd, setSsd] = useState('');
  const [password, setPassword] = useState('');

  const navigate = useNavigate();

  let formData = new FormData();

  const handleSubmit = (e) => {
    e.preventDefault();
    ({
      role,
      email,
      ssd,
      password,
    });

    formData.append('role', role);
    formData.append('email', email);
    formData.append('ssd', ssd);
    formData.append('password', password);

    if (ssd < 14 && password < 6 && role === null) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth',
      });
      setAlert({
        msg: ' الرقم القومي يجب ان يكون 14 رقم والباسوورد غير خالي',
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
    } else if (password < 6) {
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
    } else if (!role) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth',
      });
      setAlert({
        msg: 'ادخل نوع الحساب لو سمحت',
        show: true,
        type: 'error',
      });
    }

    fetch('http://localhost:80/roshetta/api/users/login', {
      method: 'POST',
      // headers: {
      //   'Content-Type': 'application/json',
      // },
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        console.log(data)
        if (data.Status > 299) {
          window.scrollTo({
            top: 0,
            left: 0,
            behavior: 'smooth',
          });
          setAlert({
            msg: 'يوجد خطأ في تسجيل الدخول',
            show: true,
            type: 'error',
          });
        } else {
          setRole('');
          setEmail('');
          setPassword('');
          setSsd('');

          navigate('/admin/dashboard');
        }
      });
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
      <div className="auth-login">
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
        <div className="auth-login__img">
          <img src={images.logo1} alt="logo" />
        </div>
        <h2 className="auth-login__title">تسجيل الدخول</h2>
        <p className="auth-login__text">يرجي مليء البيانات لتسجيل الدخول</p>
        <form className="auth-login__form" onSubmit={handleSubmit}>
          <div className="auth-login__form--form-custom-input">
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
          <div className="auth-login__form--form-input">
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

          <div className="auth-login__form--form-input">
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
          <button className="login-btn main__btn-fill" type="submit">
            انشاء حساب
          </button>
          <p className="auth-login__register-btn">
            <Link to={'/forget-password'}>هل نسيت كلمه المرور ؟</Link>
          </p>
        </form>
        <div className="auth-login__no-account">
          <p>ليس لديك حساب ؟ </p>
          <Link to={'/register'}>اضغط هنا لانشاء حساب</Link>
        </div>
      </div>
      <p className="app__footer">
        برمجه فريق <span>روشتة</span> 2023
      </p>
    </>
  );
};

export default AuthLogin;
