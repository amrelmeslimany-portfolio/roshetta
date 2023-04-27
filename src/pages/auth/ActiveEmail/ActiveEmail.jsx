import { useState, useEffect } from 'react';
import './ActiveEmail.scss';
import { MdEmail } from 'react-icons/md';
import { Alert } from 'antd';
import { useGlobalContext } from '../../../context';
import { useNavigate } from 'react-router-dom';
import { AppWrapper } from '../../../wrapper';

const ActiveEmail = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();

  const navigate = useNavigate();

  const [code, setCode] = useState('');
  const [role, setRole] = useState('');
  const [email, setEmail] = useState('');

  const registerData = JSON.parse(localStorage.getItem('registerData'));

  let formData = new FormData();

  const handleSubmit = (e) => {
    e.preventDefault();

    console.log(registerData);
    setRole(registerData[0]);
    setEmail(registerData[1]);
    formData.append('role', registerData[0]);
    formData.append('email', registerData[1]);
    formData.append('code', code);
    if (!code) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth',
      });
      setAlert({
        msg: 'الرجاء ادخال الكود',
        show: true,
        type: 'error',
      });
    } else {
      fetch('http://localhost:80/roshetta/api/users/active_email', {
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
              msg: 'يوجد خطأ في تفعيل الحساب',
              show: true,
              type: 'error',
            });
          } else {
            console.log(data.Message);
            localStorage.setItem('message', JSON.stringify([data.Message]));
            navigate('/login');
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
      <div className="h-screen mx-auto flex-col gap-6 flex justify-center items-center">
        <div className="text-8xl text-roshetta">
          <MdEmail />
        </div>
        <h2 className="text-2xl font-extrabold text-roshetta">
          تم ارسال كود الى الايميل الخاص بك
        </h2>
        <p className="text-xl text-[#a7a7a7] mb-4">
          تم ارسال كود الي بريدك الالكتروني بنجاح
        </p>
        <p className="text-xl text-[#a7a7a7] mb-4">
          قم باستخدام هذا الكود لتأكيد حسابك وتسجيل الدخول.
        </p>
        <form
          className="flex flex-col justify-center items-center gap-6"
          onSubmit={handleSubmit}
        >
          <input
            onChange={(e) => setCode(e.target.value)}
            value={code}
            className="px-2 py-1 border-2 border-slate-300 focus:outline-none rounded-2xl w-96 h-14 text-2xl "
            type="text"
            placeholder="ادخل الكود"
          />
          <button
            type="submit"
            className="bg-roshetta text-white px-6 py-2 rounded-full hover:text-lg transition-all"
          >
            تأكيد الحساب
          </button>
        </form>
      </div>
    </>
  );
};

export default AppWrapper(ActiveEmail);
