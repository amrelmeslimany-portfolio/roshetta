import React, { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import {
  Button,
  Checkbox,
  DatePicker,
  Form,
  Input,
  Radio,
  Switch,
  Tabs,
} from "antd";
import {
  editEmailOrSSdDetails,
  editProfileDetails,
  editProfilePlaceDetails,
  viewUserDetails,
} from "../../API";
import { MyLoader } from "../../../../components";
import { useGlobalContext } from "../../../../context";
import ShowAlert from "../../../../components/ShowAlert";
import { displayAlert } from "../../../../utils/displayAlert";
import { editPasswordDetails } from "../../API";

const dateFormat = "YYYY/MM/DD";
const EditPersonalInfo = ({ user, id, type }) => {
  const { setAlert } = useGlobalContext();
  const [dataChanged, setDataChanged] = useState(false);
  const navigate = useNavigate();

  let formData = new FormData();
  const onFinish = (values) => {
    console.log("Success:", values);

    formData.append("name", values.name);
    formData.append("phone_number", values.phoneNumber);
    formData.append("governorate", values.governorate);
    formData.append("birth_date", values.birthDate);
    formData.append("gender", values.gender);
    if (user.type === "doctor") {
      console.log(user.type);
      formData.append("specialist", values.specialist);
    }
    if (user.type === "patient") {
      formData.append("height", values.height);
    }
    if (user.type === "patient") {
      formData.append("weight", values.weight);
    }
    if (type === "pharmacy" || type === "clinic") {
      editProfilePlaceDetails(type, id, formData).then((res) => {
        console.log(res);
        if (res.Status === 400) {
          setAlert({
            msg: "لم تعدل على اي بيانات",
            show: true,
            type: "error",
            headMsg: "تنبيه!",
          });
        } else {
          navigate("/admin/users");
          setAlert({
            msg: "تم تعديل بيانات العيادة / الصيدلية بنجاح",
            show: true,
            type: "success",
            headMsg: "تنبيه!",
          });
        }
      });
    } else {
      editProfileDetails(type, id, formData).then((res) => {
        console.log(res);
        if (res.Status === 400) {
          setAlert({
            msg: "لم تعدل على اي بيانات",
            show: true,
            type: "error",
            headMsg: "تنبيه!",
          });
        } else {
          navigate("/admin/users");
          setAlert({
            msg: "تم تعديل البيانات الشخصية بنجاح",
            show: true,
            type: "success",
            headMsg: "تنبيه!",
          });
        }
      });
    }
  };
  const onFinishFailed = (errorInfo) => {
    console.log("Failed:", errorInfo);
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
              message: "ادخل اسمك الجديد",
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
              message: "ادخل رقم الهاتف الجديد",
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
              message: "ادخل محافظتك الجديدة",
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
        {user.type === "patient" ||
        user.type === "doctor" ||
        user.type === "pharmacist" ||
        user.type === "assistant" ? (
          <>
            <Form.Item
              label="تاريخ الميلاد"
              name="birthDate"
              rules={[
                {
                  required: true,
                  message: "ادخل تاريخ ميلادك الجديد",
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
                  message: "ادخل جنسك الجديد",
                },
              ]}
            >
              <Input />
            </Form.Item>
          </>
        ) : <>
ا

        </>}
        {user.type === "doctor" && (
          <Form.Item
            label="ادخل تخصصك الجديد"
            name="specialist"
            rules={[
              {
                required: true,
                message: "ادخل تخصصك الجديد",
              },
            ]}
          >
            <Input />
          </Form.Item>
        )}
        {user.type === "patient" && (
          <Form.Item
            label="ارتفاعك"
            name="height"
            rules={[
              {
                required: true,
                message: "ادخل ارتفاعك الجديد",
              },
            ]}
          >
            <Input />
          </Form.Item>
        )}
        {user.type === "patient" && (
          <Form.Item
            label="وزنك"
            name="weight"
            rules={[
              {
                required: true,
                message: "ادخل وزنك الجديد",
              },
            ]}
          >
            <Input />
          </Form.Item>
        )}

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

const EditEmailAndSsd = ({ user, id, type }) => {
  const { setAlert } = useGlobalContext();
  const [editType, setEditType] = useState("email");
  const navigate = useNavigate();

  let formData = new FormData();
  const onFinish = (values) => {
    console.log("Success:", values);

    if (editType === "email") {
      formData.append("user_q", values.email);
    }
    if (editType === "ssd") {
      formData.append("user_q", values.sdd);
    }
    console.log(values.edit);
    editEmailOrSSdDetails(values.edit, type, id, formData).then((res) => {
      console.log(res);
      if (res.Status === 400) {
        setAlert({
          msg: "لم تعدل على اي بيانات",
          show: true,
          type: "error",
          headMsg: "تنبيه!",
        });
      } else {
        navigate("/admin/users");
        setAlert({
          msg: "تم تعديل الإيميل/الرقم القومي بنجاح",
          show: true,
          type: "success",
          headMsg: "تم بنجاح!",
        });
      }
    });
  };
  const onFinishFailed = (errorInfo) => {
    console.log("Failed:", errorInfo);
  };
  const onRadioChange = (e) => {
    setEditType(e.target.value);
  };
  return (
    <>
      <h4 className="py-4 text-2xl font-bold text-slate-500">
        تعديل الإيميل او الرقم القومي
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
          edit: `${editType}`,
          email: `${user.email}`,
          ssd: `${user.ssd}`,
        }}
        onFinish={onFinish}
        onFinishFailed={onFinishFailed}
        autoComplete="off"
      >
        <Form.Item label="ايميل / رقم قومي" name="edit">
          <Radio.Group onChange={onRadioChange}>
            <Radio value="email"> ايميل </Radio>
            <Radio value="ssd"> رقمي قومي </Radio>
          </Radio.Group>
        </Form.Item>
        {editType === "email" && (
          <Form.Item
            label="ادخل ايميلك الجديد"
            name="email"
            rules={[
              {
                required: true,
                message: "ادخل ايميلك الجديد",
              },
            ]}
          >
            <Input />
          </Form.Item>
        )}
        {editType === "ssd" && (
          <Form.Item
            label="ادخل رقمك القومي الجديد"
            name="ssd"
            rules={[
              {
                required: true,
                message: "ادخل رقمك القومي الجديد",
              },
            ]}
          >
            <Input />
          </Form.Item>
        )}
        {user.type === "email" && (
          <Form.Item
            label="ادخل تخصصك الجديد"
            name="specialist"
            rules={[
              {
                required: true,
                message: "ادخل اسمك الجديد",
              },
            ]}
          >
            <Input />
          </Form.Item>
        )}
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
const EditPassword = ({ user, id, type }) => {
  const { setAlert } = useGlobalContext();
  const [dataChanged, setDataChanged] = useState(false);
  const navigate = useNavigate();

  let formData = new FormData();
  const onFinish = (values) => {
    console.log("Success:", values);

    formData.append("password", values.password);
    formData.append("confirm_password", values.confirm_password);

    if (values.password !== values.confirm_password) {
      setAlert({
        msg: "كلمة المرور غير متشابهة للتأكيد",
        show: true,
        type: "error",
        headMsg: "تحذير!",
      });
      return;
    }

    editPasswordDetails(type, id, formData).then((res) => {
      console.log(res);
      if (res.Status === 400) {
        setAlert({
          msg: "لم تعدل على اي بيانات",
          show: true,
          type: "error",
          headMsg: "تنبيه!",
        });
      } else {
        navigate("/admin/users");
        setAlert({
          msg: "تم تعديل كلمةالمرور بنجاح",
          show: true,
          type: "success",
          headMsg: "تم بنجاح!",
        });
      }
    });
  };
  const onFinishFailed = (errorInfo) => {
    console.log("Failed:", errorInfo);
  };

  return (
    <>
      <h4 className="py-4 text-2xl font-bold text-slate-500">
        تعديل كلمة المرور
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
        // initialValues={{
        //   password: `${user.password}`,
        //   confirm_password: `${user.password}`,
        // }}
        onFinish={onFinish}
        onFinishFailed={onFinishFailed}
        autoComplete="off"
      >
        <Form.Item
          label="كلمة المرور الجديدة"
          name="password"
          rules={[
            {
              required: true,
              message: "اكتب كلمة المرور الجديدة",
            },
          ]}
        >
          <Input.Password />
        </Form.Item>

        <Form.Item
          label="تأكيد كلمة المرور"
          name="confirm_password"
          rules={[
            {
              required: true,
              message: "اكد كلمة المرور الجديدة",
            },
          ]}
        >
          <Input.Password />
        </Form.Item>
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
  const [loading, setLoading] = useState(false);
  const { type, id } = useParams();
  const [user, setUser] = useState({});
  useEffect(() => {
    viewUserDetails(type, id).then((res) => {
      setUser(res.Data);
    });
  }, []);
  const items = [
    {
      key: "1",
      label: `تعديل البيانات الشخصية`,
      children: <EditPersonalInfo user={user} type={type} id={id} />,
    },
    {
      key: "2",
      label: `تعديل الإيميل او الرقم القومي`,
      children: <EditEmailAndSsd user={user} type={type} id={id} />,
    },
    {
      key: "3",
      label: `تعديل الباسوورد`,
      children: <EditPassword user={user} type={type} id={id} />,
    },
  ];
  const onChange = (key) => {
    console.log(key);
  };
  if (!user.name && !user.email) {
    return (
      <>
        <MyLoader loading={loading} />
      </>
    );
  } else {
    return (
      <>
        <div className="my-2 w-[80vw]">
          <Tabs defaultActiveKey="1" items={items} onChange={onChange} />
        </div>
      </>
    );
  }
};

export default EditSingleUser;
