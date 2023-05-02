import React, { useEffect, useState } from 'react';
import { viewActivation } from '../../API';

import { Avatar, Rate, Space, Table, Typography } from 'antd';

const ActivateAccounts = () => {
  const [loading, setLoading] = useState(false);
  const [dataSource, setDataSource] = useState([]);

  useEffect(() => {
    setLoading(true);
    viewActivation().then((res) => {
      console.log(res.Data);
      setDataSource(res.Data);
      setLoading(false);
    });
  }, []);

  return (
    <Space direction="vertical" size={20}>
      <h2 className="p-4 text-4xl font-bold text-roshetta">تفعيل الحسابات</h2>
      <Table
        columns={[
          {
            title: 'Thumbnail',
            dataIndex: 'profile_img',
            render: (link) => {
              return <Avatar src={link} />;
            },
          },
          { title: 'Name', dataIndex: 'name' },
          {
            title: 'Ssd',
            dataIndex: 'ssd',
            render: (value) => <span>${value}</span>,
          },
          // {
          //   title: 'Rating',
          //   dataIndex: 'rating',
          //   render: (rating) => {
          //     return <Rate value={rating} allowHalf />;
          //   },
          // },
          { title: 'User ID', dataIndex: 'user_id' },
          { title: 'Activation Status', dataIndex: 'status' },
          { title: 'Activation ID', dataIndex: 'activation_id' },
          {
            title: 'Action',
            dataIndex: '',
            key: 'x',
            render: () => (
              <a className="rounded-lg bg-roshetta p-1 text-white">Activate</a>
            ),
          },
        ]}
        dataSource={dataSource}
        loading={loading}
        pagination={{ pageSize: 7 }}
      ></Table>
    </Space>
  );
};

export default ActivateAccounts;
