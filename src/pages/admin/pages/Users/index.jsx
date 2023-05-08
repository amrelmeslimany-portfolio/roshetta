import { useEffect, useState } from 'react';
import {
  deleteUser,
  getInventory,
  getUsers,
  viewActivation,
  viewUserDetails,
} from '../../API';
import {
  Alert,
  Avatar,
  Radio,
  Rate,
  Space,
  Switch,
  Table,
  Typography,
} from 'antd';
import { Input } from 'antd';
const { Search } = Input;
import { TbEye } from 'react-icons/tb';
import { BsFillPencilFill } from 'react-icons/bs';
import { FiTrash2 } from 'react-icons/fi';
import { useGlobalContext } from '../../../../context';
import { Link } from 'react-router-dom';

const Users = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();
  const [loading, setLoading] = useState(false);
  const [users, setUsers] = useState([]);
  const [radioValue, setRadioValue] = useState('');
  const [switchValue, setSwitchValue] = useState(0);

  const refreshTableData = (searchTerm = '') => {
    setLoading(true);
    getUsers(radioValue, searchTerm).then((res) => {
      console.log(res);
      setUsers(res.Data);
      setLoading(false);
    });
  };
  const showAlert = (msg = 'حدثت مشكلة', type = 'error') => {
    window.scrollTo({
      top: 0,
      left: 0,
      behavior: 'smooth',
    });
    setAlert({
      msg: msg,
      show: true,
      type: type,
    });
  };
  const onChange = (e) => {
    setLoading(true);
    if (e === false) {
      setSwitchValue(0);
      getUsers(radioValue, '').then((res) => {
        setUsers(res.Data);
        setLoading(false);
      });
    }
    if (e === true) {
      setSwitchValue(1);
      getUsers(radioValue, '').then((res) => {
        setUsers(res.Data);
        setLoading(false);
      });
    }
  };
  const onRadioChange = (e) => {
    setLoading(true);
    // ابحث في المشكلة دي
    setRadioValue(e.target.value);
    getUsers(e.target.value, '').then((res) => {
      // ممكن اسأل عمرو فيها
      // setUsers(radioValue, '').then((res) => {
      setUsers(res.Data);
      setLoading(false);
    });
  };

  const onSearch = (e) => {
    refreshTableData(e.target.value);
    console.log(e.target.value);
  };

  useEffect(() => {
    setLoading(true);
    getUsers().then((res) => {
      console.log(res);
      setUsers(res.Data);
      setLoading(false);
    });
  }, []);

  useEffect(() => {
    const myTimeout = setTimeout(() => {
      setAlert({ msg: '', show: false, type: '' });
    }, 3000);

    return () => {
      clearTimeout(myTimeout);
    };
  }, [alert.show]);

  return (
    <>
      {alert.show && (
        <Alert
          style={{
            marginBottom: 20,
          }}
          message="تنبيه!"
          description={alert.msg}
          type={alert.type}
          showIcon
        />
      )}
      <Space direction="vertical" size={20}>
        <h2 className="p-4 text-4xl font-bold text-roshetta">المستخدمين</h2>
        <h4 className="px-4 text-2xl font-bold text-slate-500">
          فلترة النتائج
        </h4>
        <Space className="rounded-lg bg-slate-200 p-4" size={20}>
          {/* <span>مفعل / غير مفعل</span>
          <Switch onChange={onChange} /> */}
          <span>اختار النوع:</span>
          <Radio.Group onChange={onRadioChange} value={radioValue}>
            <Radio value={''}>الكل</Radio>
            <Radio value={'doctor'}>دكتور</Radio>
            <Radio value={'pharmacist'}>صيدلي</Radio>
            <Radio value={'clinic'}>عيادة</Radio>
            <Radio value={'pharmacy'}>صيدلية</Radio>
          </Radio.Group>
          <span>ابحث عن شخص:</span>
          <Search
            placeholder="اكتب الإسم او الرقم القومي"
            allowClear
            enterButton="ابحث"
            size="large"
            onChange={onSearch}
          />
        </Space>
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
              title: 'الرقم القومي',
              dataIndex: 'ssd',
              render: (value) => <span>{value}</span>,
              key: 'ssd',
            },
            {
              title: 'الخيارات',
              dataIndex: ['role'],
              render: (role, userData) => {
                const id = userData.id;
                return (
                  <div className="flex items-center justify-center  ">
                    <Link to={`/admin/users/view/${role}/${id}`}>
                      <TbEye className="mx-1 cursor-pointer text-xl text-roshetta" />
                    </Link>
                    <Link to={`/admin/users/edit/${role}/${id}`}>
                      <BsFillPencilFill className="mx-1 cursor-pointer text-xl text-roshetta" />
                    </Link>
                    <FiTrash2
                      className="mx-1 cursor-pointer text-xl text-roshetta"
                      onClick={() => {
                        console.log(role, userData.id);
                        deleteUser(role, id).then((res) => {
                          showAlert(res.Message, 'success');
                          refreshTableData();
                        });
                      }}
                    />
                  </div>
                );
              },
              key: 'options',
            },
          ]}
          dataSource={users}
          loading={loading}
          pagination={{ pageSize: 5 }}
        ></Table>
      </Space>
    </>
  );
};

export default Users;
