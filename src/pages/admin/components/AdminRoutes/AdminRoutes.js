import React from 'react';
import { Routes, Route } from 'react-router-dom';
import {
  Dashboard,
  ActivateAccounts,
  AddAdmin,
  Clinics,
  EditInfo,
  Logout,
  Pharmacies,
  Users,
  Home,
} from '../../../index';
const AdminRoutes = () => {
  return (
    <>Hello</>
    // <Routes>
    //   <Route path="dashboard" element={<Dashboard />} />
    //   <Route path="activate-accounts" element={<ActivateAccounts />} />
    //   <Route path="add-admin" element={<AddAdmin />} />
    //   <Route path="clinics" element={<Clinics />} />
    //   <Route path="edit-info" element={<EditInfo />} />
    //   <Route path="logout" element={<Logout />} />
    //   <Route path="pharmacies" element={<Pharmacies />} />
    //   <Route path="users" element={<Users />} />
    // </Routes>
  );
};

export default AdminRoutes;
