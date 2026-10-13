import { Router } from "express";
import prisma from "../prisma";
import { buildBookingEmail, sendBookingEmail } from "../services/email.service";
// Create a router to handle booking requests.
const router = Router();

// Get all active bookings.
router.get("/", async (req, res) => {
  try {
    // Get all bookings and sort them by the latest date.
    const bookings = await prisma.t_booking.findMany({
      where: {
        deleted_flg: 0,
      },
      orderBy: {
        created_at: "desc",
      },
    });

    // Get customer details for each booking.
    const bookingsWithCustomer = await Promise.all(
      bookings.map(async (booking) => {
        // Find the customer linked to the booking.
        const customer = await prisma.t_customer.findFirst({
          where: {
            customer_id: booking.customer_id,
            deleted_flg: 0,
          },
        });

        // Return the booking details with customer information.
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
          approve_by: booking.approve_by,
          approve_at: booking.approve_at,
          mail_send_details_id: booking.mail_send_details_id,
          created_at: booking.created_at,
        };
      }),
    );

    // Send the booking list as a success response.
    return res.status(200).json({
      success: true,
      data: bookingsWithCustomer,
    });
  } catch (error) {
    console.error("Get bookings error:", error);
    // Send an error response to the client.
    return res.status(500).json({
      success: false,
      message: "Failed to load bookings",
    });
  }
});

// Approve a booking.
router.put("/:id/approve", async (req, res) => {
  try {
    // Get the booking ID from the URL.
    const bookingId = Number(req.params.id);

    // Check whether the booking ID is valid.
    if (!Number.isInteger(bookingId) || bookingId <= 0) {
      return res.status(400).json({
        success: false,
        message: "Invalid booking ID",
      });
    }

    // Find the active booking using its ID.
    const booking = await prisma.t_booking.findFirst({
      where: {
        id: bookingId,
        deleted_flg: 0,
      },
    });

    // Check whether the booking exists.
    if (!booking) {
      return res.status(404).json({
        success: false,
        message: "Booking not found",
      });
    }

    // Check whether the booking is already approved.
    if (booking.booking_status === 1) {
      return res.status(400).json({
        success: false,
        message: "Booking is already approved",
      });
    }

    // Get the admin name from the request.
    const adminName =
      typeof req.body?.admin_name === "string" && req.body.admin_name.trim()
        ? req.body.admin_name.trim()
        : "admin";

    // Update the booking status to approved.
    const updatedBooking = await prisma.t_booking.update({
      where: {
        id: bookingId,
      },
      data: {
        booking_status: 1,
        approve_by: adminName,
        approve_at: new Date(),
        updated_by: adminName,
        // Reset the email reference only if one exists.
        ...(booking.mail_send_details_id != null && {
          mail_send_details_id: null,
        }),
      },
    });

    // Send a success response with the updated booking.
    return res.status(200).json({
      success: true,
      message: "Booking approved successfully",
      data: updatedBooking,
    });
  } catch (error) {
    console.error("Approve booking error:", error);
    // Send an error response to the client.
    return res.status(500).json({
      success: false,
      message: "Failed to approve booking",
    });
  }
});

// Reject a booking.
router.put("/:id/reject", async (req, res) => {
  try {
    // Get the booking ID from the URL.
    const bookingId = Number(req.params.id);

    // Check whether the booking ID is valid.
    if (!Number.isInteger(bookingId) || bookingId <= 0) {
      return res.status(400).json({
        success: false,
        message: "Invalid booking ID",
      });
    }

    // Find the active booking using its ID.
    const booking = await prisma.t_booking.findFirst({
      where: {
        id: bookingId,
        deleted_flg: 0,
      },
    });

    // Check whether the booking exists.
    if (!booking) {
      return res.status(404).json({
        success: false,
        message: "Booking not found",
      });
    }

    // Check whether the booking is already rejected.
    if (booking.booking_status === 2) {
      return res.status(400).json({
        success: false,
        message: "Booking is already rejected",
      });
    }

    // Get the admin name from the request.
    const adminName =
      typeof req.body?.admin_name === "string" && req.body.admin_name.trim()
        ? req.body.admin_name.trim()
        : "admin";

    // Update the booking status to rejected.
    const updatedBooking = await prisma.t_booking.update({
      where: {
        id: bookingId,
      },
      data: {
        booking_status: 2,
        approve_by: adminName,
        approve_at: new Date(),
        updated_by: adminName,
        // Reset the email reference only if one exists.
        ...(booking.mail_send_details_id != null && {
          mail_send_details_id: null,
        }),
      },
    });

    // Send a success response with the updated booking.
    return res.status(200).json({
      success: true,
      message: "Booking rejected successfully",
      data: updatedBooking,
    });
  } catch (error) {
    console.error("Reject booking error:", error);
    // Send an error response to the client.
    return res.status(500).json({
      success: false,
      message: "Failed to reject booking",
    });
  }
});

// Change a booking status back to pending.
router.put("/:id/revert", async (req, res) => {
  try {
    // Get the booking ID from the URL.
    const bookingId = Number(req.params.id);

    // Check whether the booking ID is valid.
    if (!Number.isInteger(bookingId) || bookingId <= 0) {
      return res.status(400).json({
        success: false,
        message: "Invalid booking ID",
      });
    }

    // Find the active booking using its ID.
    const booking = await prisma.t_booking.findFirst({
      where: {
        id: bookingId,
        deleted_flg: 0,
      },
    });

    // Check whether the booking exists.
    if (!booking) {
      return res.status(404).json({
        success: false,
        message: "Booking not found",
      });
    }

    // Check whether the booking is already pending.
    if (booking.booking_status === 0) {
      return res.status(400).json({
        success: false,
        message: "Booking is already pending",
      });
    }

    // Get the admin name from the request.
    const adminName =
      typeof req.body?.admin_name === "string" && req.body.admin_name.trim()
        ? req.body.admin_name.trim()
        : "admin";

    // Update the booking status to pending.
    const updatedBooking = await prisma.t_booking.update({
      where: {
        id: bookingId,
      },
      data: {
        booking_status: 0,
        approve_by: adminName,
        approve_at: new Date(),
        updated_by: adminName,
        mail_send_details_id: null,
      },
    });

    // Send a success response with the updated booking.
    return res.status(200).json({
      success: true,
      message: "Booking reverted to pending successfully",
      data: updatedBooking,
    });
  } catch (error) {
    console.error("Revert booking error:", error);
    // Send an error response to the client.
    return res.status(500).json({
      success: false,
      message: "Failed to revert booking",
    });
  }
});

// Send an email for a booking.
router.post("/:id/send-email", async (req, res) => {
  try {
    // Get the booking ID from the URL.
    const bookingId = Number(req.params.id);

    // Check whether the booking ID is valid.
    if (!Number.isInteger(bookingId) || bookingId <= 0) {
      return res.status(400).json({
        success: false,
        message: "Invalid booking ID",
      });
    }

    // Find the active booking using its ID.
    const booking = await prisma.t_booking.findFirst({
      where: { id: bookingId, deleted_flg: 0 },
    });

    // Check whether the booking exists.
    if (!booking) {
      return res.status(404).json({
        success: false,
        message: "Booking not found",
      });
    }

    // Find the customer linked to the booking.
    const customer = await prisma.t_customer.findFirst({
      where: {
        customer_id: booking.customer_id,
        deleted_flg: 0,
      },
    });

    // Check whether the customer exists.
    if (!customer) {
      return res.status(404).json({
        success: false,
        message: "Customer not found",
      });
    }

    // Check whether the customer email is available.
    if (!customer.email?.trim()) {
      return res.status(400).json({
        success: false,
        message: "Customer email address is not available",
      });
    }

    // Get the booking status as readable text.
    const status =
      booking.booking_status === 1
        ? "Approved"
        : booking.booking_status === 2
          ? "Rejected"
          : "Pending";

    // Get the admin name from the request.
    const adminName =
      typeof req.body?.admin_name === "string" && req.body.admin_name.trim()
        ? req.body.admin_name.trim()
        : "admin";

    // Prepare the booking and customer details for the email.
    const emailData = {
      customerName: customer.customer_name,
      customerEmail: customer.email.trim(),
      eventType: booking.event_type,
      eventDate: booking.event_date.toISOString(),
      numberOfGuests: booking.number_of_guests,
      requirements: booking.requirements,
      status: status as "Pending" | "Approved" | "Rejected",
    };

    // Prepare the email subject and body.
    const mail = buildBookingEmail(emailData);

    // Save the email details in the database.
    const mailRecord = await prisma.t_mail_send_details.create({
      data: {
        customer_id: booking.customer_id,
        booking_id: booking.id,
        from_email: mail.fromEmail,
        to_email: mail.toEmail,
        subject: mail.subject,
        body: mail.body,
        mail_send_status: 0,
        created_by: adminName,
      },
    });

    // Send the email to the customer.
    await sendBookingEmail(emailData);

    // Update the email status and booking email reference.
    await prisma.$transaction([
      prisma.t_mail_send_details.update({
        where: { id: mailRecord.id },
        data: {
          mail_send_status: 1,
          mail_send_date: new Date(),
          updated_by: adminName,
        },
      }),
      prisma.t_booking.update({
        where: { id: booking.id },
        data: {
          mail_send_details_id: mailRecord.id,
          updated_by: adminName,
        },
      }),
    ]);

    // Send a success response with the email details.
    return res.status(200).json({
      success: true,
      message: `Email sent successfully to ${customer.email}`,
      data: {
        booking_id: booking.id,
        mail_send_details_id: mailRecord.id,
        mail_send_status: 1,
      },
    });
  } catch (error) {
    console.error("Send booking email error:", error);
    // Send an error response to the client.
    return res.status(500).json({
      success: false,
      message: "Failed to send booking email. Check the server logs.",
    });
  }
});
export default router;
