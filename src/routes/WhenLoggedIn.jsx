import { message } from "antd";
import { useEffect } from "react";
import { Navigate, Outlet } from "react-router-dom";

const WhenLoggedIn = ({ isAuthenticated }) => {
  useEffect(() => {
    if (isAuthenticated) message.info("انت مسجل الدخول بالفعل");
  }, [isAuthenticated]);

  return !isAuthenticated ? <Outlet /> : <Navigate to="/admin/dashboard" />;
};

export default WhenLoggedIn;
