import React, { useState, useEffect } from "react";
import { Link, Navigate, useNavigate } from "react-router-dom";
import { LockFilled, IdcardFilled } from "@ant-design/icons";
import { Alert, Select } from "antd";

import { useGlobalContext } from "../../../context";
import images from "../../../images";
import "./AuthLogin.scss";
import { AppWrapper } from "../../../wrapper";
import { MyLoader } from "../../../components";

const AuthLogin = () => {
  const [loading, setLoading] = useState(false);
  const userData = JSON.parse(localStorage.getItem("userData"));

  if (JSON.parse(localStorage.getItem("userData"))) {
    const userData = JSON.parse(localStorage.getItem("userData"));

    if (userData.type === "doctor") {
      return <Navigate to="/doctor/personal-info" />;
    }
    if (userData.type === "admin") {
      return <Navigate to="/admin/dashboard" />;
    }
  }
  const { setAuthUser, alert, setAlert } = useGlobalContext();
  const [auth, setAuth] = useState("");

  const [email, setEmail] = useState("");
  const [role, setRole] = useState("");
  const [ssd, setSsd] = useState("");
  const [password, setPassword] = useState("");

  const navigate = useNavigate();
  // navigate(0) Make a refresh
  let formData = new FormData();
  const message = JSON.parse(localStorage.getItem("message"));

  const handleSubmit = (e) => {
    e.preventDefault();

    formData.append("role", role);
    formData.append("user_id", ssd);
    formData.append("password", password);
    formData.append("password_edit", "");

    if (ssd < 14 && password < 6 && role === null) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth",
      });
      setAlert({
        msg: " الرقم القومي يجب ان يكون 14 رقم والباسوورد غير خالي",
        show: true,
        type: "error",
      });
    } else if (ssd < 14) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth",
      });
      setAlert({
        msg: "الرقم القومي يجب ان يكون 14 رقم",
        show: true,
        type: "error",
      });
    } else if (password < 6) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth",
      });
      setAlert({
        msg: "ادخل باسوورد مكون من 6 ارقام او اكثر",
        show: true,
        type: "error",
      });
    } else if (!role) {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth",
      });
      setAlert({
        msg: "ادخل نوع الحساب لو سمحت",
        show: true,
        type: "error",
      });
    } else {
      setLoading(true);
      fetch("http://localhost:80/roshetta/api/users/login", {
        method: "POST",
        // headers: {
        //   'Content-Type': 'application/json',
        // },
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          console.log(data.Message);
          let message = data.Message;
          if (data.Status > 299) {
            setLoading(false);
            window.scrollTo({
              top: 0,
              left: 0,
              behavior: "smooth",
            });
            setAlert({
              msg: `${message.password_err} \n ${message.type_err} \n ${message.user_id_err} `,
              show: true,
              type: "error",
            });
          } else {
            localStorage.setItem("userData", JSON.stringify(data.Data));
            setLoading(false);
            if (data.Data.type === "doctor") {
              navigate("/doctor/personal-info");
            } else if (data.Data.type === "admin") {
              navigate("/admin/dashboard");
            }
            // setRole('');
            // setEmail('');
            // setPassword('');
            // setSsd('');
          }
        });
    }
  };
  useEffect(() => {
    const myTimeout = setTimeout(() => {
      setAlert({ msg: "", show: false, type: "" });
    }, 3000);

    return () => {
      clearTimeout(myTimeout);
    };
  }, [alert.show]);

  if (loading) {
    return <MyLoader text={"جاري تسجيل الدخول..."} loading={loading} />;
  }

  return (
    <>
      <div className="auth-login">
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
        <div className="auth-login__img">
          <img src={images.logo1} alt="logo" />
        </div>
        <h2 className="auth-login__title">تسجيل الدخول</h2>
        <p className="auth-login__text">يرجي مليء البيانات لتسجيل الدخول</p>
        <form className="auth-login__form" onSubmit={handleSubmit}>
          <div className="auth-login__form--form-custom-input">
            <Select
              placeholder="اختر نوع الحساب..."
              style={{
                width: 450,
                marginTop: 5,
                marginBottom: 35,
              }}
              onChange={(value) => setRole(value)}
              options={[
                {
                  value: "patient",
                  label: "مريض",
                },
                {
                  value: "doctor",
                  label: "دكتور",
                },
                {
                  value: "assistant",
                  label: "مساعد",
                },
                {
                  value: "pharmacist",
                  label: "صيدلي",
                },
                {
                  value: "admin",
                  label: "ادمن",
                },
              ]}
            />
          </div>
          <div className="auth-login__form--form-input">
            <span>
              <IdcardFilled />
            </span>
            <input
              name="ssd"
              type="text"
              placeholder="ادخل الرقم القومي..."
              value={ssd}
              onChange={(e) => setSsd(e.target.value)}
            />
          </div>

          <div className="auth-login__form--form-input">
            <span>
              <LockFilled />
            </span>
            <input
              name="password"
              type="password"
              placeholder="ادخل كلمة المرور..."
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
          </div>
          <button
            className="foucs:outline-2 mt-6 rounded-full bg-roshetta px-40 py-3 text-2xl text-white hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300  active:bg-green-600"
            type="submit"
          >
            تسجيل الدخول
          </button>
          <p className="auth-login__register-btn">
            <Link to={"/forget-password"}>هل نسيت كلمه المرور ؟</Link>
          </p>
        </form>
        <div className="auth-login__no-account">
          <p>ليس لديك حساب ؟ </p>
          <Link to={"/register"}>اضغط هنا لانشاء حساب</Link>
        </div>
      </div>
      <p className="app__footer">
        برمجه فريق <span>روشتة</span> 2023
      </p>
    </>
  );
};

export default AppWrapper(AuthLogin);
