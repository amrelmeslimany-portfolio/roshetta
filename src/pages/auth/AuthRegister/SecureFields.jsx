import { Form, Input } from "antd";
import React from "react";

const SecureFields = () => {
  return (
    <>
      <Form.Item
        name="password"
        rules={[
          { required: true, message: "ادخل كلمة المرور" },
          { min: 6, message: "يجب الا تقل عن 6 احرف" },
        ]}
      >
        <Input.Password placeholder="كلمة المرور" className="br-round " />
      </Form.Item>
      <Form.Item
        name="repassword"
        rules={[
          { required: true, message: "ادخل تأكيد كلمة المرور" },
          ({ getFieldValue }) => ({
            validator(_, value) {
              if (!value || getFieldValue("password") === value) {
                return Promise.resolve();
              }
              return Promise.reject(new Error("كلمة المرور مختلفة عن الاولي"));
            },
          }),
        ]}
      >
        <Input.Password placeholder="تأكيد كلمة المرور" className="br-round " />
      </Form.Item>
    </>
  );
};

export default SecureFields;
