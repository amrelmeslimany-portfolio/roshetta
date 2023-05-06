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
import { useGlobalContext } from '../../../../context';

const ActivateAccounts = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();

  const [loading, setLoading] = useState(false);
  const [dataSource, setDataSource] = useState([]);
  const [radioValue, setRadioValue] = useState('');
  const [switchValue, setSwitchValue] = useState(0);
  const refreshTableData = () => {
    setLoading(true);
    viewActivation(radioValue, '', switchValue).then((res) => {
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
  useEffect(() => {
    setLoading(true);
    viewActivation(radioValue, '', switchValue).then((res) => {
      setDataSource(res.Data);
      setLoading(false);
    });
  }, []);

  const handleSubmit = () => {
    console.log('hello');
  };
  const onChange = (e) => {
    setLoading(true);
    if (e === false) {
      setSwitchValue(0);
      viewActivation(radioValue, '', 0).then((res) => {
        setDataSource(res.Data);
        setLoading(false);
      });
    }
    if (e === true) {
      setSwitchValue(1);
      viewActivation(radioValue, '', 1).then((res) => {
        setDataSource(res.Data);
        setLoading(false);
      });
    }
  };
  const onRadioChange = (e) => {
    setLoading(true);
    // ابحث في المشكلة دي
    setRadioValue(e.target.value);
    viewActivation(e.target.value, '', switchValue).then((res) => {
      // ممكن اسأل عمرو فيها
      // viewActivation(radioValue, '', switchValue).then((res) => {
      setDataSource(res.Data);
      setLoading(false);
    });
  };

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
        <h2 className="p-4 text-4xl font-bold text-roshetta">تفعيل الحسابات</h2>
        <h4 className="px-4 text-2xl font-bold text-slate-500">
          فلترة النتائج
        </h4>
        <Space className="rounded-lg bg-slate-200 p-4" size={20}>
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
              render: (value) => <span>{value}</span>,
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
                      تفعيل الحساب
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
