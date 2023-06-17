import { Badge, Drawer, Image, Layout, List, Space, Typography } from "antd";
import { MailOutlined, BellFilled } from "@ant-design/icons";

import React, { useContext, useEffect, useState } from "react";
import images from "../../../../images";
import {
  // getComments,
  getOrders,
  // getUsers,
  // replyMessageUser,
  // viewMessage,
} from "../../API";
import { BsFillReplyFill } from "react-icons/bs";
import { getUsers, replyMessageUser, viewMessage } from "../../../../api/admin";
import { AuthContext } from "../../../../store/auth/context";
import { isRequestSuccess } from "../../../../utils/reusedFunctions";

const AdminHeader = () => {
  const { user } = useContext(AuthContext);
  const [comments, setComments] = useState([]);
  const [orders, setOrders] = useState([]);
  const [commentsOpen, setCommentsOpen] = useState(false);
  const [notificationOpen, setNotificationOpen] = useState(false);

  const onViewMessageClick = async (item) => {
    const usersRES = await getUsers(item.role, item.email, user.token);
    let id;
    console.log(item.email);
    if (usersRES.Data[0]) {
      id = usersRES.Data[0].id || 0;
    }
    console.log(usersRES.Data, id);
    let formData = new FormData();
    formData.append("message", item.message);
    const messageUserRES = await replyMessageUser(id, formData, user.token);
    console.log(messageUserRES);
  };

  useEffect(() => {
    // getComments().then((res) => {
    //   setComments(res.comments);
    // });
    getOrders().then((res) => {
      setOrders(res.products);
    });

    // viewMessage().then((res) => {
    //   setComments(res.Data);
    // });

    const requestMessages = async () => {
      const response = await viewMessage("", "", user.token);
      if (isRequestSuccess(response.Status)) {
        setComments(response.Data);
      }
    };
    requestMessages();
  }, []);

  return (
    <Layout.Header className="admin__header">
      <Space style={{ marginRight: "auto" }}>
        <Badge
          count={comments?.length}
          //  dot
        >
          <MailOutlined
            style={{ fontSize: 20 }}
            onClick={() => setCommentsOpen(true)}
          />
        </Badge>
        {/* <Badge count={orders.length}>
          <BellFilled
            style={{ fontSize: 24 }}
            onClick={() => setNotificationOpen(true)}
          />
        </Badge> */}
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
            return (
              <List.Item className="flex items-start justify-center">
                <h3 className="inline-block text-lg font-bold">
                  {item.name} :
                </h3>
                <p className="inline-block text-lg">{item.message}</p>
                <BsFillReplyFill
                  className="cursor-pointer text-xl"
                  title="رد"
                  onClick={() => {
                    // NOTE moved the fucntion in seprated function
                    onViewMessageClick(item);
                  }}
                />
              </List.Item>
            );
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
    </Layout.Header>
  );
};

export default AdminHeader;
