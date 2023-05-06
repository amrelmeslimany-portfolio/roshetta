import { Badge, Drawer, Image, List, Space, Typography } from 'antd';
import { MailOutlined, BellFilled } from '@ant-design/icons';

import React, { useEffect, useState } from 'react';
import images from '../../../../images';
import { getComments, getOrders } from '../../API';

const AdminHeader = () => {
  const [comments, setComments] = useState([]);
  const [orders, setOrders] = useState([]);
  const [commentsOpen, setCommentsOpen] = useState(false);
  const [notificationOpen, setNotificationOpen] = useState(false);
  useEffect(() => {
    getComments().then((res) => {
      setComments(res.comments);
    });
    getOrders().then((res) => {
      setOrders(res.products);
    });
  }, []);
  return (
    <div className="admin__header">
      <Image width={40} src={images.logo1} />
      <h1 style={{ color: '#49ce91' }} className="text-3xl font-extrabold">
        ادمن روشتة
      </h1>
      <Space>
        <Badge
          count={comments.length}
          //  dot
        >
          <MailOutlined
            style={{ fontSize: 24 }}
            onClick={() => setCommentsOpen(true)}
          />
        </Badge>
        <Badge count={orders.length}>
          <BellFilled
            style={{ fontSize: 24 }}
            onClick={() => setNotificationOpen(true)}
          />
        </Badge>
      </Space>
      <Drawer
        width={450}
        title="التعليقات"
        open={commentsOpen}
        onClose={() => setCommentsOpen(false)}
        maskClosable
      >
        <List
          dataSource={comments}
          renderItem={(item) => {
            return <List.Item>{item.body}</List.Item>;
          }}
        ></List>
      </Drawer>
      <Drawer
        width={450}
        title="الإشعارات"
        open={notificationOpen}
        onClose={() => setNotificationOpen(false)}
        maskClosable
      >
        <List
          dataSource={orders}
          renderItem={(item) => {
            return <List.Item>{item.title} has been ordered!</List.Item>;
          }}
        ></List>
      </Drawer>
    </div>
  );
};

export default AdminHeader;
