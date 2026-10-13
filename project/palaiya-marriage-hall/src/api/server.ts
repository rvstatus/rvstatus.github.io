import "dotenv/config";
import express from "express";
import cors from "cors";
import customerRoutes from "./routes/customer.routes";
import bookingRoutes from "./routes/booking.routes";
import adminRoutes from "./routes/admin.routes";
import adminDashboardRoutes from "./routes/adminDashboard.routes";
import adminBookingsRoutes from "./routes/adminBookings.routes";
import mailHistoryRoutes from "./routes/mailHistory.routes";

const app = express();

app.use(cors());
app.use(express.json());

// Check whether the API server is running.
app.get("/api/health", (_req, res) => {
  res.json({
    success: true,
    message: "Palaiya Marriage Hall API is running",
  });
});

// Connect the routes to the application.
app.use("/api/customers", customerRoutes);
app.use("/api/bookings", bookingRoutes);
app.use("/api/admin", adminRoutes);
app.use("/admin/dashboard", adminDashboardRoutes);
app.use("/admin/bookings", adminBookingsRoutes);
app.use("/admin/mail-history", mailHistoryRoutes);

const PORT = process.env.PORT || 5000;

// Start the API server and display the server URL.
app.listen(PORT, () => {
  console.log(`API server running on http://localhost:${PORT}`);
});
