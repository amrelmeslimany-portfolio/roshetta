import { IdcardFilled } from '@ant-design/icons';
import { Select, Alert } from 'antd';
import React, { useState, useEffect } from 'react';
import images from '../../../images';
import { AiOutlineArrowRight } from 'react-icons/ai';
import { BiMessageAltCheck } from 'react-icons/bi';
import { useGlobalContext } from '../../../context';
import { Link, useNavigate } from 'react-router-dom';
import './ForgetPassword.scss';
import { AppWrapper } from '../../../wrapper';

const ForgetPassword = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();
  const [auth, setAuth] = useState('');
  const [doneForget, setDoneForget] = useState(false);
  const [role, setRole] = useState('');
  const [ssd, setSsd] = useState('');

  const navigate = useNavigate();

  let formData = new FormData();

  const handleSubmit = (e) => {
    e.preventDefault();

    formData.append('role', role);
    formData.append('user_id', ssd);

    if (!role) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth',
      });
      setAlert({
        msg: 'ادخل نوع الحساب',
        show: true,
        type: 'error',
      });
    } else if (!ssd) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth',
      });
      setAlert({
        msg: 'ادخل الرقم القومي مكون من 14 رقم',
        show: true,
        type: 'error',
      });
    } else {
      fetch('http://localhost:80/roshetta/api/users/forget_password', {
        method: 'POST',
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          console.log(data);
          setAuth(data.Status);
          if (data.Status < 299) {
            window.scrollTo({
              top: 0,
              left: 0,
              behavior: 'smooth',
            });
            setAlert({
              msg: data.Message,
              show: true,
              type: 'error',
            });
          } else {
            setDoneForget(true);
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

  if (doneForget) {
    return (
      <>
        <div className="h-screen mx-auto flex-col gap-6 flex justify-center items-center">
          <div className="text-8xl text-roshetta">
            <BiMessageAltCheck />
          </div>
          <h2 className="text-2xl font-extrabold text-roshetta">
            تم الارسال بنجاح
          </h2>
          <p className="text-xl text-[#a7a7a7] mb-4">
            تم ارسال كود الي بريدك الالكتروني بنجاح
          </p>
          <p className="text-xl text-[#a7a7a7] mb-4">
            قم باستخدام هذا الكود ك كلمة المرور الخاصه بحسابك.
          </p>
          <Link className='bg-roshetta text-white px-6 py-2 rounded-full hover:text-lg transition-all' to={'/login'}>الرجوع لصفحة تسجيل الدخول</Link>
        </div>
      </>
    );
  }

  return (
    <>
      <div className="auth-forget">
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
        {/* <div className="auth-forget__icon">
          <AiOutlineArrowRight />
        </div> */}
        <div className="auth-forget__content">
          <img src={images.logo1} alt="" />
          <h2>نسيت كلمة المرور؟</h2>
          <p>قم بإدخال البريد او الرقم القومي</p>
          <form onSubmit={handleSubmit}>
            <div className="auth-forget__form--form-custom-input">
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
            <div className="auth-forget__form--form-input">
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
            <button className="" type="submit">
              ارسال
            </button>
          </form>
          <p className="auth-forget__login-btn">
            <Link
              className="text-roshetta text-lg hover:text-gray-400 transition-all"
              to={'/login'}
            >
              الرجوع لصفحة تسجيل الدخول
            </Link>
          </p>
        </div>
      </div>
      <p className="app__footer">
        برمجه فريق <span>روشتة</span> 2023
      </p>
    </>
  );
};

export default AppWrapper(ForgetPassword);
