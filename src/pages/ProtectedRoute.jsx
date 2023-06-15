import React, { useContext, useEffect, useState } from "react";
import { Navigate } from "react-router-dom";
import { useGlobalContext } from "../context";
import { AuthContext } from "../store/auth/context";
const ProtectedRoute = ({ children }) => {
  const { user } = useContext(AuthContext);
  // const [token, setToken] = useState(null);
  // const getToken = () => {
  //   let tokenData;
  //   if (JSON.parse(localStorage.getItem("userData"))) {
  //     tokenData = JSON.parse(localStorage.getItem("userData"));
  //   }
  //   setToken(tokenData.token);
  // };
  // useEffect(() => {
  //   getToken();
  // }, [token]);

  // const { auth } = useGlobalContext();
  // auth;
  if (!user) {
    return <Navigate to="/login" />;
  }

  return children;
};

export default ProtectedRoute;
