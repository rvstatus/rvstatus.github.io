import React from "react";
import {
  Alert,
  Box,
  Button,
  Card,
  CardContent,
  CircularProgress,
  InputAdornment,
  Paper,
  Snackbar,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  TextField,
  Typography,
} from "@mui/material";

import SearchIcon from "@mui/icons-material/Search";
import RefreshIcon from "@mui/icons-material/Refresh";
import PeopleIcon from "@mui/icons-material/People";
import EmailIcon from "@mui/icons-material/Email";
import PhoneIcon from "@mui/icons-material/Phone";

interface Customer {
  id: number;
  customer_id: string;
  customer_name: string;
  mobile: string;
  email: string;
  created_at: string;
}

const API_URL = "http://localhost:5000/api/customers";

const Customers: React.FC = () => {
  const [customers, setCustomers] = React.useState<Customer[]>([]);
  const [loading, setLoading] = React.useState(true);
  const [refreshing, setRefreshing] = React.useState(false);
  const [search, setSearch] = React.useState("");

  const [snackbar, setSnackbar] = React.useState({
    open: false,
    message: "",
  });

  // Fetch customers from the API
  const fetchCustomers = async (isRefresh = false): Promise<void> => {
    try {
      if (isRefresh) {
        setRefreshing(true);
      } else {
        setLoading(true);
      }

      const response = await fetch(API_URL);
      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error(result.message || "Failed to load customers");
      }

      setCustomers(result.data || []);
    } catch (error) {
      console.error("Fetch customers error:", error);

      setSnackbar({
        open: true,
        message:
          error instanceof Error ? error.message : "Failed to load customers",
      });
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  // Initial API call
  React.useEffect(() => {
    void fetchCustomers();
  }, []);

  // Format registered date
  const formatDate = (date: string): string => {
    if (!date) {
      return "-";
    }

    const parsedDate = new Date(date);

    if (Number.isNaN(parsedDate.getTime())) {
      return "-";
    }

    return parsedDate.toLocaleString("en-IN", {
      day: "2-digit",
      month: "short",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  };

  // Handle search input change
  const handleSearchChange = (
    event: React.ChangeEvent<HTMLInputElement>,
  ): void => {
    setSearch(event.target.value);
  };

  // Handle refresh button click
  const handleRefreshClick = (): void => {
    void fetchCustomers(true);
  };

  // Handle Snackbar close
  const handleSnackbarClose = (): void => {
    setSnackbar((previous) => ({
      ...previous,
      open: false,
    }));
  };

  // Filter customers based on search text
  const filteredCustomers = customers.filter((customer) => {
    const searchText = search.trim().toLowerCase();

    if (!searchText) {
      return true;
    }

    return [
      customer.customer_id,
      customer.customer_name,
      customer.mobile,
      customer.email,
    ].some((value) => value?.toLowerCase().includes(searchText));
  });

  return (
    <Box>
      {/* Header */}
      <Box
        sx={{
          mb: 3,
          display: "flex",
          justifyContent: "space-between",
          alignItems: { xs: "flex-start", sm: "center" },
          flexDirection: { xs: "column", sm: "row" },
          gap: 2,
        }}
      >
        <Box>
          <Typography variant="h5" sx={{ fontWeight: 700, color: "#333" }}>
            Customers
          </Typography>

          <Typography variant="body2" sx={{ color: "#777", mt: 0.5 }}>
            View and manage registered customers.
          </Typography>
        </Box>

        <Button
          variant="outlined"
          startIcon={
            refreshing ? <CircularProgress size={18} /> : <RefreshIcon />
          }
          disabled={loading || refreshing}
          onClick={handleRefreshClick}
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

      {/* Customer Table */}
      <Card
        elevation={0}
        sx={{
          border: "1px solid #e5e5e5",
          borderRadius: 3,
        }}
      >
        <CardContent>
          {/* Search */}
          <Box
            sx={{
              display: "flex",
              flexDirection: { xs: "column", sm: "row" },
              justifyContent: "space-between",
              alignItems: { xs: "stretch", sm: "center" },
              gap: 2,
              mb: 3,
            }}
          >
            <TextField
              size="small"
              fullWidth
              placeholder="Search name, customer ID, mobile, email..."
              value={search}
              onChange={handleSearchChange}
              slotProps={{
                input: {
                  startAdornment: (
                    <InputAdornment position="start">
                      <SearchIcon />
                    </InputAdornment>
                  ),
                },
              }}
            />

            <Typography
              variant="body2"
              sx={{
                color: "#777",
                whiteSpace: "nowrap",
              }}
            >
              {filteredCustomers.length} customer(s)
            </Typography>
          </Box>

          {/* Loading */}
          {loading ? (
            <Box
              sx={{
                display: "flex",
                justifyContent: "center",
                py: 8,
              }}
            >
              <CircularProgress sx={{ color: "#6d3b24" }} />
            </Box>
          ) : filteredCustomers.length === 0 ? (
            /* Empty State */
            <Box sx={{ textAlign: "center", py: 8 }}>
              <PeopleIcon sx={{ fontSize: 45, color: "#aaa", mb: 1 }} />

              <Typography variant="h6">No customers found</Typography>

              <Typography variant="body2" color="text.secondary">
                {search
                  ? "Try another search term."
                  : "Registered customers will appear here."}
              </Typography>
            </Box>
          ) : (
            /* Customer Data */
            <TableContainer
              component={Paper}
              elevation={0}
              sx={{ overflowX: "auto" }}
            >
              <Table>
                <TableHead>
                  <TableRow sx={{ backgroundColor: "#faf7f5" }}>
                    <TableCell sx={{ fontWeight: 700 }}>Customer ID</TableCell>

                    <TableCell sx={{ fontWeight: 700 }}>
                      Customer Name
                    </TableCell>

                    <TableCell sx={{ fontWeight: 700 }}>Mobile</TableCell>

                    <TableCell sx={{ fontWeight: 700 }}>Email</TableCell>

                    <TableCell sx={{ fontWeight: 700, minWidth: 170 }}>
                      Registered Date
                    </TableCell>
                  </TableRow>
                </TableHead>

                <TableBody>
                  {filteredCustomers.map((customer) => (
                    <TableRow key={customer.id} hover>
                      {/* Customer ID */}
                      <TableCell>
                        <Typography
                          variant="body2"
                          sx={{
                            fontWeight: 600,
                            color: "#6d3b24",
                          }}
                        >
                          {customer.customer_id}
                        </Typography>
                      </TableCell>

                      {/* Customer Name */}
                      <TableCell>
                        <Box
                          sx={{
                            display: "flex",
                            flexDirection: "row",
                            alignItems: "center",
                            gap: 1,
                          }}
                        >
                          <Box
                            sx={{
                              width: 36,
                              height: 36,
                              borderRadius: "50%",
                              backgroundColor: "#f3e7df",
                              color: "#6d3b24",
                              display: "flex",
                              alignItems: "center",
                              justifyContent: "center",
                              fontWeight: 700,
                              flexShrink: 0,
                            }}
                          >
                            {customer.customer_name?.charAt(0).toUpperCase() ||
                              "C"}
                          </Box>

                          <Typography variant="body2" sx={{ fontWeight: 600 }}>
                            {customer.customer_name}
                          </Typography>
                        </Box>
                      </TableCell>

                      {/* Mobile */}
                      <TableCell>
                        <Box
                          sx={{
                            display: "flex",
                            flexDirection: "row",
                            alignItems: "center",
                            gap: 1,
                          }}
                        >
                          <PhoneIcon sx={{ fontSize: 17, color: "#888" }} />

                          <Typography variant="body2">
                            {customer.mobile || "-"}
                          </Typography>
                        </Box>
                      </TableCell>

                      {/* Email */}
                      <TableCell>
                        <Box
                          sx={{
                            display: "flex",
                            flexDirection: "row",
                            alignItems: "center",
                            gap: 1,
                          }}
                        >
                          <EmailIcon sx={{ fontSize: 17, color: "#888" }} />

                          <Typography variant="body2">
                            {customer.email || "-"}
                          </Typography>
                        </Box>
                      </TableCell>

                      {/* Registered Date */}
                      <TableCell>{formatDate(customer.created_at)}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </TableContainer>
          )}
        </CardContent>
      </Card>

      {/* Error Message */}
      <Snackbar
        open={snackbar.open}
        autoHideDuration={4000}
        onClose={handleSnackbarClose}
        anchorOrigin={{
          vertical: "bottom",
          horizontal: "right",
        }}
      >
        <Alert severity="error" variant="filled" onClose={handleSnackbarClose}>
          {snackbar.message}
        </Alert>
      </Snackbar>
    </Box>
  );
};

export default Customers;
