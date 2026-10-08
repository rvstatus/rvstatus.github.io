import React from "react";
import {
  Alert,
  Box,
  Button,
  MenuItem,
  Paper,
  Snackbar,
  TextField,
  Typography,
} from "@mui/material";

interface FormData {
  name: string;
  phone: string;
  email: string;
  date: string;
  eventType: string;
  numberOfGuests: string;
  requirements: string;
}

interface FormErrors {
  name: string;
  phone: string;
  email: string;
  date: string;
  eventType: string;
  numberOfGuests: string;
}

interface BookingFormProps {
  title?: string;
  formData: FormData;
  formErrors: FormErrors;
  bookingSuccess: boolean;
  bookingError: string;
  bookingMessage: string;
  bookingMessageType: "success" | "info" | "warning" | "error";
  isSubmitting: boolean;
  handleFormChange: (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>,
  ) => void;
  handleBookingSubmit: (e: React.FormEvent) => void;
  setBookingMessage: React.Dispatch<React.SetStateAction<string>>;
}

const BookingForm: React.FC<BookingFormProps> = ({
  title,
  formData,
  formErrors,
  bookingSuccess,
  bookingError,
  bookingMessage,
  bookingMessageType,
  isSubmitting,
  handleFormChange,
  handleBookingSubmit,
  setBookingMessage,
}) => {
  return (
    <Paper
      elevation={3}
      sx={{
        p: 4,
        borderRadius: 4,
        backgroundColor: "#ffffff",
      }}
    >
      {title && (
        <Typography
          variant="h5"
          sx={{
            fontWeight: 700,
            color: "#6d3b24",
            mb: 3,
          }}
        >
          {title}
        </Typography>
      )}

      {bookingSuccess && (
        <Alert severity="success" sx={{ mb: 3 }}>
          Enquiry submitted successfully! We will contact you shortly.
        </Alert>
      )}

      {bookingError && (
        <Alert severity="error" sx={{ mb: 2 }}>
          {bookingError}
        </Alert>
      )}

      <Snackbar
        open={!!bookingMessage}
        autoHideDuration={7000}
        onClose={() => setBookingMessage("")}
        anchorOrigin={{
          vertical: "top",
          horizontal: "center",
        }}
      >
        <Alert
          onClose={() => setBookingMessage("")}
          severity={bookingMessageType}
          variant="filled"
          sx={{
            width: "100%",
            minWidth: {
              xs: "90vw",
              sm: "500px",
            },
            fontSize: "1rem",
            fontWeight: 600,
            boxShadow: 6,
          }}
        >
          {bookingMessage}
        </Alert>
      </Snackbar>

      <form onSubmit={handleBookingSubmit}>
        <Box
          sx={{
            display: "flex",
            flexDirection: "column",
            gap: 2,
          }}
        >
          {/* Full Name */}
          <TextField
            fullWidth
            label="Full Name"
            name="name"
            value={formData.name}
            onChange={handleFormChange}
            error={!!formErrors.name}
            helperText={formErrors.name}
          />

          {/* Phone */}
          <TextField
            fullWidth
            label="Phone Number"
            name="phone"
            value={formData.phone}
            onChange={handleFormChange}
            error={!!formErrors.phone}
            helperText={formErrors.phone}
          />

          {/* Email */}
          <TextField
            fullWidth
            label="Email"
            name="email"
            type="email"
            value={formData.email}
            onChange={handleFormChange}
            error={!!formErrors.email}
            helperText={formErrors.email}
          />

          {/* Date + Event Type */}
          <Box
            sx={{
              display: "flex",
              gap: 2,
              flexWrap: {
                xs: "wrap",
                sm: "nowrap",
              },
            }}
          >
            <Box
              sx={{
                flex: 1,
                width: "100%",
              }}
            >
              <TextField
                fullWidth
                type="date"
                label="Event Date"
                name="date"
                value={formData.date}
                onChange={handleFormChange}
                error={!!formErrors.date}
                helperText={formErrors.date}
                slotProps={{
                  inputLabel: {
                    shrink: true,
                  },
                }}
              />
            </Box>

            <Box
              sx={{
                flex: 1,
                width: "100%",
              }}
            >
              <TextField
                select
                fullWidth
                label="Event Type"
                name="eventType"
                value={formData.eventType}
                onChange={handleFormChange}
                error={!!formErrors.eventType}
                helperText={formErrors.eventType}
              >
                <MenuItem value="Marriage">Marriage</MenuItem>
                <MenuItem value="Reception">Reception</MenuItem>
                <MenuItem value="Engagement">Engagement</MenuItem>
                <MenuItem value="Birthday / Party">Birthday / Party</MenuItem>
                <MenuItem value="Other">Other</MenuItem>
              </TextField>
            </Box>
          </Box>

          {/* Number of Guests */}
          <TextField
            fullWidth
            label="Number of Guests"
            name="numberOfGuests"
            type="number"
            value={formData.numberOfGuests}
            onChange={handleFormChange}
            error={!!formErrors.numberOfGuests}
            helperText={formErrors.numberOfGuests}
          />

          {/* Requirements */}
          <TextField
            fullWidth
            multiline
            rows={3}
            label="Additional Requirements (Rooms, Parking, etc.)"
            name="requirements"
            value={formData.requirements}
            onChange={handleFormChange}
          />

          {/* Submit */}
          <Button
            fullWidth
            type="submit"
            variant="contained"
            disabled={isSubmitting}
            size="large"
            sx={{
              backgroundColor: "#6d3b24",
              fontWeight: 700,
              py: 1.5,
              "&:hover": {
                backgroundColor: "#522b1a",
              },
              borderRadius: "25px",
              mt: 1,
            }}
          >
            Submit Enquiry
          </Button>
        </Box>
      </form>
    </Paper>
  );
};

export default BookingForm;
