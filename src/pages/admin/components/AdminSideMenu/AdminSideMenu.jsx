import {
  AppstoreOutlined,
  ShopOutlined,
  ShoppingCartOutlined,
  UserOutlined,
} from '@ant-design/icons';
import { Menu } from 'antd';
import React from 'react';
import { MdAdminPanelSettings, MdDashboardCustomize, MdEdit, MdLocalPharmacy, MdOutlineLocalPharmacy } from 'react-icons/md';
import { TbActivityHeartbeat, TbUsers } from 'react-icons/tb';
import { useNavigate } from 'react-router-dom';

const AdminSideMenu = () => {
  const navigate = useNavigate();
  return (
    <div className="admin__side-menu">
      <Menu
        onClick={(item) => {
          // item.key
          navigate(item.key);
        }}
        items={[
          {
            label: 'الصفحة الرئيسية',
            icon: <MdDashboardCustomize />,
            key: '/admin/dashboard',
          },
          {
            label: 'اضافه ادمن',
            icon: <MdAdminPanelSettings />,
            key: '/admin/add-admin',
          },
          {
            label: 'تعديل مباشر',
            icon: <MdEdit />,
            key: '/admin/edit-info',
          },
          {
            label: 'العيادات',
            icon: <MdLocalPharmacy />,
            key: '/admin/clinics',
          },
          {
            label: 'الصيدليات',
            icon: <MdOutlineLocalPharmacy />,
            key: '/admin/pharmacies',
          },
          {
            label: 'المستخدمين',
            icon: <TbUsers />,
            key: '/admin/users',
          },
          {
            label: 'تفعيل الحسابات',
            icon: <TbActivityHeartbeat />,
            key: '/admin/activate-accounts',
          },
          {
            label: 'تسجيل الخروج',
            icon: <UserOutlined />,
            key: '/admin/logout',
          },
        ]}
      ></Menu>
    </div>
  );
};

export default AdminSideMenu;
