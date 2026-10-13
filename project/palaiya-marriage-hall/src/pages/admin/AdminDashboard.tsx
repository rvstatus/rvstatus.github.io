import React from "react";
import {
  Box,
  Card,
  CardContent,
  Chip,
  Paper,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Typography,
} from "@mui/material";
import EventIcon from "@mui/icons-material/Event";
import PendingActionsIcon from "@mui/icons-material/PendingActions";
import CheckCircleIcon from "@mui/icons-material/CheckCircle";
import PeopleIcon from "@mui/icons-material/People";

// Store the details of each recent booking.
interface RecentBooking {
  id: number;
  customer_id: string;
  customer_name: string;
  mobile: string;
  event_date: string;
  event_type: string;
  number_of_guests: number | null;
  requirements: string | null;
  booking_status: number;
  created_at: string;
}

// Store the dashboard count and recent booking details.
interface DashboardData {
  totalBookings: number;
  pendingBookings: number;
  approvedBookings: number;
  totalCustomers: number;
  recentBookings: RecentBooking[];
}

const AdminDashboard: React.FC = () => {
  // Store the dashboard data.
  const [dashboardData, setDashboardData] = React.useState<DashboardData>({
    totalBookings: 0,
    pendingBookings: 0,
    approvedBookings: 0,
    totalCustomers: 0,
    recentBookings: [],
  });

  // Store the loading status.
  const [loading, setLoading] = React.useState(true);

  // Get dashboard data from the API when the page loads.
  React.useEffect(() => {
    const fetchDashboardData = async () => {
      try {
        // Show the loading status.
        setLoading(true);

        // Get dashboard data from the backend.
        const response = await fetch("http://localhost:5000/admin/dashboard");

        const result = await response.json();

        // Update the dashboard data if the request is successful.
        if (result.success) {
          setDashboardData(result.data);
        }
      } catch (error) {
        // Show the error in the console if loading fails.
        console.error("Failed to load dashboard data:", error);
      } finally {
        // Stop the loading status.
        setLoading(false);
      }
    };

    // Call the method to load dashboard data.
    fetchDashboardData();
  }, []);

  // Convert the event date into a readable format.
  const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString("en-IN", {
      day: "2-digit",
      month: "short",
      year: "numeric",
    });
  };

  // Get the label and colors based on the booking status.
  const getStatus = (status: number) => {
    // Return the approved status.
    if (status === 1) {
      return {
        label: "Approved",
        color: "#2e7d32",
        background: "#e8f5e9",
      };
    }

    // Return the rejected status.
    if (status === 2) {
      return {
        label: "Rejected",
        color: "#c62828",
        background: "#ffebee",
      };
    }

    // Return the pending status.
    return {
      label: "Pending",
      color: "#ed6c02",
      background: "#fff3e0",
    };
  };

  return (
    <Box>
      {/* Welcome Section */}
      <Box sx={{ mb: 3 }}>
        <Typography
          variant="h5"
          sx={{
            fontWeight: 700,
            color: "#333",
          }}
        >
          Welcome back, Admin
        </Typography>

        <Typography
          variant="body2"
          sx={{
            color: "#777",
            mt: 0.5,
          }}
        >
          Here's what's happening with your marriage hall today.
        </Typography>
      </Box>

      {/* Dashboard Cards */}
      <Box
        sx={{
          display: "grid",
          gridTemplateColumns: {
            xs: "1fr",
            sm: "repeat(2, 1fr)",
            lg: "repeat(4, 1fr)",
          },
          gap: 2,
          mb: 4,
        }}
      >
        {/* Show the total number of bookings */}
        <Card
          elevation={0}
          sx={{
            border: "1px solid #e5e5e5",
            borderRadius: 3,
          }}
        >
          <CardContent>
            <Box
              sx={{
                display: "flex",
                alignItems: "center",
                justifyContent: "space-between",
              }}
            >
              <Box>
                <Typography variant="body2" color="text.secondary">
                  Total Bookings
                </Typography>

                <Typography
                  variant="h4"
                  sx={{
                    mt: 1,
                    fontWeight: 700,
                    color: "#6d3b24",
                  }}
                >
                  {loading ? "..." : dashboardData.totalBookings}
                </Typography>
              </Box>

              <EventIcon
                sx={{
                  fontSize: 42,
                  color: "#8b5a3c",
                }}
              />
            </Box>
          </CardContent>
        </Card>

        {/* Show the total number of pending bookings */}
        <Card
          elevation={0}
          sx={{
            border: "1px solid #e5e5e5",
            borderRadius: 3,
          }}
        >
          <CardContent>
            <Box
              sx={{
                display: "flex",
                alignItems: "center",
                justifyContent: "space-between",
              }}
            >
              <Box>
                <Typography variant="body2" color="text.secondary">
                  Pending Bookings
                </Typography>

                <Typography
                  variant="h4"
                  sx={{
                    mt: 1,
                    fontWeight: 700,
                    color: "#ed6c02",
                  }}
                >
                  {loading ? "..." : dashboardData.pendingBookings}
                </Typography>
              </Box>

              <PendingActionsIcon
                sx={{
                  fontSize: 42,
                  color: "#ed6c02",
                }}
              />
            </Box>
          </CardContent>
        </Card>

        {/* Show the total number of approved bookings */}
        <Card
          elevation={0}
          sx={{
            border: "1px solid #e5e5e5",
            borderRadius: 3,
          }}
        >
          <CardContent>
            <Box
              sx={{
                display: "flex",
                alignItems: "center",
                justifyContent: "space-between",
              }}
            >
              <Box>
                <Typography variant="body2" color="text.secondary">
                  Approved Bookings
                </Typography>

                <Typography
                  variant="h4"
                  sx={{
                    mt: 1,
                    fontWeight: 700,
                    color: "#2e7d32",
                  }}
                >
                  {loading ? "..." : dashboardData.approvedBookings}
                </Typography>
              </Box>

              <CheckCircleIcon
                sx={{
                  fontSize: 42,
                  color: "#2e7d32",
                }}
              />
            </Box>
          </CardContent>
        </Card>

        {/* Show the total number of customers */}
        <Card
          elevation={0}
          sx={{
            border: "1px solid #e5e5e5",
            borderRadius: 3,
          }}
        >
          <CardContent>
            <Box
              sx={{
                display: "flex",
                alignItems: "center",
                justifyContent: "space-between",
              }}
            >
              <Box>
                <Typography variant="body2" color="text.secondary">
                  Total Customers
                </Typography>

                <Typography
                  variant="h4"
                  sx={{
                    mt: 1,
                    fontWeight: 700,
                    color: "#1565c0",
                  }}
                >
                  {loading ? "..." : dashboardData.totalCustomers}
                </Typography>
              </Box>

              <PeopleIcon
                sx={{
                  fontSize: 42,
                  color: "#1565c0",
                }}
              />
            </Box>
          </CardContent>
        </Card>
      </Box>

      {/* Recent Bookings */}
      <Card
        elevation={0}
        sx={{
          border: "1px solid #e5e5e5",
          borderRadius: 3,
        }}
      >
        <CardContent>
          <Typography
            variant="h6"
            sx={{
              fontWeight: 700,
              mb: 2,
            }}
          >
            Recent Bookings
          </Typography>

          {/* Show loading, empty, or booking data */}
          {loading ? (
            <Typography variant="body2" sx={{ color: "#777" }}>
              Loading bookings...
            </Typography>
          ) : dashboardData.recentBookings.length === 0 ? (
            <Typography variant="body2" sx={{ color: "#777" }}>
              No bookings found.
            </Typography>
          ) : (
            // Display recent bookings in a table.
            <TableContainer
              component={Paper}
              elevation={0}
              sx={{
                overflowX: "auto",
              }}
            >
              <Table>
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
                  </TableRow>
                </TableHead>

                <TableBody>
                  {/* Loop through and display each recent booking */}
                  {dashboardData.recentBookings.map((booking) => {
                    // Get the status details for the current booking.
                    const status = getStatus(booking.booking_status);

                    return (
                      <TableRow key={booking.id} hover>
                        {/* Show customer name and mobile number */}
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
                            }}
                          >
                            {booking.mobile}
                          </Typography>
                        </TableCell>

                        {/* Show the event type */}
                        <TableCell>{booking.event_type}</TableCell>

                        {/* Show the formatted event date */}
                        <TableCell>{formatDate(booking.event_date)}</TableCell>

                        {/* Show the number of guests */}
                        <TableCell align="center">
                          {booking.number_of_guests ?? "-"}
                        </TableCell>

                        {/* Show the booking status */}
                        <TableCell align="center">
                          <Chip
                            label={status.label}
                            size="small"
                            sx={{
                              fontWeight: 600,
                              backgroundColor: status.background,
                              color: status.color,
                            }}
                          />
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
    </Box>
  );
};

export default AdminDashboard;
