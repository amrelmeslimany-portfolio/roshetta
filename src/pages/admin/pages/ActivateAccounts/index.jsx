import React, { useContext, useEffect, useState } from "react";
// import { activateUser, viewActivation } from "../../API";

import {
  Alert,
  Avatar,
  Button,
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
import { useGlobalContext } from "../../../../context";
import { motion } from "framer-motion";
import { AuthContext } from "../../../../store/auth/context";
import { activateUser, viewActivation } from "../../../../api/admin";
import { initalWindowScroll } from "../../../../utils/reusedFunctions";
import ContentLayout from "../../components/ContentLayout";
import { PrimaryButton } from "../../../../components/Buttons/Primary";

const ActivateAccounts = () => {
  const { user } = useContext(AuthContext);
  const { setAuthUser, alert, setAlert } = useGlobalContext();

  const [loading, setLoading] = useState(false);
  const [dataSource, setDataSource] = useState([]);
  const [radioValue, setRadioValue] = useState("");
  const [searchTerm, setSearchTerm] = useState("");
  const [switchValue, setSwitchValue] = useState(0);
  const refreshTableData = (
    radioValue = "",
    searchTerm = "",
    switchValue = ""
  ) => {
    setLoading(true);
    viewActivation(radioValue, searchTerm, switchValue, user.token).then(
      (res) => {
        setDataSource(res.Data);
        setLoading(false);
      }
    );
  };
  const showAlert = (msg = "حدثت مشكلة", type = "error") => {
    initalWindowScroll();
    setAlert({
      msg: msg,
      show: true,
      type: type,
    });
  };
  const onChange = (e) => {
    setLoading(true);
    if (e === false) {
      setSwitchValue(0);
      refreshTableData(radioValue, searchTerm, 0);
    }
    if (e === true) {
      setSwitchValue(1);
      refreshTableData(radioValue, searchTerm, 1);
    }
  };
  const onRadioChange = (e) => {
    setRadioValue(e.target.value);
    refreshTableData(e.target.value, searchTerm, switchValue);
  };

  const onSearch = (e) => {
    setSearchTerm(e.target.value);
    refreshTableData(radioValue, e.target.value, switchValue);
  };

  useEffect(() => {
    refreshTableData(radioValue, searchTerm, switchValue);
  }, []);

  return (
    <>
      <ContentLayout title="تفعيل الحسابات">
        <Space direction="vertical" className="w-full ">
          <Typography.Title level={5}>فلترة النتائج</Typography.Title>
          <Space
            className="rounded-lg shadow-sm bg-white px-2 py-4 w-full justify-between gap-10"
            size={3}
          >
            <Space size="large">
              <span className="text-gray-400">مفعل / غير مفعل</span>
              <Switch onChange={onChange} className="bg-gray-100" />
            </Space>
            <Divider type="vertical" className="bg-gray-100 h-8" />
            <Space size="large">
              <span className="text-gray-400"> النوع:</span>
              <Radio.Group onChange={onRadioChange} value={radioValue}>
                <Radio value={""}>الكل</Radio>
                <Radio value={"doctor"}>دكتور</Radio>
                <Radio value={"pharmacist"}>صيدلي</Radio>
                <Radio value={"clinic"}>عيادة</Radio>
                <Radio value={"pharmacy"}>صيدلية</Radio>
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
          rowKey="activation_id"
          columns={[
            {
              title: "الصورة الشخصية",
              dataIndex: "profile_img",
              render: (link) => {
                return <Avatar src={link} />;
              },
            },
            { title: "الإسم", dataIndex: "name" },
            {
              title: "الرقم القومي",
              dataIndex: "ssd",
              render: (value) => (
                <span className="font-bold">{value ? value : "لايوجد"}</span>
              ),
            },
            // {
            //   title: 'Rating',
            //   dataIndex: 'rating',
            //   render: (rating) => {
            //     return <Rate value={rating} allowHalf />;
            //   },
            // },
            { title: "رقم المستخدم", dataIndex: "user_id", key: "user_id" },
            {
              title: "حالة التفعيل",
              dataIndex: "status",
              key: "status",
              render: () => (
                <>
                  {switchValue ? (
                    <span className="p-1 text-slate-500">مفعل</span>
                  ) : (
                    <span className="p-1 text-slate-500">غير مفعل</span>
                  )}
                </>
              ),
            },
            {
              title: "رقم التفعيل",
              dataIndex: "activation_id",
              key: "activation_id",
            },
            {
              title: "نوع الحساب",
              dataIndex: "type",
              key: "type",
            },
            {
              title: "تفعيل",
              dataIndex: ["type"],
              key: "x",
              render: (type, userData) => {
                return (
                  <Space>
                    <PrimaryButton
                      onClick={() => {
                        setLoading(true);
                        activateUser(
                          type,
                          userData.activation_id,
                          1,
                          user.token
                        ).then((res) => {
                          if (res.Message === "الحساب مفعل بالفعل") {
                            showAlert(res.Message, "warning");
                          } else {
                            showAlert(res.Message, "success");
                          }
                          refreshTableData();
                          setLoading(false);
                        });
                      }}
                    >
                      تنشيط
                    </PrimaryButton>
                    <Button
                      shape="round"
                      onClick={() => {
                        setLoading(true);
                        type, userData.name, userData.activation_id;
                        activateUser(
                          type,
                          userData.activation_id,
                          -1,
                          user.token
                        ).then((res) => {
                          showAlert(res.Message, "success");
                          refreshTableData();
                          setLoading(false);
                        });
                      }}
                      className=""
                    >
                      رفض
                    </Button>
                  </Space>
                );
              },
            },
          ]}
          dataSource={dataSource}
          loading={loading}
          pagination={{ pageSize: 5 }}
        ></Table>
      </ContentLayout>
    </>
  );
};

export default ActivateAccounts;
