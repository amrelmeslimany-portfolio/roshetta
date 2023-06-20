import React, { useState, useEffect, useRef, useContext } from "react";
import { Link, Navigate, useNavigate } from "react-router-dom";
import { LockFilled, IdcardFilled } from "@ant-design/icons";
import {
  Alert,
  Button,
  Divider,
  Form,
  Input,
  Select,
  message,
  notification,
} from "antd";
import { FaRegIdCard } from "react-icons/fa";
import { useGlobalContext } from "../../../context";
import "./AuthLogin.scss";
import { AppWrapper } from "../../../wrapper";
import { MyLoader } from "../../../components";
import { USER_ROLES } from "../../../constants/data";
import AuthLayout from "../../../components/Auth/AuthLayout";
import { Colors } from "../../../constants/colors";
import { PrimaryButton } from "../../../components/Buttons/Primary";
import CustomLink from "../../../components/Buttons/Links";
import { login } from "../../../api/auth";
import {
  errorToString,
  isRequestSuccess,
} from "../../../utils/reusedFunctions";
import { AuthContext } from "../../../store/auth/context";

const AuthLogin = () => {
  const { loginAction } = useContext(AuthContext);
  const [role, setRole] = useState(null);
  const [userID, setUserID] = useState(null);
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();
  const [form] = Form.useForm();

  const onSubmitFail = ({ errorFields }) => {
    // COMMENT scroll page to first input
    form.scrollToField("role");
  };

  const onSubmit = async (values) => {
    let formData = new FormData();
    formData.append("role", values.role);
    formData.append("user_id", values.userID);
    formData.append("password", values.password);
    formData.append("password_edit", "");

    // Dont remove values of form when submitting
    form.setFieldsValue(values);

    try {
      setLoading(true);
      const response = await login(formData);
      // COMMENT If Back-End response OK
      if (isRequestSuccess(response.Status)) {
        loginAction(response.Data);
        message.success("تم تسجيل الدخول بنجاح");
        navigate("/admin/dashboard");
      }
      // COMMENT IF Back-End response Error ex: password incorrect
      else throw new Error(errorToString(response.Message));
    } catch (error) {
      message.error(error.message, 5);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    const isRegistered = localStorage.getItem("registerData");
    if (isRegistered) {
      const temp = JSON.parse(isRegistered);
      console.log(temp);
      setRole(temp[0]);
      setUserID(temp[1]);
    }
  }, []);

  return (
    <>
      <AuthLayout text="قم بادخال البيانات الخاصه بتسجيل الدخول">
        <Form
          className="customed-form form-gap"
          form={form}
          onFinish={onSubmit}
          onFinishFailed={onSubmitFail}
          initialValues={{
            userID,
            role,
          }}
          autoComplete="off"
        >
          <Form.Item
            name="role"
            rules={[
              {
                // NOTE this validator will remove when develope other users
                validator: roleValidator,
              },
            ]}
          >
            <Select
              className="br-round "
              placeholder="نوع الحساب"
              options={USER_ROLES}
            />
          </Form.Item>
          <Form.Item
            name="userID"
            rules={[
              {
                required: true,
                message: "ادخل الايمل او الرقم القومي الخاص بك",
              },
            ]}
          >
            <Input
              placeholder="الايميل او الرقم القومي"
              suffix={<FaRegIdCard color={Colors.LIGHT_GRAY} />}
              className="br-round "
            />
          </Form.Item>
          <Form.Item
            name="password"
            rules={[
              { required: true, message: "ادخل كلمة المرور" },
              { min: 6, message: "يجب الا تقل عن 6 احرف" },
            ]}
          >
            <Input.Password placeholder="كلمة المرور" className="br-round " />
          </Form.Item>
          <Form.Item>
            <CustomLink to="/forget-password">هل نسيت كلمة المرور ؟</CustomLink>
          </Form.Item>
          <Form.Item>
            <PrimaryButton htmlType="submit" loading={loading} block>
              {loading ? "جاري التحميل" : "تسجيل الدخول"}
            </PrimaryButton>

            <Divider className="font-14px">أو</Divider>
            <CustomLink.Outlined to="/register" className="block">
              انشاء حساب
            </CustomLink.Outlined>
          </Form.Item>
        </Form>
      </AuthLayout>
    </>
  );
};

const roleValidator = (_, value) => {
  if (!value) {
    return Promise.reject("يجب ادخل نوع الحساب");
  } else if (value != "admin") {
    return Promise.reject("الادمن فقط يعمل حاليا, جاري تطوير باقي المستخدمين");
  }
  return Promise.resolve();
};

export default AuthLogin;
