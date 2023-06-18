import React, { useEffect, useState } from 'react';
import { AppWrapper } from '../../../wrapper';
import { addClinic } from '../API';
import { Space } from 'antd';
import { Button, Checkbox, Form, Input } from 'antd';

const AddClinic = () => {
  const [name, setName] = useState('');
  const [specialist, setSpecialist] = useState('');
  const [price, setPrice] = useState('');
  const [phoneNumber, setPhoneNumber] = useState('');
  const [startWorking, setStartWorking] = useState('');
  const [endWorking, setEndWorking] = useState('');
  const [governorate, setGovernorate] = useState('');
  const [address, setAddress] = useState('');
  let formData = new FormData();

  formData.append('name', name);
  formData.append('specialist', specialist);
  formData.append('price', price);
  formData.append('phone_number', phoneNumber);
  formData.append('start_working', startWorking);
  formData.append('end_working', endWorking);
  formData.append('governorate', governorate);
  formData.append('address', address);

  useEffect(() => {
    addClinic(formData).then((res) => {
      console.log(res);
    });
  }, []);

  const onFinish = (values) => {
    console.log('Success:', values);
  };
  const onFinishFailed = (errorInfo) => {
    console.log('Failed:', errorInfo);
  };

  return (
    <>
      <div className="p-6 h-screen bg-gray-100">
          <Form
            className="flex "
            name="basic"
            labelCol={{ span: 8 }}
            wrapperCol={{ span: 32 }}
            style={{ maxWidth: 600 }}
            initialValues={{ remember: true }}
            onFinish={onFinish}
            onFinishFailed={onFinishFailed}
            autoComplete="off"
          >
            <Form.Item
              label="Username"
              name="username"
              rules={[
                { required: true, message: 'Please input your username!' },
              ]}
            >
              <Input />
            </Form.Item>

            <Form.Item
              label="Password"
              name="password"
              rules={[
                { required: true, message: 'Please input your password!' },
              ]}
            >
              <Input.Password />
            </Form.Item>

            <Form.Item
              name="remember"
              valuePropName="checked"
              wrapperCol={{ offset: 8, span: 16 }}
            >
              <Checkbox>Remember me</Checkbox>
            </Form.Item>

            <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
              <Button type="primary" htmlType="submit">
                Submit
              </Button>
            </Form.Item>
          </Form>
      </div>
    </>
  );
};

export default AppWrapper(AddClinic);
