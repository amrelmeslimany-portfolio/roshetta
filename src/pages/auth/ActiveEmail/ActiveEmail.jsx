import { useState, useEffect } from 'react';
import './ActiveEmail.scss';
import { MdEmail } from 'react-icons/md';
import { Alert } from 'antd';
import { useGlobalContext } from '../../../context';
import { Navigate, useNavigate } from 'react-router-dom';
import { AppWrapper } from '../../../wrapper';
import { MyLoader } from '../../../components';

const ActiveEmail = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();

  const navigate = useNavigate();

  const [code, setCode] = useState('');
  const [role, setRole] = useState('');
  const [email, setEmail] = useState('');
  const [loading, setLoading] = useState(false);
  const registerData = JSON.parse(localStorage.getItem('registerData'));

  let formData = new FormData();

  const handleSubmit = (e) => {
    e.preventDefault();
    setLoading(true);
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
          if (data.Status > 299) {
            console.log(data);
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
            setLoading(false);
          } else {
            console.log(data);
            localStorage.setItem('message', JSON.stringify([data.Message]));
            setLoading(false);
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
  loading;
  if (loading) {
    return <MyLoader text={'جاري تفعيل الحساب...'} loading={loading} />;
  }
  return (
    <>
      <div className="mx-auto flex h-screen flex-col items-center justify-center gap-6">
        {alert.show && (
          <motion.div
            initial={{ opacity: 0, scale: 0.5 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.5 }}
          >
            <Alert
              style={{
                marginBottom: 20,
              }}
              message="حدثت مشكلة"
              description={alert.msg}
              type={alert.type}
              showIcon
            />
          </motion.div>
        )}
        <div className="text-8xl text-roshetta">
          <MdEmail />
        </div>
        <h2 className="text-2xl font-extrabold text-roshetta">
          تم ارسال كود الى الايميل الخاص بك
        </h2>
        <p className="mb-4 text-xl text-[#a7a7a7]">
          تم ارسال كود الي بريدك الالكتروني بنجاح
        </p>
        <p className="mb-4 text-xl text-[#a7a7a7]">
          قم باستخدام هذا الكود لتأكيد حسابك وتسجيل الدخول.
        </p>
        <form
          className="flex flex-col items-center justify-center gap-6"
          onSubmit={handleSubmit}
        >
          <input
            onChange={(e) => setCode(e.target.value)}
            value={code}
            className="h-14 w-96 rounded-2xl border-2 border-slate-300 px-2 py-1 text-2xl focus:outline-none "
            type="text"
            placeholder="ادخل الكود"
          />
          <button
            className="foucs:outline-2 mt-6 rounded-full bg-roshetta px-40 py-3 text-2xl text-white hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300  active:bg-green-600"
            type="submit"
          >
            تأكيد الحساب
          </button>
        </form>
      </div>
    </>
  );
};

export default AppWrapper(ActiveEmail);
