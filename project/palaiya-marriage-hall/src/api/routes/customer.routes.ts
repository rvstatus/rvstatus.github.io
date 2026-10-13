import { Router } from "express";
import prisma from "../prisma";
// Create a router to handle customer requests.
const router = Router();

// Get all customers.
router.get("/", async (req, res) => {
  try {
    // Get all active customers and sort them by the latest date.
    const customers = await prisma.t_customer.findMany({
      where: {
        deleted_flg: 0,
      },
      orderBy: {
        created_at: "desc",
      },
    });

    // Send the customer list as a success response.
    return res.status(200).json({
      success: true,
      data: customers,
    });
  } catch (error) {
    console.error("Get customers error:", error);
    // Send an error response to the client.
    return res.status(500).json({
      success: false,
      message: "Failed to load customers",
    });
  }
});

export default router;
