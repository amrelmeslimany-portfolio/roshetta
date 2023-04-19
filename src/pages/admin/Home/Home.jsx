import React from 'react';
import { Space } from 'antd';
import { useGlobalContext } from '../../../context';
import AdminHeader from '../components/AdminHeader/AdminHeader';
import AdminSideMenu from '../components/AdminSideMenu/AdminSideMenu';
import AdminFooter from '../components/AdminFooter/AdminFooter';
import { Outlet } from 'react-router-dom';

import './Home.scss';
const Home = () => {
  return (
    <>
      <div className="admin">
        <AdminHeader />
        <Space className="SideMenuAndPageContent">
          <AdminSideMenu></AdminSideMenu>
          {/* <AdminPageContent></AdminPageContent> */}
          <div className="admin__page-content">
            <Outlet />
          </div>
        </Space>
        <AdminFooter />
      </div>
    </>
  );
};

export default Home;
