import React, { useContext } from "react";
import { Navigate, Route, Routes } from "react-router-dom";
import ProtectedRoute from "../pages/ProtectedRoute";
import {
  Doctor,
  ActivateAccountDoc,
  AddClinic,
  PersonalData,
  ViewClinics,
  AuthLogin,
  AuthRegister,
  ActiveEmail,
  ForgetPassword,
  AuthHome,
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
} from "../pages";
import { AuthContext } from "../store/auth/context";
import WhenLoggedIn from "./WhenLoggedIn";

const RoutesLayout = () => {
  const { user } = useContext(AuthContext);
  return (
    <Routes>
      <Route element={<WhenLoggedIn isAuthenticated={user} />}>
        <Route index path="/" element={<AuthHome />} />
        <Route path="/login" element={<AuthLogin />} />
        <Route path="/active-email" element={<ActiveEmail />} />
        <Route path="/register" element={<AuthRegister />} />
        <Route path="/forget-password" element={<ForgetPassword />} />
      </Route>
      {/* ------------------- Admin   ------------------- */}
      {/* NOTE Dont fogot remove this comments on ProtectedRoute  */}
      <Route
        path="admin"
        element={
          <ProtectedRoute>
            <Home />
          </ProtectedRoute>
        }
      >
        <Route path="dashboard" element={<Dashboard />} />
        <Route
          path="activate-accounts"
          element={
            <ProtectedRoute>
              <ActivateAccounts />
            </ProtectedRoute>
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
          <ProtectedRoute>
            <Doctor />
          </ProtectedRoute>
        }
      >
        <Route path="activate-account" element={<ActivateAccountDoc />} />
        <Route path="add-clinic" element={<AddClinic />} />
        <Route path="personal-info" element={<PersonalData />} />
        <Route path="view-clinics" element={<ViewClinics />} />
      </Route>
    </Routes>
  );
};

export default RoutesLayout;
