import "dotenv/config";
import express from "express";
import cors from "cors";
import customerRoutes from "./routes/customer.routes";
import bookingRoutes from "./routes/booking.routes";

const app = express();

app.use(cors());
app.use(express.json());

app.get("/api/health", (_req, res) => {
  res.json({
    success: true,
    message: "Palaiya Marriage Hall API is running",
  });
});

app.use("/api/customers", customerRoutes);
app.use("/api/bookings", bookingRoutes);

const PORT = process.env.PORT || 5000;

app.listen(PORT, () => {
  console.log(`API server running on http://localhost:${PORT}`);
});
