import React from 'react';
import { Space } from 'antd';
import { useGlobalContext } from '../../../context';
import './Home.scss';
import AdminHeader from '../components/AdminHeader/AdminHeader';
import AdminSideMenu from '../components/AdminSideMenu/AdminSideMenu';
import AdminPageContent from '../components/AdminPageContent/AdminPageContent';
import AdminFooter from '../components/AdminFooter/AdminFooter';
const Home = () => {
  return (
    <>
      <div className="admin">
        <AdminHeader />
        <Space>
          <AdminSideMenu></AdminSideMenu>
          <AdminPageContent></AdminPageContent>
        </Space>
        <AdminFooter />
      </div>
    </>
  );
};

export default Home;
