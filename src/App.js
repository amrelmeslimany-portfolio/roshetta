import { BrowserRouter, Route, Routes } from 'react-router-dom';

// import ProtectedRoute from './pages/ProtectedRoute';
import Layout from './pages/Layout';
import { DoctorHome, AuthLogin, AuthRegister } from './pages';
import './App.scss';

// Created devm7md @2023 All rights reserved

export default function App() {
  return (
    <>
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Layout />}>
            <Route
              index
              element={
                <>
                  <h2>Hello, World!</h2>
                </>
              }
            />
            <Route path="/doctor-home" element={<DoctorHome />} />
            <Route path="/login" element={<AuthLogin />} />
            <Route path="/register" element={<AuthRegister />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </>
  );
}
// <div className="App">
//   <h1>تطبيق روشته</h1>
//   <p>
//     التطبيق بالعربي <br /> هتشتغلو بمكتبه <b>antd</b> حاجه زي البوتستراب كد
//     بس فيها المكونات كلها <br /> هتشتغلو بخط اسمه <b>Cairo</b>
//   </p>
//   <ul>
//     <li>
//       <h2> محمد الحسن ابو الهيشام</h2>
//       <ul>
//         <li>صفحات تسجيل الدخول (pages/auth/)</li>
//         <li>صفحات الادمن (pages/admin)</li>
//         <li>صفحات  الدكتور (pages/doctor)</li>
//       </ul>
//     </li>
//     <li>
//       <h2>محمد عثمان</h2>
//       <ul>
//         <li>صفحات المريض (pages/patient/)</li>
//         <li>صفحات الصيدلي (pages/pharmcist)</li>
//         <li>صفحات المساعد (pages/assistant)</li>
//       </ul>
//     </li>
//   </ul>
// </div>
