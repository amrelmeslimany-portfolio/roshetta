import React, { useEffect, useState } from "react";
import { Navigate } from "react-router-dom";
import { useGlobalContext } from "../context";
const ProtectedRoute = ({ children }) => {
  const [token, setToken] = useState(null);
  const getToken = () => {
    let tokenData;
    if (JSON.parse(localStorage.getItem("userData"))) {
      tokenData = JSON.parse(localStorage.getItem("userData"));
    }
    setToken(tokenData.token);
  };
  useEffect(() => {
    getToken();
  }, [token]);

  const { auth } = useGlobalContext();
  auth;
  console.log("Protected router");
  console.log(auth);

  if (!token) {
    return <Navigate to="/login" />;
  }
  return children;
};

export default ProtectedRoute;
