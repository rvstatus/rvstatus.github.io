import React, { useState } from "react";
import {
  Alert,
  Box,
  Button,
  CircularProgress,
  Container,
  IconButton,
  InputAdornment,
  Paper,
  Snackbar,
  TextField,
  Typography,
} from "@mui/material";

import EmailIcon from "@mui/icons-material/Email";
import LockIcon from "@mui/icons-material/Lock";
import VisibilityIcon from "@mui/icons-material/Visibility";
import VisibilityOffIcon from "@mui/icons-material/VisibilityOff";
import { useNavigate } from "react-router-dom";

const AdminLogin: React.FC = () => {
  const navigate = useNavigate();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const [showPassword, setShowPassword] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const [emailError, setEmailError] = useState("");
  const [passwordError, setPasswordError] = useState("");

  const [loginError, setLoginError] = useState("");
  const [snackbarOpen, setSnackbarOpen] = useState(false);

  // email change event
  const handleEmailChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setEmail(event.target.value);

    if (emailError) {
      setEmailError("");
    }

    if (loginError) {
      setLoginError("");
    }
  };

  // password change event
  const handlePasswordChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setPassword(event.target.value);

    if (passwordError) {
      setPasswordError("");
    }

    if (loginError) {
      setLoginError("");
    }
  };

  // SHOW / HIDE password click event
  const handleTogglePassword = () => {
    setShowPassword((previous) => !previous);
  };

  // close snackbar event
  const handleCloseSnackbar = () => {
    setSnackbarOpen(false);
  };

  // login form submit
  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();

    setEmailError("");
    setPasswordError("");
    setLoginError("");

    let hasError = false;

    if (!email.trim()) {
      setEmailError("Email address is required");
      hasError = true;
    }

    if (!password) {
      setPasswordError("Password is required");
      hasError = true;
    }

    if (hasError) {
      return;
    }

    try {
      setIsSubmitting(true);

      const response = await fetch("http://localhost:5000/api/admin/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          email: email.trim(),
          password,
        }),
      });

      const result = await response.json();

      if (!response.ok || !result.success) {
        setLoginError(result.message || "Invalid email or password");
        setSnackbarOpen(true);
        return;
      }

      localStorage.setItem("admin", JSON.stringify(result.admin));

      navigate("/admin/dashboard");
    } catch (error) {
      console.error("Admin login error:", error);

      setLoginError("Unable to connect to the server. Please try again.");
      setSnackbarOpen(true);
    } finally {
      setIsSubmitting(false);
    }
  };

  // BACK TO WEBSITE CLICK EVENT
  const handleBackToWebsite = () => {
    navigate("/");
  };

  return (
    <Box
      sx={{
        minHeight: "100vh",
        width: "100%",
        background:
          "linear-gradient(135deg, #6d3b24 0%, #8b5a3c 50%, #d4af37 100%)",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        boxSizing: "border-box",
        px: 2,
        py: 2,
      }}
    >
      <Snackbar
        open={snackbarOpen}
        autoHideDuration={4000}
        onClose={handleCloseSnackbar}
        anchorOrigin={{
          vertical: "top",
          horizontal: "right",
        }}
        sx={{
          mt: 2,
          mr: 2,
        }}
      >
        <Alert
          onClose={handleCloseSnackbar}
          severity="error"
          variant="filled"
          sx={{
            width: "100%",
            minWidth: 280,
            maxWidth: 420,
            fontWeight: 500,
          }}
        >
          {loginError}
        </Alert>
      </Snackbar>

      <Container
        maxWidth="sm"
        sx={{
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          p: 0,
        }}
      >
        <Paper
          elevation={12}
          sx={{
            width: "100%",
            maxWidth: 500,
            borderRadius: 4,
            overflow: "hidden",
          }}
        >
          {/* Header */}
          <Box
            sx={{
              backgroundColor: "#6d3b24",
              color: "#fff",
              textAlign: "center",
              py: 3,
              px: 3,
            }}
          >
            <Typography
              variant="h4"
              sx={{
                fontWeight: 800,
                fontFamily: "serif",
              }}
            >
              Palaiya Marriage Hall
            </Typography>

            <Typography
              variant="body1"
              sx={{
                mt: 0.5,
                color: "#f5df9b",
              }}
            >
              Admin Portal
            </Typography>
          </Box>

          {/* Login Form */}
          <Box
            component="form"
            onSubmit={handleSubmit}
            sx={{
              p: { xs: 3, sm: 4 },
              pt: { xs: 2.5, sm: 3 },
            }}
          >
            <Typography
              variant="h5"
              sx={{
                fontWeight: 700,
                color: "#6d3b24",
                mb: 0.5,
              }}
            >
              Admin Login
            </Typography>

            <Typography
              variant="body2"
              sx={{
                color: "#777",
                mb: 2,
              }}
            >
              Sign in to manage bookings and customers.
            </Typography>

            {/* Email */}
            <TextField
              fullWidth
              label="Email Address"
              placeholder="Enter your email address"
              type="text"
              value={email}
              onChange={handleEmailChange}
              disabled={isSubmitting}
              autoComplete="email"
              margin="dense"
              error={Boolean(emailError)}
              helperText={emailError || " "}
              slotProps={{
                input: {
                  startAdornment: (
                    <InputAdornment position="start">
                      <EmailIcon sx={{ color: "#6d3b24" }} />
                    </InputAdornment>
                  ),
                },
                formHelperText: {
                  sx: {
                    minHeight: "20px",
                    marginLeft: 0,
                    color: "#d32f2f",
                  },
                },
              }}
            />

            {/* Password */}
            <TextField
              fullWidth
              label="Password"
              placeholder="Enter your password"
              type={showPassword ? "text" : "password"}
              value={password}
              onChange={handlePasswordChange}
              disabled={isSubmitting}
              autoComplete="current-password"
              margin="dense"
              error={Boolean(passwordError)}
              helperText={passwordError || " "}
              slotProps={{
                input: {
                  startAdornment: (
                    <InputAdornment position="start">
                      <LockIcon sx={{ color: "#6d3b24" }} />
                    </InputAdornment>
                  ),
                  endAdornment: (
                    <InputAdornment position="end">
                      <IconButton
                        type="button"
                        onClick={handleTogglePassword}
                        edge="end"
                        disabled={isSubmitting}
                      >
                        {showPassword ? (
                          <VisibilityOffIcon />
                        ) : (
                          <VisibilityIcon />
                        )}
                      </IconButton>
                    </InputAdornment>
                  ),
                },
                formHelperText: {
                  sx: {
                    minHeight: "20px",
                    marginLeft: 0,
                    color: "#d32f2f",
                  },
                },
              }}
            />

            {/* Login Button */}
            <Button
              fullWidth
              type="submit"
              variant="contained"
              size="large"
              disabled={isSubmitting}
              sx={{
                mt: 1,
                py: 1.3,
                borderRadius: 2,
                backgroundColor: "#6d3b24",
                fontWeight: 700,
                "&:hover": {
                  backgroundColor: "#522b1a",
                },
              }}
            >
              {isSubmitting ? (
                <CircularProgress size={25} sx={{ color: "#fff" }} />
              ) : (
                "Login"
              )}
            </Button>

            {/* Back Button */}
            <Button
              fullWidth
              type="button"
              onClick={handleBackToWebsite}
              disabled={isSubmitting}
              sx={{
                mt: 1,
                color: "#6d3b24",
                fontWeight: 600,
              }}
            >
              Back to Website
            </Button>
          </Box>
        </Paper>
      </Container>
    </Box>
  );
};

export default AdminLogin;
