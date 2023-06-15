import React, { useContext, useState } from "react";
import {
  Card,
  Result,
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
const cardStyles = {
  color: "#49ce91",
  backgroundColor: "rgba(0,255,0,0.15)",
  borderRadius: 20,
  fontSize: 40,
  padding: 8,
  zIndex: 99,
};

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
    <Space size={20} direction="vertical">
      <h2 className="p-4 text-4xl font-bold text-roshetta">الصفحة الرئيسية</h2>

      {/* <Space direction="horizontal">
          <DashboardCard
            icon={<GiPlayerTime style={cardStyles} />}
            title={'نشط-المرضى'}
            value={patient.active_now}
          />
          <DashboardCard
            icon={<MdLocalPharmacy style={cardStyles} />}
            title={'نشط-الدكاترة'}
            value={doctor.active_now}
          />
          <DashboardCard
            icon={<MdOutlineLocalPharmacy style={cardStyles} />}
            title={'نشط-الصيدليات '}
            value={pharmacy.active_now}
          />
          <DashboardCard
            icon={<TbCurrencyDollarCanadian style={cardStyles} />}
            title={'نشط-العيادات'}
            value={clinic.active_now}
          />
          <DashboardCard
            icon={<TbCurrencyDollarCanadian style={cardStyles} />}
            title={'نشط-الادمنز'}
            value={admin.active_now}
          />
          <DashboardCard
            icon={<TbCurrencyDollarCanadian style={cardStyles} />}
            title={'نشط-المساعدين'}
            value={clinic.active_now}
          />
          <DashboardCard
            icon={<TbCurrencyDollarCanadian style={cardStyles} />}
            title={'نشط-الصيادلة'}
            value={pharmacist.active_now}
          />
        </Space> */}

      <Space direction="horizontal">
        <DashboardCard
          icon={<MdOutlineSick style={cardStyles} />}
          title={"المرضى"}
          value={patient.all}
        />
        <DashboardCard
          icon={<FaUserNurse style={cardStyles} />}
          title={"الدكاترة"}
          value={doctor.all}
        />
        <DashboardCard
          icon={<RiNurseFill style={cardStyles} />}
          title={"المساعدين"}
          value={assistant.all}
        />
        <DashboardCard
          icon={<MdOutlineLocalPharmacy style={cardStyles} />}
          title={"الصيدليات "}
          value={pharmacy.all}
        />
      </Space>
      <Space direction="horizontal">
        <DashboardCard
          icon={<RiAdminLine style={cardStyles} />}
          title={"الادمنز"}
          value={admin.all}
        />
        <DashboardCard
          icon={<GiNurseMale style={cardStyles} />}
          title={"الصيادلة"}
          value={pharmacist.all}
        />
        <DashboardCard
          icon={<FaPrescriptionBottleAlt style={cardStyles} />}
          title={"الروشتات"}
          value={prescript}
        />
        <DashboardCard
          icon={<BiClinic style={cardStyles} />}
          title={"العيادات"}
          value={clinic.all}
        />
      </Space>
      <Space>
        <RecentOrders />
        <DashboardChart />
      </Space>
    </Space>
  );
};

const DashboardCard = ({ title, value, icon }) => {
  return (
    <>
      <div className="h-28 w-28 rounded-lg bg-gray-200 p-2 lg:w-36">
        {icon} <h3 className="text-md font-bold text-black">{title}</h3>
        <p className="font-extrabold text-gray-700">{value}</p>
      </div>
    </>
  );
  return (
    <Card>
      <Space direction="horizontal">
        {icon}
        <Statistic title={title} value={value} />
      </Space>
    </Card>
  );
};
const RecentOrders = () => {
  const { user } = useContext(AuthContext);
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
  }, []);

  return (
    <>
      <h3>رسائل المرضى</h3>
      <Table
        columns={[
          { title: "Name", dataIndex: "name" },
          { title: "Email", dataIndex: "email" },
          { title: "Message", dataIndex: "message" },
        ]}
        loading={loading}
        dataSource={dataSource}
        rowKey={"email"}
        pagination={false}
      ></Table>
    </>
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
      title: {
        display: true,
        text: "احصائيات المرضى",
      },
    },
  };

  return (
    <Card style={{ width: 500, height: 250 }}>
      {" "}
      <Bar options={options} data={revenueData} />
    </Card>
  );
};

export default Dashboard;
