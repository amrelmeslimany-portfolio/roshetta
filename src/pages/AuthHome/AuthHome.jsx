import React from "react";
import { Space } from "antd";
import AuthLayout from "../../components/Auth/AuthLayout";
import "./AuthHome.scss";

import CustomLink from "../../components/Buttons/Links";

const AuthHome = () => {
  return (
    <AuthLayout text="روشته ترحب بك, من فضلك اختر التسجيل">
      <Space direction="vertical" className="links">
        <CustomLink.Primary className="block" to="/login">
          تسجيل الدخول
        </CustomLink.Primary>

        <CustomLink.Outlined to="/register" className="block">
          انشاء حساب
        </CustomLink.Outlined>
      </Space>
    </AuthLayout>
  );
};

export default AuthHome;
