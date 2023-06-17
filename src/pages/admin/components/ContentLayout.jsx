import { Space, Typography } from "antd";
import React from "react";

const ContentLayout = ({ title, children }) => {
  return (
    <Space size={15} direction="vertical" style={{ width: "100%" }}>
      {/* <h2 className="p-4 text-4xl font-bold text-roshetta">الصفحة الرئيسية</h2> */}
      <Typography.Title level={3} className="bold">
        {title}
      </Typography.Title>

      {children}
    </Space>
  );
};

export default ContentLayout;
