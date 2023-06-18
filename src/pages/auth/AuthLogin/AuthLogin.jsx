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
  const [loading, setLoading] = useState(false);
  // const userData = JSON.parse(localStorage.getItem("userData"));

  // if (JSON.parse(localStorage.getItem("userData"))) {
  //   const userData = JSON.parse(localStorage.getItem("userData"));

  //   if (userData.type === "doctor") {
  //     return <Navigate to="/doctor/personal-info" />;
  //   }
  //   if (userData.type === "admin") {
  //     return <Navigate to="/admin/dashboard" />;
  //   }
  // }
  // const { setAuthUser, alert, setAlert } = useGlobalContext();
  // const [auth, setAuth] = useState("");

  // const [email, setEmail] = useState("");
  const [role, setRole] = useState("");
  // const [ssd, setSsd] = useState("");
  // const [password, setPassword] = useState("");

  const navigate = useNavigate();
  // navigate(0) Make a refresh

  // const message = JSON.parse(localStorage.getItem("message"));

  // const handleSubmit = (e) => {
  //   e.preventDefault();

  //   formData.append("role", role);
  //   formData.append("user_id", ssd);
  //   formData.append("password", password);
  //   formData.append("password_edit", "");

  //   if (ssd < 14 && password < 6 && role === null) {
  //     setAlert({
  //       msg: " الرقم القومي يجب ان يكون 14 رقم والباسوورد غير خالي",
  //       show: true,
  //       type: "error",
  //     });
  //   } else if (ssd < 14) {
  //     initScroll();
  //     setAlert({
  //       msg: "الرقم القومي يجب ان يكون 14 رقم",
  //       show: true,
  //       type: "error",
  //     });
  //   } else if (password < 6) {
  //     initScroll();
  //     setAlert({
  //       msg: "ادخل باسوورد مكون من 6 ارقام او اكثر",
  //       show: true,
  //       type: "error",
  //     });
  //   } else if (!role) {
  //     initScroll();
  //     setAlert({
  //       msg: "ادخل نوع الحساب لو سمحت",
  //       show: true,
  //       type: "error",
  //     });
  //   } else {
  //     setLoading(true);
  //     fetch("http://localhost:80/roshetta/api/users/login", {
  //       method: "POST",
  //       // headers: {
  //       //   'Content-Type': 'application/json',
  //       // },
  //       body: formData,
  //     })
  //       .then((res) => res.json())
  //       .then((data) => {
  //         console.log(data.Message);
  //         let message = data.Message;
  //         if (data.Status > 299) {
  //           setLoading(false);
  //           initScroll();
  //           setAlert({
  //             msg: `${message.password_err} \n ${message.type_err} \n ${message.user_id_err} `,
  //             show: true,
  //             type: "error",
  //           });
  //         } else {
  //           localStorage.setItem("userData", JSON.stringify(data.Data));
  //           setLoading(false);
  //           if (data.Data.type === "doctor") {
  //             navigate("/doctor/personal-info");
  //           } else if (data.Data.type === "admin") {
  //             navigate("/admin/dashboard");
  //           }
  //           // setRole('');
  //           // setEmail('');
  //           // setPassword('');
  //           // setSsd('');
  //         }
  //       });
  //   }
  // };

  const { loginAction } = useContext(AuthContext);

  const [form] = Form.useForm();

  const onRoleChange = (value) => setRole(value);

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

  return (
    <>
      <AuthLayout text="قم بادخال البيانات الخاصه بتسجيل الدخول">
        <Form
          className="customed-form form-gap"
          form={form}
          onFinish={onSubmit}
          onFinishFailed={onSubmitFail}
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
              onChange={onRoleChange}
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
