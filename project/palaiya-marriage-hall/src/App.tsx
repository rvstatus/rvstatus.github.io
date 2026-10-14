import React from "react";
import { Navigate, Route, Routes } from "react-router-dom";

import Home from "./pages/Home";
import AdminLogin from "./pages/admin/AdminLogin";
import AdminDashboard from "./pages/admin/AdminDashboard";
import AdminBookings from "./pages/admin/AdminBookings";
import AdminLayout from "./pages/components/admin/AdminLayout";
import MailHistory from "./pages/admin/MailHistory";
import Customers from "./pages/admin/Customers";

// admin-auth-changed used for the home screen
export const ADMIN_AUTH_EVENT = "admin-auth-changed";

// notifyAdminAuthChanged used to identify the login and logout properly
export const notifyAdminAuthChanged = () => {
  window.dispatchEvent(new Event(ADMIN_AUTH_EVENT));
};

// isAdminAuthenticated is store the localStorage of admin value
export const isAdminAuthenticated = () =>
  Boolean(localStorage.getItem("admin"));

const AppRoutes: React.FC = () => {
  // isAdminAuthenticated set on the isAdminLoggedIn
  const [isAdminLoggedIn, setIsAdminLoggedIn] =
    React.useState(isAdminAuthenticated);
  // useEffect for syncAdminAuth
  React.useEffect(() => {
    const syncAdminAuth = () => {
      setIsAdminLoggedIn(isAdminAuthenticated());
    };

    window.addEventListener(ADMIN_AUTH_EVENT, syncAdminAuth);
    window.addEventListener("storage", syncAdminAuth);

    return () => {
      window.removeEventListener(ADMIN_AUTH_EVENT, syncAdminAuth);
      window.removeEventListener("storage", syncAdminAuth);
    };
  }, []);

  return (
    <Routes>
      <Route path="/" element={<Home />} />

      <Route path="/admin/login" element={<AdminLogin />} />

      <Route
        path="/admin"
        element={
          isAdminLoggedIn ? (
            <AdminLayout />
          ) : (
            <Navigate to="/admin/login" replace />
          )
        }
      >
        <Route index element={<Navigate to="/admin/dashboard" replace />} />
        <Route path="dashboard" element={<AdminDashboard />} />
        <Route path="bookings" element={<AdminBookings />} />
        <Route path="mail-history" element={<MailHistory />} />
        <Route path="customers" element={<Customers />} />
        <Route path="/admin" element={<AdminLayout />} />
      </Route>

      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
};

export default AppRoutes;
