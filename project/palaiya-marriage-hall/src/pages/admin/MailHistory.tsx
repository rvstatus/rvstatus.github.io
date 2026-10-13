import React from "react";
import {
  Alert,
  Box,
  Button,
  Card,
  CardContent,
  Chip,
  CircularProgress,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  IconButton,
  InputAdornment,
  MenuItem,
  Paper,
  Snackbar,
  Stack,
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
import VisibilityIcon from "@mui/icons-material/Visibility";
import MarkEmailReadIcon from "@mui/icons-material/MarkEmailRead";
import EmailIcon from "@mui/icons-material/Email";
import CloseIcon from "@mui/icons-material/Close";

interface MailRecord {
  id: number;
  customer_id: string;
  booking_id: number;
  from_email: string;
  to_email: string;
  subject: string;
  body: string;
  mail_send_status: number;
  mail_send_date: string;
  created_by: string;
  created_at: string;
}

const API_URL = "http://localhost:5000/admin/mail-history";

const MailHistory: React.FC = () => {
  const [records, setRecords] = React.useState<MailRecord[]>([]);
  const [loading, setLoading] = React.useState(true);
  const [search, setSearch] = React.useState("");
  const [status, setStatus] = React.useState("all");
  const [selected, setSelected] = React.useState<MailRecord | null>(null);

  const [snackbar, setSnackbar] = React.useState({
    open: false,
    message: "",
  });

  // Show error message
  const showMessage = (message: string) => {
    setSnackbar({
      open: true,
      message,
    });
  };

  // Close snackbar
  const handleSnackbarClose = () => {
    setSnackbar((previous) => ({
      ...previous,
      open: false,
    }));
  };

  // Fetch mail history
  const fetchHistory = React.useCallback(async () => {
    try {
      setLoading(true);

      const params = new URLSearchParams();

      if (search.trim()) {
        params.set("search", search.trim());
      }

      if (status !== "all") {
        params.set("status", status);
      }

      const response = await fetch(`${API_URL}?${params.toString()}`);
      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error(result.message || "Failed to load mail history");
      }

      setRecords(result.data || []);
    } catch (error) {
      console.error("Fetch mail history error:", error);

      showMessage(
        error instanceof Error ? error.message : "Failed to load mail history",
      );
    } finally {
      setLoading(false);
    }
  }, [search, status]);

  // Load mail history on mount and when filters change
  React.useEffect(() => {
    fetchHistory();
  }, [fetchHistory]);

  // Handle search input change
  const handleSearchChange = (
    event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>,
  ) => {
    setSearch(event.target.value);
  };

  // Handle search Enter key
  const handleSearchKeyDown = (event: React.KeyboardEvent<HTMLDivElement>) => {
    if (event.key === "Enter") {
      fetchHistory();
    }
  };

  // Handle status filter change
  const handleStatusChange = (
    event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>,
  ) => {
    setStatus(event.target.value);
  };

  // Handle refresh button click
  const handleRefresh = () => {
    fetchHistory();
  };

  // Open selected mail details
  const handleViewDetails = (record: MailRecord) => {
    setSelected(record);
  };

  // Close mail details dialog
  const handleCloseDetails = () => {
    setSelected(null);
  };

  // Format date
  const formatDate = (date: string) => {
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

  // Get email status
  const getStatus = (value: number) => {
    if (value === 1) {
      return {
        label: "Sent",
        color: "#2e7d32",
        background: "#e8f5e9",
      };
    }

    if (value === 2) {
      return {
        label: "Failed",
        color: "#c62828",
        background: "#ffebee",
      };
    }

    return {
      label: "Not Sent",
      color: "#ed6c02",
      background: "#fff3e0",
    };
  };

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
            Mail Send History
          </Typography>

          <Typography variant="body2" sx={{ color: "#777", mt: 0.5 }}>
            View booking email logs and delivery attempts.
          </Typography>
        </Box>

        <Button
          variant="outlined"
          startIcon={<RefreshIcon />}
          disabled={loading}
          onClick={handleRefresh}
          sx={{
            color: "#6d3b24",
            borderColor: "#6d3b24",
          }}
        >
          Refresh
        </Button>
      </Box>

      {/* Mail History Card */}
      <Card
        elevation={0}
        sx={{
          border: "1px solid #e5e5e5",
          borderRadius: 3,
        }}
      >
        <CardContent>
          {/* Search and Filter */}
          <Stack
            direction={{ xs: "column", sm: "row" }}
            spacing={2}
            sx={{ mb: 3 }}
          >
            <TextField
              fullWidth
              size="small"
              placeholder="Search email, customer ID, booking ID..."
              value={search}
              onChange={handleSearchChange}
              onKeyDown={handleSearchKeyDown}
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

            <TextField
              select
              size="small"
              label="Email Status"
              value={status}
              onChange={handleStatusChange}
              sx={{ minWidth: { sm: 180 } }}
            >
              <MenuItem value="all">All Statuses</MenuItem>
              <MenuItem value="1">Sent</MenuItem>
              <MenuItem value="0">Not Sent</MenuItem>
              <MenuItem value="2">Failed</MenuItem>
            </TextField>
          </Stack>

          <Typography variant="body2" sx={{ mb: 2, color: "#777" }}>
            {records.length} email record(s)
          </Typography>

          {/* Loading */}
          {loading ? (
            <Box sx={{ display: "flex", justifyContent: "center", py: 8 }}>
              <CircularProgress sx={{ color: "#6d3b24" }} />
            </Box>
          ) : records.length === 0 ? (
            /* Empty State */
            <Box sx={{ textAlign: "center", py: 8 }}>
              <EmailIcon sx={{ fontSize: 45, color: "#aaa", mb: 1 }} />

              <Typography variant="h6">No email records found</Typography>

              <Typography variant="body2" color="text.secondary">
                Email history will appear here after records are created.
              </Typography>
            </Box>
          ) : (
            /* Mail History Table */
            <TableContainer
              component={Paper}
              elevation={0}
              sx={{ overflowX: "auto" }}
            >
              <Table>
                <TableHead>
                  <TableRow sx={{ backgroundColor: "#faf7f5" }}>
                    <TableCell sx={{ fontWeight: 700 }}>ID</TableCell>
                    <TableCell sx={{ fontWeight: 700 }}>Booking</TableCell>
                    <TableCell sx={{ fontWeight: 700 }}>Recipient</TableCell>
                    <TableCell sx={{ fontWeight: 700 }}>Subject</TableCell>
                    <TableCell sx={{ fontWeight: 700 }}>Status</TableCell>
                    <TableCell sx={{ fontWeight: 700 }}>Mail Date</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 700 }}>
                      Details
                    </TableCell>
                  </TableRow>
                </TableHead>

                <TableBody>
                  {records.map((record) => {
                    const mailStatus = getStatus(record.mail_send_status);

                    return (
                      <TableRow key={record.id} hover>
                        <TableCell>{record.id}</TableCell>

                        <TableCell>#{record.booking_id}</TableCell>

                        <TableCell>
                          <Typography variant="body2">
                            {record.to_email}
                          </Typography>

                          <Typography variant="caption" color="text.secondary">
                            {record.customer_id}
                          </Typography>
                        </TableCell>

                        <TableCell sx={{ minWidth: 200 }}>
                          {record.subject}
                        </TableCell>

                        <TableCell>
                          <Chip
                            icon={
                              record.mail_send_status === 1 ? (
                                <MarkEmailReadIcon />
                              ) : (
                                <EmailIcon />
                              )
                            }
                            label={mailStatus.label}
                            size="small"
                            sx={{
                              color: mailStatus.color,
                              backgroundColor: mailStatus.background,
                              fontWeight: 600,
                            }}
                          />
                        </TableCell>

                        <TableCell sx={{ minWidth: 160 }}>
                          {formatDate(record.mail_send_date)}
                        </TableCell>

                        <TableCell align="center">
                          <Button
                            size="small"
                            startIcon={<VisibilityIcon />}
                            onClick={() => handleViewDetails(record)}
                            sx={{ color: "#6d3b24" }}
                          >
                            View
                          </Button>
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

      {/* Mail Details Dialog */}
      <Dialog
        open={Boolean(selected)}
        onClose={handleCloseDetails}
        fullWidth
        maxWidth="md"
      >
        <DialogTitle
          sx={{
            display: "flex",
            alignItems: "center",
            justifyContent: "space-between",
            pr: 2,
          }}
        >
          <Typography variant="h6" sx={{ fontWeight: 700 }}>
            Mail Details
          </Typography>

          <IconButton
            onClick={handleCloseDetails}
            aria-label="Close mail details"
            sx={{ color: "#6d3b24" }}
          >
            <CloseIcon />
          </IconButton>
        </DialogTitle>

        <DialogContent dividers>
          {selected && (
            <Stack spacing={2.5}>
              {/* Email Information Card */}
              <Paper
                variant="outlined"
                sx={{
                  p: 2,
                  borderRadius: 2,
                  borderColor: "#eaded6",
                  backgroundColor: "#fffdfb",
                }}
              >
                <Stack
                  direction="row"
                  sx={{
                    alignItems: "center",
                    justifyContent: "space-between",
                    mb: 2,
                  }}
                >
                  <Typography
                    variant="subtitle1"
                    sx={{ fontWeight: 700, color: "#6d3b24" }}
                  >
                    Email Information
                  </Typography>

                  <Chip
                    size="small"
                    label={getStatus(selected.mail_send_status).label}
                    sx={{
                      color: getStatus(selected.mail_send_status).color,
                      backgroundColor: getStatus(selected.mail_send_status)
                        .background,
                      fontWeight: 600,
                    }}
                  />
                </Stack>

                <Box
                  sx={{
                    display: "grid",
                    gridTemplateColumns: {
                      xs: "100px minmax(0, 1fr)",
                      sm: "100px minmax(0, 1fr) 100px minmax(0, 1fr)",
                    },
                    columnGap: 2,
                    rowGap: 2,
                    alignItems: "start",
                  }}
                >
                  {/* Booking ID */}
                  <Typography variant="body2" color="text.secondary">
                    Booking ID
                  </Typography>

                  <Typography variant="body2" sx={{ fontWeight: 600 }}>
                    #{selected.booking_id}
                  </Typography>

                  {/* Desktop Mail Date */}
                  <Typography
                    variant="body2"
                    color="text.secondary"
                    sx={{ display: { xs: "none", sm: "block" } }}
                  >
                    Mail Date
                  </Typography>

                  <Typography
                    variant="body2"
                    sx={{
                      display: { xs: "none", sm: "block" },
                      overflowWrap: "anywhere",
                    }}
                  >
                    {formatDate(selected.mail_send_date)}
                  </Typography>

                  {/* From */}
                  <Typography variant="body2" color="text.secondary">
                    From
                  </Typography>

                  <Typography variant="body2" sx={{ overflowWrap: "anywhere" }}>
                    {selected.from_email}
                  </Typography>

                  {/* To */}
                  <Typography variant="body2" color="text.secondary">
                    To
                  </Typography>

                  <Typography variant="body2" sx={{ overflowWrap: "anywhere" }}>
                    {selected.to_email}
                  </Typography>

                  {/* Subject */}
                  <Typography variant="body2" color="text.secondary">
                    Subject
                  </Typography>

                  <Typography
                    variant="body2"
                    sx={{
                      gridColumn: { xs: "2", sm: "2 / 5" },
                      fontWeight: 600,
                      overflowWrap: "anywhere",
                    }}
                  >
                    {selected.subject}
                  </Typography>

                  {/* Mobile Mail Date */}
                  <Typography
                    variant="body2"
                    color="text.secondary"
                    sx={{ display: { xs: "block", sm: "none" } }}
                  >
                    Mail Date
                  </Typography>

                  <Typography
                    variant="body2"
                    sx={{
                      display: { xs: "block", sm: "none" },
                      overflowWrap: "anywhere",
                    }}
                  >
                    {formatDate(selected.mail_send_date)}
                  </Typography>
                </Box>
              </Paper>

              {/* Email Preview */}
              <Box>
                <Typography
                  variant="subtitle1"
                  sx={{ fontWeight: 700, color: "#6d3b24", mb: 1 }}
                >
                  Email Preview
                </Typography>

                <Paper
                  variant="outlined"
                  sx={{
                    p: 2,
                    maxHeight: 500,
                    overflow: "auto",
                    backgroundColor: "#f5f5f5",
                  }}
                >
                  <Box
                    sx={{
                      maxWidth: 650,
                      mx: "auto",
                      p: { xs: 2, sm: 4 },
                      backgroundColor: "#ffffff",
                      border: "1px solid #e5e5e5",
                      borderRadius: 2,
                      overflowWrap: "anywhere",
                      "& img": {
                        maxWidth: "100%",
                        height: "auto",
                      },
                      "& table": {
                        maxWidth: "100%",
                      },
                    }}
                  >
                    {/<\/?[a-z][\s\S]*?>/i.test(selected.body) ? (
                      <iframe
                        title="Stored email preview"
                        srcDoc={selected.body}
                        sandbox=""
                        style={{
                          width: "100%",
                          minHeight: 350,
                          border: "none",
                          backgroundColor: "#ffffff",
                        }}
                      />
                    ) : (
                      <Typography
                        sx={{
                          whiteSpace: "pre-wrap",
                          overflowWrap: "anywhere",
                          fontSize: 14,
                          lineHeight: 1.8,
                          color: "#333",
                        }}
                      >
                        {selected.body}
                      </Typography>
                    )}
                  </Box>
                </Paper>
              </Box>
            </Stack>
          )}
        </DialogContent>

        <DialogActions>
          <Button onClick={handleCloseDetails} sx={{ color: "#6d3b24" }}>
            Close
          </Button>
        </DialogActions>
      </Dialog>

      {/* Error Snackbar */}
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

export default MailHistory;
