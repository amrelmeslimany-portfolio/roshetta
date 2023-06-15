import React, { useContext, useEffect, useState } from "react";
import { useGlobalContext } from "../../../context";
import { Alert, message } from "antd";
import { Navigate } from "react-router-dom";
import { AuthContext } from "../../../store/auth/context";
import { initalWindowScroll } from "../../../utils/reusedFunctions";

const AdminAuthHandler = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();
  const { user, logoutAction } = useContext(AuthContext);
  const { isExpired, setIsExpired } = useState(false);

  // let userData;
  // if (JSON.parse(localStorage.getItem('userData'))) {
  //   userData = JSON.parse(localStorage.getItem('userData'));
  // }

  // if (!userData) {
  //   return <Navigate to="/login" />;
  // }

  useEffect(() => {
    const time = Number(Date.now().toString().slice(0, 10));
    const timeTillExpired = user.expiredToken - time;

    if (timeTillExpired <= 0) {
      // NOTE this function for testing
      localStorage.clear("user");
      setIsExpired(true);
      initalWindowScroll();
      message.error(" يجب ان تعيد تسجيل الدخول مره اخرى !انتهت الجلسة");
    }
  }, [user]);

  if (isExpired) {
    // setAlert({
    //   msg: ' يجب ان تعيد تسجيل الدخول مره اخرى !انتهت الجلسة',
    //   show: true,
    //   type: 'warning',
    // });

    // localStorage.clear('userData');

    return <Navigate to="/login" />;
  }
  return null;
};

export default AdminAuthHandler;
