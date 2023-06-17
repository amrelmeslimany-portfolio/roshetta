import React, { useContext, useState } from "react";
import {
  Card,
  Col,
  Result,
  Row,
  Space,
  Statistic,
  Table,
  Typography,
  message,
} from "antd";
import { ShoppingCartOutlined } from "@ant-design/icons";
import { TbCurrencyDollarCanadian } from "react-icons/tb";
import {
  MdLocalPharmacy,
  MdOutlineLocalPharmacy,
  MdOutlineSick,
} from "react-icons/md";
import { GiNurseMale, GiPlayerTime } from "react-icons/gi";
import { RiAdminLine, RiNurseFill } from "react-icons/ri";
import { GrUserAdmin } from "react-icons/gr";

import {
  getOrders,
  getRevenue,
  // viewMessage,
  // viewRoshettaNumbers,
} from "../../API";
import { useEffect } from "react";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";
// import faker from 'faker';

import { Bar } from "react-chartjs-2";
import { MyLoader } from "../../../../components";
import { FaPrescriptionBottleAlt, FaUserNurse } from "react-icons/fa";
import { BiClinic } from "react-icons/bi";
import {
  errorToString,
  isRequestSuccess,
} from "../../../../utils/reusedFunctions";
import { viewMessage, viewRoshettaNumbers } from "../../../../api/admin";
import { AuthContext } from "../../../../store/auth/context";
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);

import "./Dashboard.scss";
import ContentLayout from "../../components/ContentLayout";

const INITIAL_STATE_USERS = { all: 0, active_now: 0 };

const Dashboard = () => {
  const { user } = useContext(AuthContext);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [admin, setAdmin] = useState(INITIAL_STATE_USERS);
  const [doctor, setDoctor] = useState(INITIAL_STATE_USERS);
  const [assistant, setAssistant] = useState(INITIAL_STATE_USERS);
  const [pharmacy, setPharmacy] = useState(INITIAL_STATE_USERS);
  const [pharmacist, setPharmacist] = useState(INITIAL_STATE_USERS);
  const [patient, setPatient] = useState(INITIAL_STATE_USERS);
  const [clinic, setClinic] = useState(INITIAL_STATE_USERS);
  const [prescript, setPrescript] = useState(0);

  useEffect(() => {
    const getNumbers = async () => {
      try {
        setLoading(true);
        const response = await viewRoshettaNumbers(user.token);
        console.log(user.token);
        // COMMENT If Sucess
        if (isRequestSuccess(response.Status)) {
          setAdmin(response.Data.admin);
          setAssistant(response.Data.assistant);
          setClinic(response.Data.clinic);
          setDoctor(response.Data.doctor);
          setPatient(response.Data.patient);
          setPharmacist(response.Data.pharmacist);
          setPharmacy(response.Data.pharmacy);
          setPrescript(response.Data.prescript);
        }
        // COMMENT if Error
        else throw new Error(errorToString(response.Message));
      } catch (error) {
        setError(error.message);
      } finally {
        setLoading(false);
      }
    };

    getNumbers();

    // viewRoshettaNumbers().then((res) => {
    //   setAdmin(res.Data.admin);
    //   setAssistant(res.Data.assistant);
    //   setClinic(res.Data.clinic);
    //   setDoctor(res.Data.doctor);
    //   setPatient(res.Data.patient);
    //   setPharmacist(res.Data.pharmacist);
    //   setPharmacy(res.Data.pharmacy);
    //   setPrescript(res.Data.prescript);
    // });
  }, []);

  if (loading) {
    return <MyLoader loading={loading} />;
  }

  if (!loading && error) {
    return <Result status="error" subTitle={error} />;
  }

  return (
    <ContentLayout title="الصفحة الرئيسية">
      <Space direction="vertical" style={{ width: "100%" }}>
        <Typography.Title level={5}>احصائية عامة</Typography.Title>
        <Row gutter={[15, 15]} className="static-grid-cards">
          <Col span={6}>
            <Card>
              <Statistic
                prefix={<MdOutlineSick />}
                title={"المرضى"}
                value={patient.all}
              />
            </Card>
          </Col>
          <Col span={6}>
            <Card>
              <Statistic
                prefix={<FaUserNurse />}
                title={"الدكاترة"}
                value={doctor.all}
              />
            </Card>
          </Col>
          <Col span={6}>
            <Card>
              <Statistic
                prefix={<RiNurseFill />}
                title={"المساعدين"}
                value={assistant.all}
              />
            </Card>
          </Col>
          <Col span={6}>
            <Card>
              <Statistic
                prefix={<MdOutlineLocalPharmacy />}
                title={"الصيدليات "}
                value={pharmacy.all}
              />
            </Card>
          </Col>
          <Col span={6}>
            <Card>
              <Statistic
                prefix={<RiAdminLine />}
                title={"الادمنز"}
                value={admin.all}
              />
            </Card>
          </Col>
          <Col span={6}>
            <Card>
              <Statistic
                prefix={<GiNurseMale />}
                title={"الصيادلة"}
                value={pharmacist.all}
              />
            </Card>
          </Col>
          <Col span={6}>
            <Card>
              <Statistic
                prefix={<FaPrescriptionBottleAlt />}
                title={"الروشتات"}
                value={prescript}
              />
            </Card>
          </Col>
          <Col span={6}>
            <Card>
              <Statistic
                prefix={<BiClinic />}
                title={"العيادات"}
                value={clinic.all}
              />
            </Card>
          </Col>
        </Row>
      </Space>
      <Row gutter={15}>
        <Col span={12}>
          <RecentOrders user={user} />
        </Col>
        <Col span={12}>
          <DashboardChart />
        </Col>
      </Row>
    </ContentLayout>
  );
};

const RecentOrders = ({ user }) => {
  const [dataSource, setDataSource] = useState([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    setLoading(true);
    const messageRequest = async () => {
      const response = await viewMessage("", "", user.token);
      if (isRequestSuccess(response.Status)) {
        setDataSource(response.Data);
        console.log(response.Data);
        // console.log(res.Data.splice(0, 3));
        setLoading(false);
      }
    };
    messageRequest();
  }, [user]);

  return (
    <Space direction="vertical">
      <Typography.Title level={5}>رسائل المرضي</Typography.Title>
      <Table
        columns={[
          { title: "الاسم", dataIndex: "name" },
          { title: "الايميل", dataIndex: "email" },
          { title: "الرسالة", dataIndex: "message" },
        ]}
        loading={loading}
        dataSource={dataSource}
        rowKey={"email"}
        pagination={false}
      ></Table>
    </Space>
  );
};

const DashboardChart = () => {
  const [revenueData, setRevenueData] = useState({
    labels: [],
    datasets: [],
  });
  useEffect(() => {
    getRevenue().then((res) => {
      const labels = res.carts.map((cart) => {
        return `User-${cart.userId}`;
      });

      const data = res.carts.map((cart) => {
        return cart.discountedTotal;
      });

      const dataSource = {
        labels,
        datasets: [
          {
            label: "المريض المتسلخ",
            data: data,
            backgroundColor: "#49ce91",
          },

          {
            label: "المريض الكحيان ههه",
            data: data,
            backgroundColor: "rgba(0,255,0,0.25)",
          },
        ],
      };
      setRevenueData(dataSource);
    });
  }, []);

  const options = {
    responsive: true,
    plugins: {
      legend: {
        position: "bottom",
      },
    },
  };

  return (
    <Space direction="vertical" style={{ width: "100%" }}>
      <Typography.Title level={5}>احصائية المرضى</Typography.Title>
      <Card>
        <Bar options={options} data={revenueData} />
      </Card>
    </Space>
  );
};

export default Dashboard;
