import { Router } from "express";
import prisma from "../prisma";
// Create a router to handle email history requests.
const router = Router();

// Get email history with search and status filters.
router.get("/", async (req, res) => {
  try {
    // Get the search text from the request.
    const search =
      typeof req.query.search === "string" ? req.query.search.trim() : "";

    // Get the email status from the request.
    const statusParam = req.query.status;

    // Get only active email records.
    const where: any = {
      deleted_flg: 0,
    };

    // Filter email records by status.
    if (statusParam === "0" || statusParam === "1" || statusParam === "2") {
      where.mail_send_status = Number(statusParam);
    }

    // Search email records using the given text.
    if (search) {
      // Convert the search text to a booking ID.
      const bookingId = Number(search);

      // Search by customer ID, email address, or subject.
      where.OR = [
        {
          customer_id: {
            contains: search,
          },
        },
        {
          to_email: {
            contains: search,
          },
        },
        {
          subject: {
            contains: search,
          },
        },
        // Search by booking ID if the value is valid.
        ...(Number.isInteger(bookingId) && bookingId > 0
          ? [{ booking_id: bookingId }]
          : []),
      ];
    }

    // Get the email history and sort it by the latest date.
    const mailHistory = await prisma.t_mail_send_details.findMany({
      where,
      orderBy: {
        created_at: "desc",
      },
    });

    // Send the email history as a success response.
    return res.status(200).json({
      success: true,
      data: mailHistory,
    });
  } catch (error) {
    console.error("Get mail history error:", error);
    // Send an error response to the client.
    return res.status(500).json({
      success: false,
      message: "Failed to load mail history",
    });
  }
});

export default router;
