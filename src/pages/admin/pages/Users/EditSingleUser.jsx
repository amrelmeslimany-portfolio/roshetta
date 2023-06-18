import React, { useContext, useEffect, useState } from "react";
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
// import {
//   editEmailOrSSdDetails,
//   editProfileDetails,
//   editProfilePlaceDetails,
//   viewUserDetails,
// } from "../../API";
import { MyLoader } from "../../../../components";
import { useGlobalContext } from "../../../../context";
import ShowAlert from "../../../../components/ShowAlert";
import { displayAlert } from "../../../../utils/displayAlert";
import { editPasswordDetails } from "../../API";
import SubmitButton from "../../../../components/SubmitButton";
import { AuthContext } from "../../../../store/auth/context";
import {
  editEmailOrSSdDetails,
  editProfileDetails,
  editProfilePlaceDetails,
  viewUserDetails,
} from "../../../../api/admin";

const dateFormat = "YYYY/MM/DD";
const EditPersonalInfo = ({ user, id, type }) => {
  const { user: auth } = useContext(AuthContext);
  const { setAlert } = useGlobalContext();
  const [dataChanged, setDataChanged] = useState(false);
  const navigate = useNavigate();

  let formData = new FormData();
  const onFinish = (values) => {
    console.log("Success:", values);

    formData.append("name", values.name);
    formData.append("phone_number", values.phone_number);
    formData.append("governorate", values.governorate);
    formData.append("start_working", values.start_working);
    formData.append("end_working", values.end_working);
    formData.append("address", values.address);
    formData.append("owner", values.owner);
    formData.append("specialist", values.specialist);
    formData.append("price", values.price);
    if (type === "pharmacy" || type === "clinic") {
      editProfilePlaceDetails(type, id, formData, auth.token).then((res) => {
        console.log(res);
        if (res.Status === 400) {
          setAlert({
            msg: "لم تعدل على اي بيانات",
            show: true,
            type: "error",
            headMsg: "تنبيه!",
          });
        } else {
          if (type === "pharmacy") {
            navigate("/admin/pharmacies");
            setAlert({
              msg: "تم تعديل بيانات الصيدلية بنجاح",
              show: true,
              type: "success",
              headMsg: "تنبيه!",
            });
          } else {
            navigate("/admin/clinics");
            setAlert({
              msg: "تم تعديل بيانات العيادة بنجاح",
              show: true,
              type: "success",
              headMsg: "تنبيه!",
            });
          }
        }
      });
    } else {
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
      editProfileDetails(type, id, formData, auth.token).then((res) => {
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
        تعديل العيادة / الصيدلية
      </h4>
      {console.log(user)}

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
            <SubmitButton />
          </Form>
        </>
      ) : (
        <>
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
              name: `${user.name}`,
              phone_number: `${user.phone_number}`,
              governorate: `${user.governorate}`,
              start_working: `${user.start_working}`,
              end_working: `${user.end_working}`,
              address: `${user.address}`,
              owner: `${user.owner}`,
              specialist: `${user.specialist}`,
              price: `${user.price}`,
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
                  message: "ادخل الإسم الجديد",
                },
              ]}
            >
              <Input />
            </Form.Item>
            <Form.Item
              label="رقم الهاتف"
              name="phone_number"
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
                  message: "ادخل المحافظة الجديدة",
                },
              ]}
            >
              <Input />
            </Form.Item>
            <Form.Item
              label="وقت بدء العمل"
              name="start_working"
              rules={[
                {
                  required: true,
                  message: "ادخل وقت بدء العمل الجديد",
                },
              ]}
            >
              <Input />
            </Form.Item>
            <Form.Item
              label="وقت إنتهاء العمل"
              name="end_working"
              rules={[
                {
                  required: true,
                  message: "ادخل وقت إنتهاء العمل الجديد",
                },
              ]}
            >
              <Input />
            </Form.Item>
            <Form.Item
              label="العنوان"
              name="address"
              rules={[
                {
                  required: true,
                  message: "ادخل العنوان الجديد",
                },
              ]}
            >
              <Input />
            </Form.Item>
            <Form.Item
              label="المالك"
              name="owner"
              rules={[
                {
                  required: true,
                  message: "ادخل المالك الجديد",
                },
              ]}
            >
              <Input />
            </Form.Item>
            {type === "clinic" && (
              <>
                <Form.Item
                  label="التخصص"
                  name="specialist"
                  rules={[
                    {
                      required: true,
                      message: "ادخل التخصص الجديد",
                    },
                  ]}
                >
                  <Input />
                </Form.Item>
                <Form.Item
                  label="السعر"
                  name="price"
                  rules={[
                    {
                      required: true,
                      message: "ادخل السعر الجديد",
                    },
                  ]}
                >
                  <Input />
                </Form.Item>
              </>
            )}
            <SubmitButton />
          </Form>
        </>
      )}
    </>
  );
};

const EditEmailAndSsd = ({ user, id, type }) => {
  const { user: auth } = useContext(AuthContext);
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
    editEmailOrSSdDetails(values.edit, type, id, formData, auth.token).then(
      (res) => {
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
      }
    );
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
  const { user: auth } = useContext(AuthContext);
  const [loading, setLoading] = useState(false);
  const { type, id } = useParams();
  const [user, setUser] = useState({});
  useEffect(() => {
    viewUserDetails(type, id, auth.token).then((res) => {
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
    if (type === "pharmacy" || type === "clinic") {
      return <EditPersonalInfo user={user} type={type} id={id} />;
    } else {
      return (
        <>
          <div className="my-2 w-[80vw]">
            <Tabs defaultActiveKey="1" items={items} onChange={onChange} />
          </div>
        </>
      );
    }
  }
};

export default EditSingleUser;
