import {
  AppstoreOutlined,
  ShopOutlined,
  ShoppingCartOutlined,
  UserOutlined,
} from "@ant-design/icons";
import { Layout, Menu } from "antd";
import React, { useContext, useEffect, useState } from "react";
import {
  MdAdminPanelSettings,
  MdDashboardCustomize,
  MdEdit,
  MdLocalPharmacy,
  MdOutlineLocalPharmacy,
} from "react-icons/md";
import { TbActivityHeartbeat, TbUsers } from "react-icons/tb";
import { useLocation, useNavigate } from "react-router-dom";
import { logout } from "../../../../api/auth";
import { AuthContext } from "../../../../store/auth/context";
import images from "../../../../images";
// import { logOut } from '../../API';

const AdminSideMenu = () => {
  const { logoutAction } = useContext(AuthContext);
  const location = useLocation();
  const [selectedKeys, setSelectedKeys] = useState("/");
  useEffect(() => {
    const pathname = location.pathname;
    setSelectedKeys(pathname);
  }, [location.pathname]);

  const navigate = useNavigate();
  return (
    <Layout.Sider className="admin__side-menu">
      <div className="slider-logo">
        <img src={images.logo2} width={100} />
      </div>
      <Menu
        className="admin__side-menu--vertical"
        mode="vertical"
        onClick={(item) => {
          // item.key
          navigate(item.key);
        }}
        selectedKeys={[selectedKeys]}
        items={[
          {
            label: "الصفحة الرئيسية",
            icon: <MdDashboardCustomize style={{ color: "#49ce91" }} />,
            key: "/admin/dashboard",
          },
          // {
          //   label: 'اضافه ادمن',
          //   icon: <MdAdminPanelSettings style={{ color: '#49ce91' }} />,
          //   key: '/admin/add-admin',
          // },
          // {
          //   label: 'تعديل مباشر',
          //   icon: <MdEdit style={{ color: '#49ce91' }} />,
          //   key: '/admin/edit-info',
          // },
          {
            label: "العيادات",
            icon: <MdLocalPharmacy style={{ color: "#49ce91" }} />,
            key: "/admin/clinics",
          },
          {
            label: "الصيدليات",
            icon: <MdOutlineLocalPharmacy style={{ color: "#49ce91" }} />,
            key: "/admin/pharmacies",
          },
          {
            label: "المستخدمين",
            icon: <TbUsers style={{ color: "#49ce91" }} />,
            key: "/admin/users",
          },
          {
            label: "تفعيل الحسابات",
            icon: <TbActivityHeartbeat style={{ color: "#49ce91" }} />,
            key: "/admin/activate-accounts",
          },
          {
            label: "تسجيل الخروج",
            icon: <UserOutlined style={{ color: "#49ce91" }} />,
            key: "/login",
            onClick: () =>
              logout()
                .then((data) => console.log(data))
                .finally(() => logoutAction()),
          },
        ]}
      ></Menu>
    </Layout.Sider>
  );
};

export default AdminSideMenu;
