import { Navigate, Outlet } from "react-router-dom";

const WhenLoggedIn = ({ isAuthenticated }) => {
  return !isAuthenticated ? <Outlet /> : <Navigate to="/admin/dashboard" />;
};

export default WhenLoggedIn;
