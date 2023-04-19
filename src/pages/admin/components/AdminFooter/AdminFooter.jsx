import { Typography } from 'antd';
import React from 'react';

const AdminFooter = () => {
  return (
    <div className="admin__footer">
      <Typography.Link href="tel:+123456789">+123456789</Typography.Link>
      <Typography.Link href="https://www.google.com" target='_blank'>
        سياسية الخصوصية
      </Typography.Link>
      <Typography.Link href="https://www.google.com" target='_blank'>
        من هم فريق روشتة
      </Typography.Link>
    </div>
  );
};

export default AdminFooter;
