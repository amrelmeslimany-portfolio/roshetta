import { Badge, Image, Space, Typography } from 'antd';
import { MailOutlined, BellFilled } from '@ant-design/icons';

import React from 'react';
import images from '../../../../images';

const AdminHeader = () => {
  return (
    <div className="admin__header">
      <Image width={40} src={images.logo1} />
      <h1>ادمن روشتة</h1>
      <Space>
        <Badge count={12}>
          <MailOutlined style={{ fontSize: 24 }} />
        </Badge>
        <Badge>
          <BellFilled style={{ fontSize: 24 }} />
        </Badge>
      </Space>
    </div>
  );
};

export default AdminHeader;
