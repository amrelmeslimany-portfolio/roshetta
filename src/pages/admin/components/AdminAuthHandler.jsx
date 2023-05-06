import React from 'react';
import { useGlobalContext } from '../../../context';
import { Alert } from 'antd';
import { Navigate } from 'react-router-dom';

const AdminAuthHandler = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();

  let userData;
  if (JSON.parse(localStorage.getItem('userData'))) {
    userData = JSON.parse(localStorage.getItem('userData'));
  }

  if (!userData) {
    return <Navigate to="/login" />;
  }
  const time = Date.now();
  const str_time = time.toString();
  const result = Number(str_time.slice(0, 10));

  const timeTillExpired = userData.expiredToken - result;
  if (timeTillExpired <= 0) {
    window.scrollTo({
      top: 0,
      left: 0,
      behavior: 'smooth',
    });
    setAlert({
      msg: ' يجب ان تعيد تسجيل الدخول مره اخرى !انتهت الجلسة',
      show: true,
      type: 'warning',
    });

    localStorage.clear('userData');
    return <Navigate to="/login" />;
  }
  return <></>;
};

export default AdminAuthHandler;
