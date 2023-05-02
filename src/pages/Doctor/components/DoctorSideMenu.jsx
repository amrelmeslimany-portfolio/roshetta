import {
  AppstoreOutlined,
  ShopOutlined,
  ShoppingCartOutlined,
  UserOutlined,
} from '@ant-design/icons';
import { Menu } from 'antd';
import React from 'react';
import {
  MdAdminPanelSettings,
  MdDashboardCustomize,
  MdEdit,
  MdLocalPharmacy,
  MdOutlineLocalPharmacy,
} from 'react-icons/md';
import { TbActivityHeartbeat, TbUsers } from 'react-icons/tb';
import { useNavigate } from 'react-router-dom';
import { logOut } from '../API';

const DoctorSideMenu = () => {
  const navigate = useNavigate();
  return (
    <div className="doctor__side-menu ">
      <Menu
        onClick={(item) => {
          // item.key
          navigate(item.key);
        }}
        items={[
          {
            label: 'الملف الشخصي',
            icon: <MdEdit style={{ color: '#49ce91' }} />,
            key: '/doctor/personal-info',
          },
          {
            label: 'تنشيط الحساب',
            icon: <MdOutlineLocalPharmacy style={{ color: '#49ce91' }} />,
            key: '/doctor/activate-account',
          },
          {
            label: 'عرض العيادات',
            icon: <TbUsers style={{ color: '#49ce91' }} />,
            key: '/doctor/view-clinics',
          },
          {
            label: 'اضافة عيادة جديد',
            icon: <TbActivityHeartbeat style={{ color: '#49ce91' }} />,
            key: '/doctor/add-clinic',
          },
          {
            label: 'تسجيل الخروج',
            icon: <UserOutlined style={{ color: '#49ce91' }} />,
            key: '/login',
            onClick: () => logOut().then((data) => console.log(data)),
          },
        ]}
      ></Menu>
    </div>
  );
};

export default DoctorSideMenu;
