import { useContext, useEffect, useState } from "react";
import {
  // deleteUser,
  // getUsers,
  viewActivation,
  viewUserDetails,
} from "../../API";
import {
  Alert,
  Avatar,
  Divider,
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
import { AuthContext } from "../../../../store/auth/context";
import { deleteUser, getUsers } from "../../../../api/admin";
import ContentLayout from "../../components/ContentLayout";

const Users = () => {
  const { user } = useContext(AuthContext);
  const { setAuthUser, alert, setAlert } = useGlobalContext();
  const [loading, setLoading] = useState(false);
  const [users, setUsers] = useState([]);
  const [radioValue, setRadioValue] = useState("");
  const [switchValue, setSwitchValue] = useState(0);
  const [searchTerm, setSearchTerm] = useState("");

  const refreshTableData = (radioValue = "", searchTerm = "") => {
    setLoading(true);
    getUsers(radioValue, searchTerm, user.token).then((res) => {
      setUsers(res.Data);
      setLoading(false);
    });
  };
  const showAlert = (msg = "حدثت مشكلة", type = "error") => {
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
      <ContentLayout title="المستخدمين">
        <Space direction="vertical" className="w-full">
          <Typography.Title level={5}>نتائج البحث</Typography.Title>

          <Space
            className="rounded-lg shadow-sm bg-white px-8 py-4 w-full justify-between gap-10"
            size={3}
          >
            <Space size="large">
              <span className="text-gray-400"> النوع:</span>
              <Radio.Group onChange={onRadioChange} value={radioValue}>
                <Radio value={""}>الكل</Radio>
                // Add filter for Patient
                <Radio value={"doctor"}>دكتور</Radio>
                <Radio value={"assistant"}>مساعد</Radio>
                <Radio value={"pharmacist"}>صيدلي</Radio>
                <Radio value={"patient"}>مريض</Radio>
                {/* <Radio value={'clinic'}>عيادة</Radio> */}
                {/* <Radio value={'pharmacy'}>صيدلية</Radio> */}
              </Radio.Group>
            </Space>
            <Divider type="vertical" className="bg-gray-100 h-8" />
            <Space size="large">
              <span className="text-gray-400">ابحث عن شخص:</span>
              <Search
                className="search-input-group"
                placeholder="اكتب الإسم او الرقم القومي"
                allowClear
                enterButton="ابحث"
                size="large"
                bordered={false}
                onChange={onSearch}
              />
            </Space>
          </Space>
        </Space>

        <Table
          rowKey="id"
          className="w-[80vw]"
          columns={[
            {
              title: "الصورة",
              width: "10%",
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
              title: "الرقم القومي",
              dataIndex: "ssd",
              render: (value) => <span className="font-bold">{value}</span>,
              key: "ssd",
            },
            {
              title: "نوع الحساب",
              dataIndex: "role",
              render: (value) => <span className="font-bold">{value}</span>,
              key: "role",
            },
            {
              title: "الخيارات",
              width: "15%",
              dataIndex: ["role"],
              render: (role, userData) => {
                const id = userData.id;
                return (
                  <div className="flex items-center justify-between  ">
                    <Link to={`/admin/users/view/${role}/${id}`}>
                      <TbEye className="mx-1 cursor-pointer text-xl text-roshetta" />
                    </Link>
                    <Link to={`/admin/users/edit/${role}/${id}`}>
                      <BsFillPencilFill className="mx-1 cursor-pointer text-xl text-roshetta" />
                    </Link>
                    <FiTrash2
                      className="mx-1 cursor-pointer text-xl text-roshetta"
                      onClick={() => {
                        deleteUser(role, id, user.token).then((res) => {
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
      </ContentLayout>
    </>
  );
};

export default Users;
