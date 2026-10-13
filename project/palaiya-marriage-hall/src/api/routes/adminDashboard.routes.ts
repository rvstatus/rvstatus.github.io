import { Router } from "express";
import prisma from "../prisma";
// Create a router to handle dashboard requests.
const router = Router();

// Get the admin dashboard data.
router.get("/", async (req, res) => {
  try {
    // Get booking counts, customer count, and recent bookings.
    const [
      totalBookings,
      pendingBookings,
      approvedBookings,
      totalCustomers,
      recentBookings,
    ] = await Promise.all([
      // Count all active bookings.
      prisma.t_booking.count({
        where: {
          deleted_flg: 0,
        },
      }),
      // Count all pending bookings.
      prisma.t_booking.count({
        where: {
          deleted_flg: 0,
          booking_status: 0,
        },
      }),
      // Count all approved bookings.
      prisma.t_booking.count({
        where: {
          deleted_flg: 0,
          booking_status: 1,
        },
      }),
      // Count all active customers.
      prisma.t_customer.count({
        where: {
          deleted_flg: 0,
        },
      }),
      // Get the five most recent bookings.
      prisma.t_booking.findMany({
        where: {
          deleted_flg: 0,
        },
        orderBy: {
          created_at: "desc",
        },
        take: 5,
      }),
    ]);

    // Get customer details for each recent booking.
    const recentBookingsWithCustomer = await Promise.all(
      recentBookings.map(async (booking) => {
        // Find the customer linked to the booking.
        const customer = await prisma.t_customer.findFirst({
          where: {
            customer_id: booking.customer_id,
            deleted_flg: 0,
          },
        });
        // Return booking details with customer information.
        return {
          id: booking.id,
          customer_id: booking.customer_id,
          customer_name: customer?.customer_name || "Unknown",
          mobile: customer?.mobile || "",
          email: customer?.email || "",
          event_date: booking.event_date,
          event_type: booking.event_type,
          number_of_guests: booking.number_of_guests,
          requirements: booking.requirements,
          booking_status: booking.booking_status,
          created_at: booking.created_at,
        };
      }),
    );

    // Send the dashboard data as a success response.
    return res.status(200).json({
      success: true,
      data: {
        totalBookings,
        pendingBookings,
        approvedBookings,
        totalCustomers,
        recentBookings: recentBookingsWithCustomer,
      },
    });
  } catch (error) {
    console.error("Admin dashboard error:", error);
    // Send an error response to the client.
    return res.status(500).json({
      success: false,
      message: "Failed to load dashboard data",
    });
  }
});

export default router;
