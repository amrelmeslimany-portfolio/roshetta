import React from 'react';
import { Outlet } from 'react-router-dom';
import DoctorHeader from './components/DoctorHeader';
import DoctorFooter from './components/DoctorFooter';
import DoctorSideMenu from './components/DoctorSideMenu';
import './Doctor.scss';
import { Space } from 'antd';

const Doctor = () => {
  return (
    <>
      <div className="doctor">
        <DoctorHeader />
        <Space className="SideMenuAndPageContent">
          <DoctorSideMenu></DoctorSideMenu>
          <div className="doctor__page-content">
            <Outlet />
          </div>
        </Space>
        <DoctorFooter />
      </div>
    </>
  );
};

export default Doctor;
