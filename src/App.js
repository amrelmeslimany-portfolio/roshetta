import { BrowserRouter, Route, Routes } from 'react-router-dom';

import ProtectedRoute from './pages/ProtectedRoute';
import Layout from './pages/Layout';
import {
  DoctorHome,
  AuthLogin,
  AuthRegister,
  HomePage,
  ForgetPassword,
  Home,
} from './pages';
import './App.scss';

export default function App() {
  return (
    <>
      <div className="app">
        <BrowserRouter>
          <Routes>
            <Route index path="/" element={<HomePage />} />
            <Route path="/login" element={<AuthLogin />} />
            <Route path="/register" element={<AuthRegister />} />
            <Route path="/forget-password" element={<ForgetPassword />} />
            <Route
              path="/admin-home"
              element={
                // <ProtectedRoute>
                  <Home />
                /* </ProtectedRoute> */
              }
            />
            <Route element={<Layout />}>
              <Route
                element={
                  <>
                    <h2>Hello, World!</h2>
                  </>
                }
              />
              <Route
                path="/doctor-home"
                element={
                  <ProtectedRoute>
                    <DoctorHome />
                  </ProtectedRoute>
                }
              />
            </Route>
          </Routes>
        </BrowserRouter>
      </div>
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
