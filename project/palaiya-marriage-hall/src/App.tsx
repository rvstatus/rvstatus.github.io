import React from "react";
import { Navigate, Route, Routes } from "react-router-dom";

import Home from "./pages/Home";
import AdminLogin from "./pages/admin/AdminLogin";
import AdminDashboard from "./pages/admin/AdminDashboard";
import AdminBookings from "./pages/admin/AdminBookings";
import AdminLayout from "./pages/components/admin/AdminLayout";
import MailHistory from "./pages/admin/MailHistory";
import Customers from "./pages/admin/Customers";

const AppRoutes: React.FC = () => {
  // Check whether the admin is logged in.
  const isAdminLoggedIn = Boolean(localStorage.getItem("admin"));

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
