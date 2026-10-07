import { Router } from "express";
import prisma from "../prisma";

const router = Router();

router.post("/", async (req, res) => {
  try {
    const {
      customer_name,
      mobile,
      email,
      event_date,
      event_type,
      number_of_guests,
      requirements,
    } = req.body;

    // ---------------------------------------
    // 1. Basic validation
    // ---------------------------------------

    if (!customer_name || !mobile) {
      return res.status(400).json({
        success: false,
        message: "Customer name and mobile are required",
      });
    }

    if (!event_date || !event_type) {
      return res.status(400).json({
        success: false,
        message: "Event date and event type are required",
      });
    }

    const guests =
      number_of_guests === undefined ||
      number_of_guests === null ||
      number_of_guests === ""
        ? null
        : Number(number_of_guests);

    if (guests !== null && (!Number.isInteger(guests) || guests < 1)) {
      return res.status(400).json({
        success: false,
        message: "Number of guests must be at least 1",
      });
    }

    // ---------------------------------------
    // 2. Transaction
    // ---------------------------------------

    const result = await prisma.$transaction(async (tx) => {
      // ---------------------------------------
      // 3. Find existing customer
      // ---------------------------------------

      const existingCustomer = await tx.t_customer.findFirst({
        where: {
          mobile: mobile.trim(),
          email: email.trim(),
        },
      });

      let customer;

      // ---------------------------------------
      // 4. Existing customer
      // ---------------------------------------

      if (existingCustomer) {
        console.log(`Existing customer found: ${existingCustomer.customer_id}`);

        // Update name only if it is different
        if (existingCustomer.customer_name.trim() !== customer_name.trim()) {
          customer = await tx.t_customer.update({
            where: {
              id: existingCustomer.id,
            },
            data: {
              customer_name: customer_name.trim(),
              updated_by: existingCustomer.customer_id,
            },
          });
        } else {
          customer = existingCustomer;
        }
      }

      // ---------------------------------------
      // 5. New customer
      // ---------------------------------------
      else {
        console.log("New customer - creating customer");

        const lastCustomer = await tx.t_customer.findFirst({
          orderBy: {
            id: "desc",
          },
          select: {
            id: true,
          },
        });

        const nextCustomerId = (lastCustomer?.id ?? 0) + 1;

        const customer_id = `CUS${String(nextCustomerId).padStart(4, "0")}`;

        customer = await tx.t_customer.create({
          data: {
            customer_id,
            customer_name: customer_name.trim(),
            mobile: mobile.trim(),
            email: email.trim(),
            created_by: customer_id,
          },
        });
      }

      // ---------------------------------------
      // 6. CHECK EXISTING BOOKINGS FOR DATE
      // ---------------------------------------

      const requestedDate = new Date(event_date);

      const existingBookings = await tx.t_booking.findMany({
        where: {
          event_date: requestedDate,
        },
        orderBy: {
          id: "asc",
        },
      });

      // ---------------------------------------
      // 7. CHECK APPROVED BOOKING
      //
      // booking_status = 1
      // AND
      // approve_by OR approve_at is not null
      // ---------------------------------------

      const approvedBooking = existingBookings.find(
        (booking) =>
          booking.booking_status === 1 &&
          (booking.approve_by !== null || booking.approve_at !== null),
      );

      // ---------------------------------------
      // 8. APPROVED BOOKING EXISTS
      // ---------------------------------------

      if (approvedBooking) {
        // ---------------------------------------
        // Same customer
        // ---------------------------------------

        if (approvedBooking.customer_id === customer.customer_id) {
          return {
            success: false,
            code: "ALREADY_BOOKED_BY_YOU",
            message:
              "You have already booked this date. We cannot create another booking for the same date.",
            customer,
            booking: null,
          };
        }

        // ---------------------------------------
        // Different customer
        // ---------------------------------------

        return {
          success: false,
          code: "DATE_ALREADY_BOOKED",
          message:
            "This date is already booked by another customer. Please try another date.",
          customer,
          booking: null,
        };
      }

      // ---------------------------------------
      // 9. CHECK PENDING BOOKING
      //
      // Pending booking DOES NOT BLOCK creation.
      // We still create the new booking.
      // ---------------------------------------

      const pendingBooking = existingBookings.find(
        (booking) => booking.booking_status === 0,
      );
      const pendingBookingForSameCustomer = existingBookings.find(
        (booking) =>
          booking.booking_status === 0 &&
          booking.customer_id == customer.customer_id,
      );
      if (pendingBookingForSameCustomer) {
        // Same customer
        return {
          success: false,
          code: "PENDING_ALREADY_SUBMITTED",
          message:
            "You have already submitted an enquiry for this date. Please wait for admin confirmation.",
          customer,
          booking: null,
        };
      }
      // ---------------------------------------
      // 10. CREATE NEW BOOKING
      // ---------------------------------------

      const booking = await tx.t_booking.create({
        data: {
          customer_id: customer.customer_id,
          event_date: requestedDate,
          event_type,
          number_of_guests: guests,
          requirements: requirements || null,

          // 0 = pending
          booking_status: 0,

          approve_by: null,
          approve_at: null,

          created_by: customer.customer_id,
        },
      });

      // ---------------------------------------
      // 11. Pending warning
      // ---------------------------------------

      if (pendingBooking) {
        return {
          success: true,
          code: "PENDING_DATE",
          message:
            "Your enquiry has been submitted. This date already has a pending enquiry. Please contact admin as soon as possible.",
          customer,
          booking,
        };
      }

      // ---------------------------------------
      // 12. Date completely available
      // ---------------------------------------

      return {
        success: true,
        code: "BOOKING_CREATED",
        message: "Customer and booking registered successfully.",
        customer,
        booking,
      };
    });

    // ---------------------------------------
    // 13. Handle blocked booking
    // ---------------------------------------

    if (!result.success) {
      return res.status(409).json(result);
    }

    // ---------------------------------------
    // 14. Successful booking
    // ---------------------------------------

    return res.status(201).json(result);
  } catch (error) {
    console.error("Customer and booking registration error:", error);

    return res.status(500).json({
      success: false,
      code: "SERVER_ERROR",
      message:
        error instanceof Error
          ? error.message
          : "Failed to register customer and booking",
    });
  }
});

export default router;
