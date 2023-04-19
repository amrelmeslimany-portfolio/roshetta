import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { LockFilled, IdcardFilled } from '@ant-design/icons';
import { Select } from 'antd';

import { useGlobalContext } from '../../../context';
import images from '../../../images';
import './AuthLogin.scss';

const AuthLogin = () => {
  const { setAuthUser } = useGlobalContext();
  const [auth, setAuth] = useState('');

  const [email, setEmail] = useState('');
  const [role, setRole] = useState('');
  const [ssd, setSsd] = useState('');
  const [password, setPassword] = useState('');

  const navigate = useNavigate();

  const handleSubmit = (e) => {
    console.log({
      role,
      email,
      ssd,
      password,
    });
    e.preventDefault();
    fetch('http://localhost:80/roshetta/api/users/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        role,
        email,
        ssd,
        password,
      }),
    })
      .then((res) => res.json())
      .then((data) => console.log(data));
    // setRole('');
    // setEmail('');
    // setPassword('');
    // setSsd('');

    // navigate('/');
  };
  return (
    <>
      <div className="auth-login">
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
