import React from "react";
import {
  Alert,
  Box,
  Button,
  Card,
  CardContent,
  Chip,
  CircularProgress,
  IconButton,
  Paper,
  Snackbar,
  Stack,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Tooltip,
  Typography,
} from "@mui/material";

import RefreshIcon from "@mui/icons-material/Refresh";
import CheckCircleIcon from "@mui/icons-material/CheckCircle";
import CancelIcon from "@mui/icons-material/Cancel";
import UndoIcon from "@mui/icons-material/Undo";
import MarkEmailReadIcon from "@mui/icons-material/MarkEmailRead";
import EmailIcon from "@mui/icons-material/Email";

interface Booking {
  id: number;
  customer_id: string;
  customer_name: string;
  mobile: string;
  email: string;
  event_date: string;
  event_type: string;
  number_of_guests: number | null;
  requirements: string | null;
  booking_status: number;
  approve_by?: string | null;
  approve_at?: string | null;
  mail_send_details_id?: number | null;
  created_at: string;
}

const AdminBookings: React.FC = () => {
  // Store the booking list.
  const [bookings, setBookings] = React.useState<Booking[]>([]);

  // Store the loading states.
  const [loading, setLoading] = React.useState(true);
  const [actionLoading, setActionLoading] = React.useState<number | null>(null);
  const [refreshing, setRefreshing] = React.useState(false);
  const [emailLoading, setEmailLoading] = React.useState<number | null>(null);

  // Store the message details.
  const [snackbar, setSnackbar] = React.useState({
    open: false,
    message: "",
    severity: "success" as "success" | "error",
  });

  // Get the logged-in admin details.
  const adminData = localStorage.getItem("admin");
  const admin = adminData ? JSON.parse(adminData) : null;

  // Show a success or error message.
  const showMessage = (message: string, severity: "success" | "error") => {
    setSnackbar({
      open: true,
      message,
      severity,
    });
  };

  // Get all bookings from the API.
  const fetchBookings = async (showRefreshLoader = false) => {
    try {
      // Show the loading indicator.
      if (showRefreshLoader) {
        setRefreshing(true);
      } else {
        setLoading(true);
      }

      // Send a request to get all bookings.
      const response = await fetch("http://localhost:5000/admin/bookings");

      // Read the response data.
      const result = await response.json();

      // Check whether the request was successful.
      if (!response.ok || !result.success) {
        throw new Error(result.message || "Failed to load bookings");
      }

      // Update the booking list.
      setBookings(result.data || []);
    } catch (error) {
      // Print the error if bookings cannot be loaded.
      console.error("Failed to load bookings:", error);

      // Show an error message.
      showMessage(
        error instanceof Error ? error.message : "Failed to load bookings",
        "error",
      );
    } finally {
      // Stop the loading indicators.
      setLoading(false);
      setRefreshing(false);
    }
  };

  // Load bookings when the page opens.
  React.useEffect(() => {
    fetchBookings();
  }, []);

  // Update the booking status.
  const updateBookingStatus = async (
    bookingId: number,
    action: "approve" | "reject" | "revert",
  ) => {
    try {
      // Show the action loading indicator.
      setActionLoading(bookingId);

      // Send the booking status update request.
      const response = await fetch(
        `http://localhost:5000/admin/bookings/${bookingId}/${action}`,
        {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            admin_name: admin?.name || "admin",
          }),
        },
      );

      // Read the response data.
      const result = await response.json();

      // Check whether the update was successful.
      if (!response.ok || !result.success) {
        throw new Error(result.message || `Failed to ${action} booking`);
      }

      // Show a success message.
      showMessage(
        result.message || `Booking ${action}d successfully`,
        "success",
      );

      // Get the latest booking data from the API.
      await fetchBookings(true);
    } catch (error) {
      // Print the error if the booking update fails.
      console.error(`Failed to ${action} booking:`, error);

      // Show an error message.
      showMessage(
        error instanceof Error ? error.message : `Failed to ${action} booking`,
        "error",
      );
    } finally {
      // Stop the action loading indicator.
      setActionLoading(null);
    }
  };

  // Handle the refresh button click.
  const handleRefresh = () => {
    fetchBookings(true);
  };

  // Handle the approve button click.
  const handleApprove = (bookingId: number) => {
    updateBookingStatus(bookingId, "approve");
  };

  // Handle the reject button click.
  const handleReject = (bookingId: number) => {
    updateBookingStatus(bookingId, "reject");
  };

  // Handle the revert button click.
  const handleRevert = (bookingId: number) => {
    updateBookingStatus(bookingId, "revert");
  };

  // Get the label and colors for the booking status.
  const getStatus = (status: number) => {
    if (status === 1) {
      return {
        label: "Approved",
        color: "#2e7d32",
        background: "#e8f5e9",
      };
    }

    if (status === 2) {
      return {
        label: "Rejected",
        color: "#c62828",
        background: "#ffebee",
      };
    }

    return {
      label: "Pending",
      color: "#ed6c02",
      background: "#fff3e0",
    };
  };

  // Convert the date into a readable format.
  const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString("en-IN", {
      day: "2-digit",
      month: "short",
      year: "numeric",
    });
  };

  // Send an email to the customer.
  const handleSendEmail = async (
    bookingId: number,
    mail_send_details_id: number | null | undefined,
  ) => {
    // Stop if an email has already been sent.
    if (mail_send_details_id != null) {
      return;
    }

    try {
      // Show the email loading indicator.
      setEmailLoading(bookingId);

      // Send the email request to the API.
      const response = await fetch(
        `http://localhost:5000/admin/bookings/${bookingId}/send-email`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
        },
      );

      // Read the response data.
      const result = await response.json();

      // Check whether the email was sent successfully.
      if (!response.ok || !result.success) {
        throw new Error(result.message || "Failed to send email");
      }

      // Show a success message and refresh the booking list.
      showMessage(result.message || "Email sent successfully", "success");
      await fetchBookings(true);
    } catch (error) {
      // Print the error if the email cannot be sent.
      console.error("Send email error:", error);

      // Show an error message.
      showMessage(
        error instanceof Error ? error.message : "Failed to send email",
        "error",
      );
    } finally {
      // Stop the email loading indicator.
      setEmailLoading(null);
    }
  };

  // Handle the email button click.
  const handleEmailClick = (
    bookingId: number,
    mail_send_details_id: number | null | undefined,
  ) => {
    handleSendEmail(bookingId, mail_send_details_id);
  };

  // Handle the snackbar close event.
  const handleSnackbarClose = () => {
    setSnackbar((prev) => ({
      ...prev,
      open: false,
    }));
  };

  // Display the booking management page.
  return (
    <Box>
      {/* Header */}
      <Box
        sx={{
          mb: 3,
          display: "flex",
          justifyContent: "space-between",
          alignItems: {
            xs: "flex-start",
            sm: "center",
          },
          flexDirection: {
            xs: "column",
            sm: "row",
          },
          gap: 2,
        }}
      >
        <Box>
          <Typography
            variant="h5"
            sx={{
              fontWeight: 700,
              color: "#333",
            }}
          >
            Bookings
          </Typography>

          <Typography
            variant="body2"
            sx={{
              color: "#777",
              mt: 0.5,
            }}
          >
            Manage all marriage hall bookings.
          </Typography>
        </Box>

        {/* Refresh the booking list. */}
        <Button
          variant="outlined"
          startIcon={
            refreshing ? (
              <CircularProgress size={18} sx={{ color: "#6d3b24" }} />
            ) : (
              <RefreshIcon />
            )
          }
          disabled={refreshing || loading}
          onClick={handleRefresh}
          sx={{
            color: "#6d3b24",
            borderColor: "#6d3b24",
            "&:hover": {
              borderColor: "#4e2919",
              backgroundColor: "#f7eee9",
            },
          }}
        >
          Refresh
        </Button>
      </Box>

      {/* Display the booking list inside a card. */}
      <Card
        elevation={0}
        sx={{
          border: "1px solid #e5e5e5",
          borderRadius: 3,
        }}
      >
        <CardContent>
          {/* Show the loader, empty message, or booking table. */}
          {loading ? (
            <Box
              sx={{
                display: "flex",
                justifyContent: "center",
                alignItems: "center",
                py: 8,
              }}
            >
              <CircularProgress
                sx={{
                  color: "#6d3b24",
                }}
              />
            </Box>
          ) : bookings.length === 0 ? (
            <Box
              sx={{
                textAlign: "center",
                py: 8,
              }}
            >
              <Typography
                variant="h6"
                sx={{
                  fontWeight: 600,
                  color: "#555",
                }}
              >
                No bookings found
              </Typography>

              <Typography
                variant="body2"
                sx={{
                  color: "#777",
                  mt: 1,
                }}
              >
                There are currently no bookings.
              </Typography>
            </Box>
          ) : (
            <TableContainer
              component={Paper}
              elevation={0}
              sx={{
                overflowX: "auto",
              }}
            >
              <Table>
                {/* Display the table column names. */}
                <TableHead>
                  <TableRow
                    sx={{
                      backgroundColor: "#faf7f5",
                    }}
                  >
                    <TableCell sx={{ fontWeight: 700 }}>Customer</TableCell>

                    <TableCell sx={{ fontWeight: 700 }}>Event</TableCell>

                    <TableCell sx={{ fontWeight: 700 }}>Event Date</TableCell>

                    <TableCell align="center" sx={{ fontWeight: 700 }}>
                      Guests
                    </TableCell>

                    <TableCell align="center" sx={{ fontWeight: 700 }}>
                      Status
                    </TableCell>

                    <TableCell align="center" sx={{ fontWeight: 700 }}>
                      Action
                    </TableCell>
                    <TableCell align="center" sx={{ fontWeight: 700 }}>
                      Contact
                    </TableCell>
                  </TableRow>
                </TableHead>

                {/* Display each booking and its actions. */}
                <TableBody>
                  {bookings.map((booking) => {
                    // Get the current booking status.
                    const status = getStatus(booking.booking_status);

                    // Check whether an action is in progress.
                    const isActionLoading = actionLoading === booking.id;

                    return (
                      <TableRow key={booking.id} hover>
                        {/* Customer details */}
                        <TableCell>
                          <Typography
                            variant="body2"
                            sx={{
                              fontWeight: 600,
                            }}
                          >
                            {booking.customer_name}
                          </Typography>

                          <Typography
                            variant="caption"
                            sx={{
                              color: "#777",
                              display: "block",
                            }}
                          >
                            {booking.mobile}
                          </Typography>

                          {booking.email && (
                            <Typography
                              variant="caption"
                              sx={{
                                color: "#999",
                                display: "block",
                              }}
                            >
                              {booking.email}
                            </Typography>
                          )}
                        </TableCell>

                        {/* Event details */}
                        <TableCell>
                          <Typography
                            variant="body2"
                            sx={{
                              fontWeight: 600,
                            }}
                          >
                            {booking.event_type}
                          </Typography>

                          {booking.requirements && (
                            <Typography
                              variant="caption"
                              sx={{
                                color: "#777",
                                display: "block",
                                mt: 0.5,
                              }}
                            >
                              {booking.requirements}
                            </Typography>
                          )}
                        </TableCell>

                        {/* Display the event date. */}
                        <TableCell>{formatDate(booking.event_date)}</TableCell>

                        {/* Display the number of guests. */}
                        <TableCell align="center">
                          {booking.number_of_guests ?? "-"}
                        </TableCell>

                        {/* Display the booking status. */}
                        <TableCell align="center">
                          <Chip
                            label={status.label}
                            size="small"
                            sx={{
                              fontWeight: 600,
                              color: status.color,
                              backgroundColor: status.background,
                            }}
                          />
                        </TableCell>

                        {/* Display the booking action buttons. */}
                        <TableCell align="center">
                          {/* Show approve and reject buttons for pending bookings. */}
                          {booking.booking_status === 0 && (
                            <Stack
                              direction={{
                                xs: "column",
                                sm: "row",
                              }}
                              spacing={1}
                              sx={{
                                justifyContent: "center",
                              }}
                            >
                              <Button
                                size="small"
                                variant="contained"
                                disabled={isActionLoading}
                                startIcon={
                                  isActionLoading ? (
                                    <CircularProgress
                                      size={15}
                                      color="inherit"
                                    />
                                  ) : (
                                    <CheckCircleIcon />
                                  )
                                }
                                onClick={() => handleApprove(booking.id)}
                                sx={{
                                  backgroundColor: "#2e7d32",
                                  "&:hover": {
                                    backgroundColor: "#1b5e20",
                                  },
                                }}
                              >
                                Approve
                              </Button>

                              <Button
                                size="small"
                                variant="outlined"
                                color="error"
                                disabled={isActionLoading}
                                startIcon={<CancelIcon />}
                                onClick={() => handleReject(booking.id)}
                              >
                                Reject
                              </Button>
                            </Stack>
                          )}

                          {/* Show the revert button for approved bookings. */}
                          {booking.booking_status === 1 && (
                            <Stack
                              direction={{
                                xs: "column",
                                sm: "row",
                              }}
                              spacing={1}
                              sx={{
                                justifyContent: "center",
                              }}
                            >
                              <Button
                                size="small"
                                variant="outlined"
                                disabled={isActionLoading}
                                startIcon={
                                  isActionLoading ? (
                                    <CircularProgress
                                      size={15}
                                      color="inherit"
                                    />
                                  ) : (
                                    <UndoIcon />
                                  )
                                }
                                onClick={() => handleRevert(booking.id)}
                                sx={{
                                  color: "#6d3b24",
                                  borderColor: "#6d3b24",
                                  "&:hover": {
                                    borderColor: "#4e2919",
                                    backgroundColor: "#f7eee9",
                                  },
                                }}
                              >
                                Revert
                              </Button>
                            </Stack>
                          )}

                          {/* Show the revert button for rejected bookings. */}
                          {booking.booking_status === 2 && (
                            <Stack
                              direction={{
                                xs: "column",
                                sm: "row",
                              }}
                              spacing={1}
                              sx={{
                                justifyContent: "center",
                              }}
                            >
                              <Button
                                size="small"
                                variant="outlined"
                                disabled={isActionLoading}
                                startIcon={
                                  isActionLoading ? (
                                    <CircularProgress
                                      size={15}
                                      color="inherit"
                                    />
                                  ) : (
                                    <UndoIcon />
                                  )
                                }
                                onClick={() => handleRevert(booking.id)}
                                sx={{
                                  color: "#6d3b24",
                                  borderColor: "#6d3b24",
                                  "&:hover": {
                                    borderColor: "#4e2919",
                                    backgroundColor: "#f7eee9",
                                  },
                                }}
                              >
                                Revert
                              </Button>
                            </Stack>
                          )}
                        </TableCell>

                        {/* Display the email button and its status. */}
                        <TableCell align="center">
                          <Tooltip
                            title={
                              !booking.email
                                ? "Customer email not available"
                                : booking.mail_send_details_id != null
                                  ? `Email sent to ${booking.email}`
                                  : `Send email to ${booking.email}`
                            }
                          >
                            <span>
                              <IconButton
                                size="small"
                                disabled={
                                  !booking.email || emailLoading === booking.id
                                }
                                onClick={() =>
                                  handleEmailClick(
                                    booking.id,
                                    booking.mail_send_details_id,
                                  )
                                }
                                sx={{
                                  color:
                                    booking.mail_send_details_id != null
                                      ? "#2e7d32"
                                      : "#6d3b24",
                                  "&:hover": {
                                    backgroundColor:
                                      booking.mail_send_details_id != null
                                        ? "#e8f5e9"
                                        : "#f7eee9",
                                  },
                                }}
                              >
                                {/* Show the correct email icon. */}
                                {emailLoading === booking.id ? (
                                  <CircularProgress size={20} />
                                ) : booking.mail_send_details_id != null ? (
                                  <MarkEmailReadIcon />
                                ) : (
                                  <EmailIcon />
                                )}
                              </IconButton>
                            </span>
                          </Tooltip>
                        </TableCell>
                      </TableRow>
                    );
                  })}
                </TableBody>
              </Table>
            </TableContainer>
          )}
        </CardContent>
      </Card>

      {/* Display success and error messages. */}
      <Snackbar
        open={snackbar.open}
        autoHideDuration={3000}
        onClose={handleSnackbarClose}
        anchorOrigin={{
          vertical: "bottom",
          horizontal: "right",
        }}
      >
        <Alert
          severity={snackbar.severity}
          variant="filled"
          onClose={handleSnackbarClose}
        >
          {snackbar.message}
        </Alert>
      </Snackbar>
    </Box>
  );
};

export default AdminBookings;
