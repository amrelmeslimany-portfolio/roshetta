import React from 'react';
import { Outlet } from 'react-router-dom';
import DoctorHeader from './components/DoctorHeader';
import DoctorFooter from './components/DoctorFooter';
import DoctorSideMenu from './components/DoctorSideMenu';
import DoctorAuthHandler from './components/DoctorAuthHandler';
import './Doctor.scss';
import { Space } from 'antd';

const Doctor = () => {
  return (
    <>
      <div className="doctor">
        <DoctorHeader />
        {/* <Space className="SideMenuAndPageContent"> */}
        <div className="doctor-SideMenuAndPageContent">
          <DoctorSideMenu />
          <div className="doctor__page-content">
            <DoctorAuthHandler />
            <Outlet />
          </div>
        </div>
        {/* </Space> */}
        {/* <DoctorFooter /> */}
      </div>
    </>
  );
};

export default Doctor;
