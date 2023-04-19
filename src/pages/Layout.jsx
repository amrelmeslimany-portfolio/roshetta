import React from "react";
import { Footer } from "../components";
import { Outlet } from "react-router-dom";
import { Navbar } from "../components";
const Layout = () => {
  return (
    <>
      <Navbar />
      <Outlet />
      <Footer />
    </>
  );
};

export default Layout;
