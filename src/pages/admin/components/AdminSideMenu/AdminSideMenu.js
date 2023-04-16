import {
  AppstoreOutlined,
  ShopOutlined,
  ShoppingCartOutlined,
  UserOutlined,
} from '@ant-design/icons';
import { Menu } from 'antd';
import React from 'react';
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
            icon: <AppstoreOutlined />,
            key: '/admin/dashboard',
          },
          {
            label: 'اضافه ادمن',
            icon: <AppstoreOutlined />,
            key: '/admin/add-admin',
          },
          {
            label: 'تعديل مباشر',
            icon: <AppstoreOutlined />,
            key: '/admin/edit-info',
          },
          {
            label: 'العيادات',
            icon: <AppstoreOutlined />,
            key: '/admin/clinics',
          },
          {
            label: 'الصيدليات',
            icon: <AppstoreOutlined />,
            key: '/admin/pharmacies',
          },
          {
            label: 'المستخدمين',
            icon: <AppstoreOutlined />,
            key: '/admin/users',
          },
          {
            label: 'تفعيل الحسابات',
            icon: <ShoppingCartOutlined />,
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
