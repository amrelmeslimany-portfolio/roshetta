import { BrowserRouter, Route, Routes } from "react-router-dom";

import ProtectedRoute from "./pages/ProtectedRoute";
import Layout from "./pages/Layout";
import {
  Doctor,
  ActivateAccountDoc,
  AddClinic,
  PersonalData,
  ViewClinics,
  AuthLogin,
  AuthRegister,
  ActiveEmail,
  HomePage,
  ForgetPassword,
  Home,
  ActivateAccounts,
  AddAdmin,
  Clinics,
  Dashboard,
  EditInfo,
  Pharmacies,
  Users,
  ViewSingleUser,
  EditSingleUser,
} from "./pages";
import "./App.scss";

export default function App() {
  return (
    <>
      <div className="app">
        <BrowserRouter>
          <Routes>
            <Route index path="/" element={<HomePage />} />
            <Route path="/login" element={<AuthLogin />} />
            <Route path="/active-email" element={<ActiveEmail />} />
            <Route path="/register" element={<AuthRegister />} />
            <Route path="/forget-password" element={<ForgetPassword />} />
            {/* ------------------- Admin   ------------------- */}
            {/* BUG Dont fogot remove this comments on ProtectedRoute  */}
            <Route
              path="admin"
              element={
                // <ProtectedRoute>
                <Home />
                /* </ProtectedRoute> */
              }
            >
              <Route path="dashboard" element={<Dashboard />} />
              <Route
                path="activate-accounts"
                element={
                  // <ProtectedRoute>
                  <ActivateAccounts />
                  // </ProtectedRoute>
                }
              />
              {/* <Route path="add-admin" element={<AddAdmin />} /> */}
              <Route path="edit-info" element={<EditInfo />} />
              <Route path="pharmacies" element={<Pharmacies />} />
              {/* <Route path="pharmacies/view/:type/:id" element={<ViewSinglePharmacy />} />
              <Route path="pharmacies/edit/:type/:id" element={<EditSinglePharmacy />} /> */}

              <Route path="clinics" element={<Clinics />} />
              {/* <Route path="clinics/view/:type/:id" element={<ViewSingleClinic />} />
              <Route path="clinics/edit/:type/:id" element={<EditSingleClinic />} /> */}

              <Route path="users" element={<Users />} />
              <Route path="users/view/:type/:id" element={<ViewSingleUser />} />
              <Route path="users/edit/:type/:id" element={<EditSingleUser />} />
            </Route>

            {/* ------------------- Doctor   ------------------- */}

            <Route
              path="doctor"
              element={
                // <ProtectedRoute>
                <Doctor />
                /* </ProtectedRoute> */
              }
            >
              <Route path="activate-account" element={<ActivateAccountDoc />} />
              <Route path="add-clinic" element={<AddClinic />} />
              <Route path="personal-info" element={<PersonalData />} />
              <Route path="view-clinics" element={<ViewClinics />} />
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
