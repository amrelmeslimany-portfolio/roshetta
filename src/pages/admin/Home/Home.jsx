import React from "react";
import { Layout, Space } from "antd";
import { useGlobalContext } from "../../../context";
import AdminHeader from "../components/AdminHeader/AdminHeader";
import AdminSideMenu from "../components/AdminSideMenu/AdminSideMenu";
import AdminFooter from "../components/AdminFooter/AdminFooter";
import { Outlet } from "react-router-dom";

import "./Home.scss";
import AdminAuthHandler from "../components/AdminAuthHandler";
import ShowAlert from "../../../components/ShowAlert";
import CopyRights from "../../../components/Footer/CopyRights";
const Home = () => {
  return (
    <>
      <Layout>
        <AdminSideMenu></AdminSideMenu>
        <Layout style={{ backgroundColor: "#f8f8f8" }}>
          <AdminHeader />
          <Layout.Content className="main-content">
            <AdminAuthHandler />
            <Outlet />
          </Layout.Content>
          <Layout.Footer className="main__footer">
            <CopyRights />
          </Layout.Footer>
        </Layout>
      </Layout>
    </>
  );
};

//  <div className="admin">
//       <AdminHeader />
//       <div className="SideMenuAndPageContent bg-gray-100">
//         <AdminSideMenu></AdminSideMenu>
//         {/* <AdminPageContent></AdminPageContent> */}
//     //     <div className="admin__page-content">
//     //       <AdminAuthHandler />
//     //       <ShowAlert />
//     //       <Outlet />
//     //     </div>
//     //   </div>
//     //   <AdminFooter />
//     // </div>

export default Home;
