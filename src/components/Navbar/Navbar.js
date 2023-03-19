import React from 'react';
import { Link } from 'react-router-dom';
import './Navbar.scss';

const Navbar = () => {
  return (
    <>
      <Link to="doctor-home">Doctor Home</Link>
      <Link to="register">Register</Link>
      <Link to="login">Login</Link>
    </>
  );
};

export default Navbar;
