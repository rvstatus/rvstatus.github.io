import nodemailer from "nodemailer";
// Get the Gmail username and app password from environment variables.
const gmailUser = process.env.GMAIL_USER;
const gmailAppPassword = process.env.GMAIL_APP_PASSWORD;

// Check whether the Gmail configuration is available.
if (!gmailUser || !gmailAppPassword) {
  console.warn("Gmail configuration missing: GMAIL_USER / GMAIL_APP_PASSWORD");
}

// Create a transporter to send emails using Gmail.
const transporter = nodemailer.createTransport({
  service: "gmail",
  auth: {
    user: gmailUser,
    pass: gmailAppPassword,
  },
});

// Define the data required to send a booking email.
export interface BookingEmailData {
  customerName: string;
  customerEmail: string;
  eventType: string;
  eventDate: string;
  numberOfGuests: number | null;
  requirements: string | null;
  status: "Pending" | "Approved" | "Rejected";
}

// Prepare the booking email details and content.
export const buildBookingEmail = (data: BookingEmailData) => {
  // Get the customer and booking details from the data.
  const {
    customerName,
    customerEmail,
    eventType,
    eventDate,
    numberOfGuests,
    requirements,
    status,
  } = data;

  // Check whether Gmail is configured before preparing the email.
  if (!gmailUser || !gmailAppPassword) {
    throw new Error("Gmail is not configured");
  }

  // Convert the event date into a readable date format.
  const formattedDate = new Date(eventDate).toLocaleDateString("en-IN", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  });
  // Set the email subject using the booking status.
  const subject = `Booking ${status} - Palaiya Marriage Hall`;

  // Create the HTML content for the booking email.
  const body = `
    <div style="font-family:Arial,sans-serif;max-width:650px;margin:0 auto;color:#333">
      <div style="background:#6d3b24;color:#fff;padding:24px;text-align:center">
        <h2 style="margin:0">Palaiya Marriage Hall</h2>
      </div>
      <div style="padding:30px">
        <p>Dear <strong>${customerName}</strong>,</p>
        <p>Your marriage hall booking status has been updated.</p>
        <div style="background:#faf7f5;border:1px solid #e5e5e5;border-radius:8px;padding:20px;margin:20px 0">
          <p><strong>Status:</strong> ${status}</p>
          <p><strong>Event:</strong> ${eventType}</p>
          <p><strong>Event Date:</strong> ${formattedDate}</p>
          <p><strong>Guests:</strong> ${numberOfGuests ?? "-"}</p>
          ${requirements ? `<p><strong>Requirements:</strong> ${requirements}</p>` : ""}
        </div>
        ${
          status === "Approved"
            ? '<p style="color:#2e7d32;font-weight:bold">Your booking has been approved.</p>'
            : status === "Rejected"
              ? '<p style="color:#c62828;font-weight:bold">Unfortunately, your booking has been rejected.</p>'
              : '<p style="color:#ed6c02;font-weight:bold">Your booking is currently pending.</p>'
        }
        <p>Thank you for choosing Palaiya Marriage Hall.</p>
        <p>Regards,<br/><strong>Palaiya Marriage Hall</strong></p>
      </div>
    </div>
  `;

  // Return the sender, receiver, subject, and email content.
  return {
    from: `"Palaiya Marriage Hall" <${gmailUser}>`,
    to: customerEmail,
    subject,
    html: body,
    fromEmail: gmailUser,
    toEmail: customerEmail,
    body,
  };
};

// Send the booking email to the customer.
export const sendBookingEmail = async (data: BookingEmailData) => {
  // Prepare the email details before sending.
  const message = buildBookingEmail(data);

  // Send the email using the Gmail transporter.
  const info = await transporter.sendMail({
    from: message.from,
    to: message.to,
    subject: message.subject,
    html: message.html,
  });

  // Return the email details along with the message ID.
  return {
    ...message,
    messageId: info.messageId,
  };
};
