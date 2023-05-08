import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { Button, Checkbox, DatePicker, Form, Input, Tabs } from 'antd';
import { editProfileDetails, viewUserDetails } from '../../API';
import { MyLoader } from '../../../../components';
import { useGlobalContext } from '../../../../context';
import ShowAlert from '../../../../components/ShowAlert';

const dateFormat = 'YYYY/MM/DD';
const EditPersonalInfo = ({ user, id, type }) => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();

  let formData = new FormData();
  const onFinish = (values) => {
    console.log('Success:', values);
    
    formData.append('name', values.name);
    formData.append('phone_number', values.phoneNumber);
    formData.append('governorate', values.governorate);
    formData.append('birth_date', values.birthDate);
    formData.append('gender', values.gender);
    formData.append('specialist', values.specialist);
    formData.append('height', values.height);
    formData.append('weight', values.weight);
    editProfileDetails(type, id, formData).then((res) => {
      console.log(res);
      <ShowAlert type={'error'} msg={res.Message} />
    });
  };
  const onFinishFailed = (errorInfo) => {
    console.log('Failed:', errorInfo);
  };
  return (
    <>
      <h4 className="py-4 text-2xl font-bold text-slate-500">
        تعديل البيانات الشخصية
      </h4>
      <Form
        layout="vertical"
        name="basic"
        labelCol={{
          span: 8,
        }}
        wrapperCol={{
          span: 16,
        }}
        style={{
          maxWidth: 600,
        }}
        initialValues={{
          remember: true,
          name: `${user.name}`,
          phoneNumber: `${user.phone_number}`,
          governorate: `${user.governorate}`,
          birthDate: `${user.birth_date}`,
          gender: `${user.gender}`,
          specialist: `${user.specialist}`,
          height: `${user.height || 0}`,
          weight: `${user.weight || 0} `,
        }}
        onFinish={onFinish}
        onFinishFailed={onFinishFailed}
        autoComplete="off"
      >
        <Form.Item
          label="الإسم"
          name="name"
          rules={[
            {
              required: true,
              message: 'ادخل اسمك الجديد',
            },
          ]}
        >
          <Input />
        </Form.Item>

        <Form.Item
          label="رقم الهاتف"
          name="phoneNumber"
          rules={[
            {
              required: true,
              message: 'ادخل رقم الهاتف الجديد',
            },
          ]}
        >
          <Input />
        </Form.Item>

        <Form.Item
          label="المحافظة"
          name="governorate"
          rules={[
            {
              required: true,
              message: 'ادخل محافظتك الجديدة',
            },
          ]}
        >
          <Input />
        </Form.Item>

        {/* <Form.Item
              label="تاريخ الميلاد"
              name="birthDate"
              rules={[
                {
                  required: true,
                  message: 'ادخل تاريخ ميلادك الجديد',
                },
              ]}
            >
              <DatePicker format={dateFormat} />
            </Form.Item> */}
        <Form.Item
          label="تاريخ الميلاد"
          name="birthDate"
          rules={[
            {
              required: true,
              message: 'ادخل تاريخ ميلادك الجديد',
            },
          ]}
        >
          <Input />
        </Form.Item>

        <Form.Item
          label="الجنس"
          name="gender"
          rules={[
            {
              required: true,
              message: 'ادخل جنسك الجديد',
            },
          ]}
        >
          <Input />
        </Form.Item>

        {user.type === 'doctor' && (
          <Form.Item
            label="ادخل تخصصك الجديد"
            name="specialist"
            rules={[
              {
                required: true,
                message: 'ادخل اسمك الجديد',
              },
            ]}
          >
            <Input />
          </Form.Item>
        )}

        <Form.Item
          label="ارتفاعك"
          name="height"
          rules={[
            {
              required: true,
              message: 'ادخل ارتفاعك الجديد',
            },
          ]}
        >
          <Input />
        </Form.Item>

        <Form.Item
          label="وزنك"
          name="weight"
          rules={[
            {
              required: true,
              message: 'ادخل وزنك الجديد',
            },
          ]}
        >
          <Input />
        </Form.Item>

        {/* <Form.Item
              label="Password"
              name="password"
              rules={[
                {
                  required: true,
                  message: 'Please input your password!',
                },
              ]}
            >
              <Input.Password />
            </Form.Item> */}

        {/* <Form.Item
            name="remember"
            valuePropName="checked"
            wrapperCol={{
              offset: 8,
              span: 16,
            }}
          >
            <Checkbox>Remember me</Checkbox>
          </Form.Item> */}

        {/* <Form.Item
              wrapperCol={{
                offset: 8,
                span: 16,
              }}
            >
              <Button className="bg-roshetta" type="primary" htmlType="submit">
                Submit
              </Button>
            </Form.Item> */}
        <button
          className="foucs:outline-2 mt-6 rounded-full bg-roshetta px-40 py-3 text-2xl text-white hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300  active:bg-green-600"
          type="submit"
        >
          تعديل البيانات
        </button>
      </Form>
    </>
  );
};

const EditSingleUser = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();

  const [loading, setLoading] = useState(false);
  const { type, id } = useParams();
  const [user, setUser] = useState({});
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  useEffect(() => {
    viewUserDetails(type, id).then((res) => {
      setUser(res.Data);
    });
  }, []);
  const items = [
    {
      key: '1',
      label: `تعديل البيانات الشخصية`,
      children: <EditPersonalInfo user={user} type={type} id={id} />,
    },
    {
      key: '2',
      label: `تعديل الإيميل او الرقم القومي`,
      children: `Content of Tab Pane 1`,
    },
    {
      key: '3',
      label: `تعديل الباسوورد`,
      children: `Content of Tab Pane 2`,
    },
  ];
  const onChange = (key) => {
    console.log(key);
  };

  useEffect(() => {
    const myTimeout = setTimeout(() => {
      setAlert({ msg: '', show: false, type: '' });
    }, 3000);

    return () => {
      clearTimeout(myTimeout);
    };
  }, [alert.show]);

  if (!user.name && !user.email) {
    return (
      <>
        <MyLoader loading={loading} />
      </>
    );
  } else {
    return (
      <>
        {alert.show && (
          <Alert
            style={{
              marginBottom: 20,
            }}
            message="عفواً!"
            description={alert.msg}
            type={alert.type}
            showIcon
          />
        )}
        <div className="my-2 w-[80vw]">
          <Tabs defaultActiveKey="1" items={items} onChange={onChange} />
        </div>
      </>
    );
  }
};

export default EditSingleUser;
