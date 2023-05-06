import { useEffect, useState } from 'react';
import { deleteUser, getInventory, getUsers, viewActivation, viewUserDetails } from '../../API';
import { Avatar, Rate, Space, Table, Typography } from 'antd';
import { TbEye } from 'react-icons/tb';
import { BsFillPencilFill } from 'react-icons/bs';
import { FiTrash2 } from 'react-icons/fi';

const Users = () => {
  const [loading, setLoading] = useState(false);
  const [users, setUsers] = useState([]);

  useEffect(() => {
    setLoading(true);
    viewActivation().then((res) => {
      console.log(res);
      setUsers(res.Data);
      setLoading(false);
    });
  }, []);

  return (
    <Space direction="vertical" size={20}>
      <h2 className="p-4 text-4xl font-bold text-roshetta">المستخدمين</h2>
      <div className=""></div>

      <Table
        className="w-[80vw]"
        columns={[
          {
            title: 'الصورة',
            dataIndex: 'profile_img',
            render: (link) => {
              return <Avatar src={link} />;
            },
            key: 'image',
          },
          {
            title: 'الإسم',
            dataIndex: 'name',

            key: 'name',
          },
          {
            title: 'الرقم',
            dataIndex: 'ssd',
            render: (value) => <span>{value}</span>,
            key: 'ssd',
          },
          {
            title: 'الخيارات',
            dataIndex: 'id',
            render: (id) => {
              return (
                <div className="flex items-center justify-center  ">
                  <span>{}</span>
                  <TbEye
                    className="mx-1 text-xl text-roshetta cursor-pointer"
                    onClick={() =>
                      viewUserDetails(type, id).then((res) => console.log(res))
                    }
                  />
                  <BsFillPencilFill
                    className="mx-1 text-xl text-roshetta cursor-pointer"
                    onClick={() =>
                      editUserDetails(type, id).then((res) => console.log(res))
                    }
                  />
                  <FiTrash2
                    className="mx-1 text-xl text-roshetta cursor-pointer"
                    onClick={() =>
                      deleteUser(type, id).then((res) => console.log(res))
                    }
                  />
                </div>
              );
            },
            key: 'options',
          },
        ]}
        dataSource={users}
        loading={loading}
        pagination={{ pageSize: 7 }}
      ></Table>
    </Space>
  );
};

export default Users;
