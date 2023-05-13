import React, { useEffect, useState } from 'react';
import { activateUser, viewActivation } from '../../API';

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
import { useGlobalContext } from '../../../../context';
import { motion } from 'framer-motion';

const ActivateAccounts = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();

  const [loading, setLoading] = useState(false);
  const [dataSource, setDataSource] = useState([]);
  const [radioValue, setRadioValue] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [switchValue, setSwitchValue] = useState(0);
  const refreshTableData = (
    radioValue = '',
    searchTerm = '',
    switchValue = ''
  ) => {
    setLoading(true);
    viewActivation(radioValue, searchTerm, switchValue).then((res) => {
      setDataSource(res.Data);
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
      refreshTableData(radioValue, searchTerm, 0);
    }
    if (e === true) {
      setSwitchValue(1);
      refreshTableData(radioValue, searchTerm, 1);
    }
  };
  const onRadioChange = (e) => {
    setRadioValue(e.target.value);
    refreshTableData(e.target.value, searchTerm, switchValue);
  };

  const onSearch = (e) => {
    setSearchTerm(e.target.value);
    refreshTableData(radioValue, e.target.value, switchValue);
  };

  useEffect(() => {
    refreshTableData(radioValue, searchTerm, switchValue);
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
        <motion.div
          initial={{ opacity: 0, scale: 0.5 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.5 }}
        >
          <Alert
            style={{
              marginBottom: 20,
            }}
            message="تنبيه!"
            description={alert.msg}
            type={alert.type}
            showIcon
          />
        </motion.div>
      )}
      <Space direction="vertical" size={20}>
        <h2 className="p-4 text-4xl font-bold text-roshetta">تفعيل الحسابات</h2>
        <h4 className="px-4 text-2xl font-bold text-black">فلترة النتائج</h4>
        <Space className="rounded-lg bg-gray-200 px-2 py-4" size={3}>
          <span>مفعل / غير مفعل</span>
          <Switch onChange={onChange} />
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
          columns={[
            {
              title: 'الصورة الشخصية',
              dataIndex: 'profile_img',
              render: (link) => {
                return <Avatar src={link} />;
              },
            },
            { title: 'الإسم', dataIndex: 'name' },
            {
              title: 'الرقم القومي',
              dataIndex: 'ssd',
              render: (value) => <span className="font-bold">{value}</span>,
            },
            // {
            //   title: 'Rating',
            //   dataIndex: 'rating',
            //   render: (rating) => {
            //     return <Rate value={rating} allowHalf />;
            //   },
            // },
            { title: 'رقم المستخدم', dataIndex: 'user_id', key: 'user_id' },
            {
              title: 'حالة التفعيل',
              dataIndex: 'status',
              key: 'status',
              render: () => (
                <>
                  {switchValue ? (
                    <span className="p-1 text-slate-500">مفعل</span>
                  ) : (
                    <span className="p-1 text-slate-500">غير مفعل</span>
                  )}
                </>
              ),
            },
            {
              title: 'رقم التفعيل',
              dataIndex: 'activation_id',
              key: 'activation_id',
            },
            {
              title: 'نوع الحساب',
              dataIndex: 'type',
              key: 'type',
            },
            {
              title: 'تفعيل',
              dataIndex: ['type'],
              key: 'x',
              render: (type, userData) => {
                return (
                  <>
                    <a
                      onClick={() => {
                        setLoading(true);
                        activateUser(type, userData.activation_id, 1).then(
                          (res) => {
                            if (res.Message === 'الحساب مفعل بالفعل') {
                              showAlert(res.Message, 'warning');
                            } else {
                              showAlert(res.Message, 'success');
                            }
                            refreshTableData();
                            setLoading(false);
                          }
                        );
                      }}
                      className="m-2 rounded-lg border-2 border-green-400 bg-roshetta p-1 text-white transition-colors hover:bg-transparent hover:text-slate-600  "
                    >
                      تنشيط الحساب
                    </a>
                    <a
                      onClick={() => {
                        setLoading(true);
                        type, userData.name, userData.activation_id;
                        activateUser(type, userData.activation_id, -1).then(
                          (res) => {
                            showAlert(res.Message, 'success');
                            refreshTableData();
                            setLoading(false);
                          }
                        );
                      }}
                      className="m-2 rounded-lg border-2 border-green-400 bg-roshetta p-1 text-white transition-colors hover:bg-transparent hover:text-slate-600  "
                    >
                      رفض الحساب
                    </a>
                  </>
                );
              },
            },
          ]}
          dataSource={dataSource}
          loading={loading}
          pagination={{ pageSize: 5 }}
        ></Table>
      </Space>
    </>
  );
};

export default ActivateAccounts;
