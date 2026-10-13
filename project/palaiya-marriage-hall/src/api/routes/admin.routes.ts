import { Router } from "express";
import prisma from "../prisma";
// Create a router to handle admin login.
const router = Router();

// Handle the admin login request.
router.post("/login", async (req, res) => {
  try {
    // Get the email and password from the request.
    const { email, password } = req.body;

    if (!email || !password) {
      return res.status(400).json({
        success: false,
        message: "Email and password are required",
      });
    }

    // Find the admin using the email and check if the account is active.
    const admin = await prisma.t_admin.findFirst({
      where: {
        email: email.trim(),
        deleted_flg: 0,
      },
    });

    // Check whether the admin exists and the password is correct.
    if (!admin || admin.password !== password) {
      return res.status(401).json({
        success: false,
        message: "Invalid email or password",
      });
    }

    // Send a success response with the admin details.
    return res.status(200).json({
      success: true,
      message: "Login successful",
      admin: {
        id: admin.id,
        name: admin.name,
        email: admin.email,
      },
    });
  } catch (error) {
    console.error("Admin login error:", error);
    // Send an error response to the client.
    return res.status(500).json({
      success: false,
      message: "Failed to login",
    });
  }
});

export default router;
