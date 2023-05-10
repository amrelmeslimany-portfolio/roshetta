import React, { useState } from 'react';
import { Card, Space, Statistic, Table, Typography } from 'antd';
import { ShoppingCartOutlined } from '@ant-design/icons';
import { TbCurrencyDollarCanadian } from 'react-icons/tb';
import { MdLocalPharmacy, MdOutlineLocalPharmacy } from 'react-icons/md';
import { GiPlayerTime } from 'react-icons/gi';
import { getOrders, getRevenue, viewRoshettaNumbers } from '../../API';
import { useEffect } from 'react';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';
// import faker from 'faker';

import { Bar } from 'react-chartjs-2';
import { MyLoader } from '../../../../components';
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);
const cardStyles = {
  color: '#49ce91',
  backgroundColor: 'rgba(0,255,0,0.25)',
  borderRadius: 20,
  fontSize: 40,
  padding: 8,
  zIndex: 99,
};
const Dashboard = () => {
  const [loading, setLoading] = useState(false);
  const [admin, setAdmin] = useState({ all: 0, active_now: 0 });
  const [doctor, setDoctor] = useState({ all: 0, active_now: 0 });
  const [assistant, setAssistant] = useState({ all: 0, active_now: 0 });
  const [pharmacy, setPharmacy] = useState({ all: 0, active_now: 0 });
  const [pharmacist, setPharmacist] = useState({ all: 0, active_now: 0 });
  const [patient, setPatient] = useState({ all: 0, active_now: 0 });
  const [clinic, setClinic] = useState({ all: 0, active_now: 0 });
  const [prescript, setPrescript] = useState(0);

  useEffect(() => {
    setLoading(true);
    viewRoshettaNumbers().then((res) => {
      console.log(res.Data);
      setAdmin(res.Data.admin);
      setAssistant(res.Data.assistant);
      setClinic(res.Data.clinic);
      setDoctor(res.Data.doctor);
      setPatient(res.Data.patient);
      setPharmacist(res.Data.pharmacist);
      setPharmacy(res.Data.pharmacy);
      setPrescript(res.Data.prescript);
    });
  }, []);
  if (!patient && !doctor) {
    return <MyLoader loading={loading} />;
  } else {
    return (
      <Space size={20} direction="vertical">
        <h2 className="p-4 text-4xl font-bold text-roshetta">
          الصفحة الرئيسية
        </h2>

        <Space direction="horizontal">
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
        </Space>

        <Space direction="horizontal">
          <DashboardCard
            icon={<GiPlayerTime style={cardStyles} />}
            title={'المرضى'}
            value={patient.all}
          />
          <DashboardCard
            icon={<MdLocalPharmacy style={cardStyles} />}
            title={'الدكاترة'}
            value={doctor.all}
          />
          <DashboardCard
            icon={<MdOutlineLocalPharmacy style={cardStyles} />}
            title={'الصيدليات '}
            value={pharmacy.all}
          />
          <DashboardCard
            icon={<TbCurrencyDollarCanadian style={cardStyles} />}
            title={'العيادات'}
            value={clinic.all}
          />
          <DashboardCard
            icon={<TbCurrencyDollarCanadian style={cardStyles} />}
            title={'الادمنز'}
            value={admin.all}
          />
          <DashboardCard
            icon={<TbCurrencyDollarCanadian style={cardStyles} />}
            title={'المساعدين'}
            value={clinic.all}
          />
          <DashboardCard
            icon={<TbCurrencyDollarCanadian style={cardStyles} />}
            title={'الصيادلة'}
            value={pharmacist.all}
          />
          <DashboardCard
            icon={<TbCurrencyDollarCanadian style={cardStyles} />}
            title={'الروشتات'}
            value={prescript}
          />
        </Space>
        <Space>
          <RecentOrders />
          <DashboardChart />
        </Space>
      </Space>
    );
  }
};

const DashboardCard = ({ title, value, icon }) => {
  return (
    <>
      <div className="h-28 w-28 rounded-lg bg-gray-200 p-2 lg:w-36">
        {icon} <h3 className="text-md font-bold text-slate-700">{title}</h3>
        <p className="font-extrabold text-slate-600">{value}</p>
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
  const [dataSource, setDataSource] = useState([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    setLoading(true);
    getOrders().then((res) => {
      setDataSource(res.products.splice(0, 3));
      setLoading(false);
    });
  }, []);

  return (
    <>
      <h3>رسائل المرضى</h3>
      <Table
        columns={[
          { title: 'title', dataIndex: 'title' },
          { title: 'Quantity', dataIndex: 'quantity' },
          { title: 'Price', dataIndex: 'discountedPrice' },
        ]}
        loading={loading}
        dataSource={dataSource}
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
            label: 'المريض المتسلخ',
            data: data,
            backgroundColor: '#49ce91',
          },

          {
            label: 'المريض الكحيان ههه',
            data: data,
            backgroundColor: 'rgba(0,255,0,0.25)',
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
        position: 'bottom',
      },
      title: {
        display: true,
        text: 'احصائيات المرضى',
      },
    },
  };

  return (
    <Card style={{ width: 500, height: 250 }}>
      {' '}
      <Bar options={options} data={revenueData} />
    </Card>
  );
};

export default Dashboard;
