import { useEffect, useState } from 'react';
import { getInventory } from '../../API';
import { Avatar, Rate, Space, Table, Typography } from 'antd';

const Users = () => {
  const [loading, setLoading] = useState(false);
  const [dataSource, setDataSource] = useState([]);

  useEffect(() => {
    setLoading(true);
    getInventory().then((res) => {
      setDataSource(res.products);
      setLoading(false);
    });
  }, []);

  return (
    <Space direction="vertical" size={20}>
      <h2 className="text-3xl p-2">المستخدمين</h2>
      <Table
        columns={[
          {
            title: 'Thumbnail',
            dataIndex: 'thumbnail',
            render: (link) => {
              return <Avatar src={link} />;
            },
          },
          { title: 'Title', dataIndex: 'title' },
          {
            title: 'Price',
            dataIndex: 'price',
            render: (value) => <span>${value}</span>,
          },
          {
            title: 'Rating',
            dataIndex: 'rating',
            render: (rating) => {
              return <Rate value={rating} allowHalf />;
            },
          },
          { title: 'Stock', dataIndex: 'stock' },
          { title: 'Category', dataIndex: 'category' },
          { title: 'Brand', dataIndex: 'brand' },
        ]}
        dataSource={dataSource}
        loading={loading}
        pagination={{pageSize:5}}
      ></Table>
    </Space>
  );
};

export default Users;
