import React from 'react';
import { Link } from 'react-router-dom';
import './Navbar.css';

const Navbar = () => {
  return (
    <>
      <Link to="doctor-home">صفحة الدكتور</Link>
      <Link to="register">تسجيل جديد</Link>
      <Link to="login">تسجيل الدخول</Link>
    </>
  );
};

export default Navbar;
