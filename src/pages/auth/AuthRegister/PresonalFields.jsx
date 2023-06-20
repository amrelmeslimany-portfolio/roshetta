import { DatePicker, Form, Input, Radio, Select, Space } from "antd";

import React from "react";
import {
  FaCalendarDay,
  FaPhone,
  FaRegEnvelope,
  FaRegIdCard,
} from "react-icons/fa";
import locale from "antd/es/date-picker/locale/ar_EG";
import { Colors } from "../../../constants/colors";
import { GENDERS } from "../../../constants/data";

const PresonalFields = () => {
  // BUG error
  return (
    <>
      <Form.Item
        name="username"
        rules={[
          {
            required: true,
            message: "ادخل الاسم بالكامل",
          },
        ]}
      >
        <Input
          placeholder="الاسم بالكامل"
          suffix={<FaRegIdCard color={Colors.LIGHT_GRAY} />}
          className="br-round"
        />
      </Form.Item>
      <Form.Item
        name="email"
        rules={[
          {
            required: true,
            message: "ادخل البريد ",
          },
          {
            type: "email",
            message: "ادخل البريد صحيحا ",
          },
        ]}
      >
        <Input
          placeholder="البريد الالكتروني"
          suffix={<FaRegEnvelope color={Colors.LIGHT_GRAY} />}
          className="br-round"
        />
      </Form.Item>
      <Form.Item
        name="ssdNumber"
        rules={[
          {
            required: true,
            message: "ادخل الرقم القومي ",
          },
          { len: 14, message: "يجب ان يكون 14 رقم" },
        ]}
      >
        <Input
          placeholder="الرقم القومي"
          suffix={<FaRegIdCard color={Colors.LIGHT_GRAY} />}
          className="br-round"
        />
      </Form.Item>
      <Form.Item
        name="phoneNumber"
        rules={[
          {
            required: true,
            message: "ادخل رقم الهاتف  ",
          },
          { len: 11, message: "يجب ان يكون 11 رقم" },
        ]}
      >
        <Input
          placeholder="رقم الهاتف"
          suffix={<FaPhone color={Colors.LIGHT_GRAY} />}
          className="br-round"
        />
      </Form.Item>
      <Form.Item
        name="gender"
        rules={[
          {
            required: true,
            message: "يجب ادخل النوع ",
          },
        ]}
      >
        <Select
          className="br-round"
          placeholder="اختر الجنس"
          options={GENDERS}
        />
      </Form.Item>
      <Form.Item
        name="birthday"
        rules={[
          {
            required: true,
            message: "يجب ادخل تاريخ الميلاد",
          },
        ]}
      >
        <DatePicker
          format="YYYY/MM/DD"
          locale={locale}
          placeholder="تاريخ الميلاد"
          suffixIcon={<FaCalendarDay color={Colors.LIGHT_GRAY} />}
          className="br-round w-full"
        />
      </Form.Item>
    </>
  );
};

export default PresonalFields;
