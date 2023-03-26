import React from 'react';
import { Navigate } from 'react-router-dom';
import { useGlobalContext } from '../context';
const ProtectedRoute = ({ children }) => {
  const { auth } = useGlobalContext();
  console.log(auth);
  if (auth > 299) {
    return <Navigate to="/" />;
  }
  return children;
};

export default ProtectedRoute;
