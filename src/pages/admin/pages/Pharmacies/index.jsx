import { useEffect, useState } from "react";
import {
  deleteUser,
  getInventory,
  getUsers,
  viewActivation,
  viewUserDetails,
} from "../../API";
import {
  Alert,
  Avatar,
  Radio,
  Rate,
  Space,
  Switch,
  Table,
  Typography,
} from "antd";
import { Input } from "antd";
const { Search } = Input;
import { TbEye } from "react-icons/tb";
import { BsFillPencilFill } from "react-icons/bs";
import { FiTrash2 } from "react-icons/fi";
import { useGlobalContext } from "../../../../context";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";

const Pharmacies = () => {
  const { setAuthUser, alert, setAlert } = useGlobalContext();
  const [loading, setLoading] = useState(false);
  const [users, setUsers] = useState([]);
  const [radioValue, setRadioValue] = useState("pharmacy");
  const [searchTerm, setSearchTerm] = useState("");

  const refreshTableData = (radioValue = "", searchTerm = "") => {
    setLoading(true);
    getUsers(radioValue, searchTerm).then((res) => {
      setUsers(res.Data);
      setLoading(false);
    });
  };
  const showAlert = (msg = "حدثت مشكلة", type = "error") => {
    window.scrollTo({
      top: 0,
      left: 0,
      behavior: "smooth",
    });
    setAlert({
      msg: msg,
      show: true,
      type: type,
    });
  };
  const onRadioChange = (e) => {
    setRadioValue(e.target.value);
    refreshTableData(e.target.value, searchTerm);
  };

  const onSearch = (e) => {
    setSearchTerm(e.target.value);
    refreshTableData(radioValue, e.target.value);
  };

  useEffect(() => {
    refreshTableData(radioValue, searchTerm);
  }, []);

  return (
    <>
      <Space direction="vertical" size={20}>
        <h2 className="p-4 text-4xl font-bold text-roshetta">الصيدليات</h2>
        <h4 className="px-4 text-2xl font-bold text-black">فلترة النتائج</h4>
        <Space className="rounded-lg bg-gray-200 p-4" size={20}>
          {/* <span>مفعل / غير مفعل</span>
          <Switch onChange={onChange} /> */}
          <span>اختار النوع:</span>
          <Radio.Group onChange={onRadioChange} value={radioValue}>
            {/* <Radio value={''}>الكل</Radio> */}
            {/* <Radio value={'doctor'}>دكتور</Radio>
            <Radio value={'pharmacist'}>صيدلي</Radio> */}
            <Radio value={"pharmacy"}>صيدلية</Radio>
            <Radio value={"clinic"}>عيادة</Radio>
          </Radio.Group>
          <span>ابحث عن صيدلية:</span>
          <Search
            placeholder="اكتب الإسم"
            allowClear
            enterButton="ابحث"
            size="large"
            onChange={onSearch}
          />
        </Space>
        <Table
          className="w-[80vw]"
          columns={[
            {
              title: "الصورة",
              dataIndex: "profile_img",
              render: (link) => {
                return <Avatar src={link} />;
              },
              key: "image",
            },
            {
              title: "الإسم",
              dataIndex: "name",
              key: "name",
            },
            {
              title: "رقم الخدمة",
              dataIndex: "ser_id",
              render: (value) => <span className="font-bold">{value}</span>,
              key: "ssd",
            },
            {
              title: "الخيارات",
              dataIndex: ["type"],
              render: (type, userData) => {
                const id = userData.id;
                return (
                  <div className="flex items-center justify-center  ">
                    <Link to={`/admin/users/view/${type}/${id}`}>
                      <TbEye className="mx-1 cursor-pointer text-xl text-roshetta" />
                    </Link>
                    <Link to={`/admin/users/edit/${type}/${id}`}>
                      <BsFillPencilFill className="mx-1 cursor-pointer text-xl text-roshetta" />
                    </Link>
                    <FiTrash2
                      className="mx-1 cursor-pointer text-xl text-roshetta"
                      onClick={() => {
                        deleteUser(type, id).then((res) => {
                          showAlert(res.Message, "success");
                          refreshTableData(radioValue, searchTerm);
                        });
                      }}
                    />
                  </div>
                );
              },
              key: "options",
            },
          ]}
          dataSource={users}
          loading={loading}
          pagination={{ pageSize: 5 }}
        ></Table>
      </Space>
    </>
  );
};

export default Pharmacies;
