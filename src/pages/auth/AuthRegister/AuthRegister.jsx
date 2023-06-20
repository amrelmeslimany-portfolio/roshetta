import React from "react";
import { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { GiWeight } from "react-icons/gi";
import { MdLocationOn } from "react-icons/md";
import { TbArrowAutofitHeight } from "react-icons/tb";
import { FaHandHoldingMedical, FaRegIdCard } from "react-icons/fa";
import {
  UserOutlined,
  MailFilled,
  LockFilled,
  PhoneFilled,
  IdcardFilled,
} from "@ant-design/icons";
import {
  DatePicker,
  Select,
  Alert,
  Steps,
  Form,
  Input,
  Divider,
  Row,
  Col,
  Button,
  message,
} from "antd";
import images from "../../../images";
import { useGlobalContext } from "../../../context";
import "./AuthRegister.scss";
import { AppWrapper } from "../../../wrapper";
import { MyLoader } from "../../../components";
import AuthLayout from "../../../components/Auth/AuthLayout";
import { Colors } from "chart.js";
import CustomLink from "../../../components/Buttons/Links";
import { PrimaryButton } from "../../../components/Buttons/Primary";
import PresonalFields from "./PresonalFields";
import AccountFields from "./AccountFields";
import SecureFields from "./SecureFields";
import { register } from "../../../api/auth";
import {
  errorToString,
  isRequestSuccess,
} from "../../../utils/reusedFunctions";

const FORM_STEPS = [
  {
    title: "الشخصية",
    content: <PresonalFields />,
  },
  {
    title: "الحساب",
    content: <AccountFields />,
  },
  {
    title: "الامان",
    content: <SecureFields />,
  },
];

const AuthRegister = () => {
  const [currentForm, setCurrentForm] = useState(0);
  const [loading, setLoading] = useState(false);
  const [formValues, setFormValues] = useState({});

  const navigate = useNavigate();

  const [form] = Form.useForm();

  const isNext = currentForm < FORM_STEPS.length - 1;

  const onNext = () => {
    setFormValues((prev) => ({ ...prev, ...form.getFieldsValue() }));
    setCurrentForm(currentForm + 1);
  };

  const onPrevious = () => {
    setCurrentForm(currentForm - 1);
  };

  const onSubmit = async (values) => {
    const formData = new FormData();
    const newuser = { ...formValues, ...values };

    formData.append("role", newuser.role);
    formData.append("first_name", newuser.username);
    formData.append("last_name", newuser.username);
    formData.append("email", newuser.email);
    formData.append("governorate", newuser.governorate);
    formData.append("gender", newuser.gender);
    formData.append("ssd", newuser.ssdNumber);
    formData.append("phone_number", newuser.phoneNumber);
    formData.append("birth_date", newuser.birthday);
    formData.append("password", newuser.password);
    formData.append("confirm_password", newuser.repassword);
    formData.append("weight", newuser.weight || "");
    formData.append("height", newuser.height || "");
    formData.append("specialist", newuser.specialist || "");

    try {
      setLoading(true);
      const response = await register(formData);
      // COMMENT If Back-End response OK
      if (isRequestSuccess(response.Status)) {
        localStorage.setItem(
          "registerData",
          JSON.stringify([newuser.role, newuser.email])
        );
        message.success("تم انشاء حساب بنجاح");
        navigate("/active-email");
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
    <AuthLayout text="  يرجي مليء البيانات لانشاء حساب جديد">
      <Steps className="w-[410px]" current={currentForm} items={FORM_STEPS} />
      <Form
        className="customed-form form-gap"
        form={form}
        onFinish={onSubmit}
        // onFinishFailed={onSubmitFail}
        initialValues={{ remember: true }}
        autoComplete="off"
      >
        {FORM_STEPS[currentForm].content}

        <Form.Item className="mt-4">
          <Row className="justify-between">
            {currentForm > 0 && (
              <Col>
                <Form.Item>
                  <Button
                    disabled={loading}
                    shape="round"
                    htmlType="button"
                    onClick={onPrevious}
                    block
                  >
                    للخلف
                  </Button>
                </Form.Item>
              </Col>
            )}
            <Col className="ms-auto">
              {!isNext && (
                <Form.Item>
                  <PrimaryButton htmlType="submit" loading={loading} block>
                    {loading ? "جارى التحميل" : "انشاء حساب"}
                  </PrimaryButton>
                </Form.Item>
              )}
              {isNext && (
                <Form.Item>
                  <PrimaryButton htmlType="button" onClick={onNext} block>
                    التالي
                  </PrimaryButton>
                </Form.Item>
              )}
            </Col>
          </Row>
          <Divider className="font-14px">أو</Divider>
          <CustomLink.Outlined to="/login" className="block">
            تسجيل الدخول
          </CustomLink.Outlined>
        </Form.Item>
      </Form>
    </AuthLayout>
  );
};

export default AppWrapper(AuthRegister);
