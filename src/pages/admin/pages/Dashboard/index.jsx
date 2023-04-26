import React, { useState } from 'react';
import { Card, Space, Statistic, Table, Typography } from 'antd';
import { ShoppingCartOutlined } from '@ant-design/icons';
import { TbCurrencyDollarCanadian } from 'react-icons/tb';
import { MdLocalPharmacy, MdOutlineLocalPharmacy } from 'react-icons/md';
import { GiPlayerTime } from 'react-icons/gi';
import { getOrders, getRevenue } from '../../API';
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
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);
const Dashboard = () => {
  return (
    <Space size={20} direction="vertical">
      <h2 className="text-3xl p-2">صفحة التحكم</h2>

      <Space direction="horizontal">
        <DashboardCard
          icon={
            <GiPlayerTime
              style={{
                color: '#49ce91',
                backgroundColor: 'rgba(0,255,0,0.25)',
                borderRadius: 20,
                fontSize: 40,
                padding: 8,
              }}
            />
          }
          title={'المرضى'}
          value={12345}
        />
        <DashboardCard
          icon={
            <MdLocalPharmacy
              style={{
                color: '#49ce91',
                backgroundColor: 'rgba(0,255,0,0.25)',
                borderRadius: 20,
                fontSize: 40,
                padding: 8,
              }}
            />
          }
          title={'العيادات'}
          value={12345}
        />
        <DashboardCard
          icon={
            <MdOutlineLocalPharmacy
              style={{
                color: '#49ce91',
                backgroundColor: 'rgba(0,255,0,0.25)',
                borderRadius: 20,
                fontSize: 40,
                padding: 8,
              }}
            />
          }
          title={'الصيدليات المتاحة'}
          value={12345}
        />
        <DashboardCard
          icon={
            <TbCurrencyDollarCanadian
              style={{
                color: '#49ce91',
                backgroundColor: 'rgba(0,255,0,0.25)',
                borderRadius: 20,
                fontSize: 40,
                padding: 8,
              }}
            />
          }
          title={'المرضى الكليين'}
          value={12345}
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
      res.products;
      setDataSource(res.products.splice(0, 3));
      setLoading(false);
    });
  }, []);

  return (
    <>
      <h3>المرضى</h3>
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
