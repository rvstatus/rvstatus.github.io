import React, { useState } from "react";
import {
  AppBar,
  Toolbar,
  Typography,
  Button,
  Box,
  Container,
  Card,
  CardMedia,
  CardContent,
  CardActions,
  Tabs,
  Tab,
  IconButton,
  Drawer,
  List,
  ListItem,
  ListItemButton,
  ListItemText,
  Divider,
  TextField,
  MenuItem,
  Modal,
  Alert,
  Paper,
  Chip,
} from "@mui/material";
import MenuIcon from "@mui/icons-material/Menu";
import CloseIcon from "@mui/icons-material/Close";
import LocationOnIcon from "@mui/icons-material/LocationOn";
import PhoneIcon from "@mui/icons-material/Phone";
import EmailIcon from "@mui/icons-material/Email";
import CheckCircleIcon from "@mui/icons-material/CheckCircle";
import AcUnitIcon from "@mui/icons-material/AcUnit";
import DirectionsCarIcon from "@mui/icons-material/DirectionsCar";
import RestaurantIcon from "@mui/icons-material/Restaurant";
import SecurityIcon from "@mui/icons-material/Security";
import PowerIcon from "@mui/icons-material/Power";
import WaterDropIcon from "@mui/icons-material/WaterDrop";
import StarRateIcon from "@mui/icons-material/StarRate";
import BookingForm from "./components/BookingForm";

interface Room {
  id: number;
  name: string;
  type: "ac" | "non-ac";
  price: string;
  capacity: string;
  image: string;
  features: string[];
}

interface GalleryItem {
  id: number;
  title: string;
  category: "Hall" | "Rooms" | "Dining" | "Parking";
  image: string;
}

const Home: React.FC = () => {
  const [mobileOpen, setMobileOpen] = useState(false);
  const [roomTab, setRoomTab] = useState(0);
  const [galleryFilter, setGalleryFilter] = useState<string>("All");
  const [modalOpen, setModalOpen] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [bookingSuccess, setBookingSuccess] = useState(false);
  const [bookingError, setBookingError] = useState("");
  const [bookingMessage, setBookingMessage] = useState("");
  const [bookingMessageType, setBookingMessageType] = useState<
    "success" | "info" | "warning" | "error"
  >("success");
  const showBookingMessage = (
    message: string,
    type: "success" | "info" | "warning" | "error",
  ) => {
    setBookingMessage(message);
    setBookingMessageType(type);
  };
  const resetBookingForm = () => {
    setFormData({
      name: "",
      phone: "",
      email: "",
      date: "",
      eventType: "",
      numberOfGuests: "",
      requirements: "",
    });

    setFormErrors({
      name: "",
      phone: "",
      email: "",
      date: "",
      eventType: "",
      numberOfGuests: "",
    });
  };

  const [formData, setFormData] = useState({
    name: "",
    phone: "",
    email: "",
    date: "",
    eventType: "",
    numberOfGuests: "",
    requirements: "",
  });
  const [formErrors, setFormErrors] = useState({
    name: "",
    phone: "",
    email: "",
    date: "",
    eventType: "",
    numberOfGuests: "",
  });
  const validateBookingForm = () => {
    const errors = {
      name: "",
      phone: "",
      email: "",
      date: "",
      eventType: "",
      numberOfGuests: "",
    };

    const name = formData.name.trim();
    const phone = formData.phone.trim();
    const email = formData.email.trim();
    const date = formData.date;
    const guests = formData.numberOfGuests.trim();

    // Full Name
    if (!name) {
      errors.name = "Full name is required";
    } else if (name.length < 2) {
      errors.name = "Full name must be at least 2 characters";
    }

    // Phone
    if (!phone) {
      errors.phone = "Phone number is required";
    } else if (!/^[0-9]{10}$/.test(phone)) {
      errors.phone = "Enter a valid 10-digit phone number";
    }

    // Email
    if (!email) {
      errors.email = "email is required";
    } else if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      errors.email = "Enter a valid email address";
    }

    // Event Date
    if (!date) {
      errors.date = "Event date is required";
    } else {
      const selectedDate = new Date(`${date}T00:00:00`);
      const today = new Date();

      today.setHours(0, 0, 0, 0);

      if (selectedDate < today) {
        errors.date = "Event date cannot be in the past";
      }
    }

    // Event Type
    if (!formData.eventType) {
      errors.eventType = "Event type is required";
    }

    // Number of Guests - optional
    // if (!guests) {
    //   errors.numberOfGuests = "Number of guests is required";
    // } else
    if (guests) {
      if (!/^[0-9]+$/.test(guests)) {
        errors.numberOfGuests = "Enter a valid number of guests";
      } else if (Number(guests) < 1) {
        errors.numberOfGuests = "Number of guests must be at least 1";
      }
    }

    setFormErrors(errors);

    return !Object.values(errors).some((error) => error !== "");
  };

  const [checkDate, setCheckDate] = useState("");
  const [availabilityStatus, setAvailabilityStatus] = useState<string | null>(
    null,
  );

  const handleDrawerToggle = () => {
    setMobileOpen(!mobileOpen);
  };

  const handleFormChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>,
  ) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleBookingSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    // Clear only old messages.
    // Do NOT clear the form here.
    setBookingSuccess(false);
    setBookingMessage("");

    // ---------------------------------------
    // 1. Frontend validation
    // ---------------------------------------

    const isValid = validateBookingForm();

    if (!isValid) {
      return;
    }

    try {
      setIsSubmitting(true);
      // ---------------------------------------
      // 2. Prepare request data
      // ---------------------------------------

      const requestData = {
        customer_name: formData.name.trim(),
        mobile: formData.phone.trim(),
        email: formData.email.trim(),
        event_date: formData.date,
        event_type: formData.eventType,
        number_of_guests:
          formData.numberOfGuests.trim() === ""
            ? null
            : Number(formData.numberOfGuests),
        requirements: formData.requirements.trim(),
      };

      console.log("Submitting booking:", requestData);

      // ---------------------------------------
      // 3. Send request to backend
      // ---------------------------------------

      const response = await fetch("http://localhost:5000/api/bookings", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(requestData),
      });

      // ---------------------------------------
      // 4. Read backend response safely
      // ---------------------------------------

      const result = await response.json();

      console.log("Booking API response:", result);

      // ---------------------------------------
      // 5. SAME CUSTOMER ALREADY BOOKED
      // ---------------------------------------

      if (result.code === "ALREADY_BOOKED_BY_YOU") {
        setBookingSuccess(false);

        showBookingMessage(
          result.message ||
            "You have already booked this date. We cannot create another booking for the same date.",
          "info",
        );

        // IMPORTANT:
        // Do NOT clear the form.
        // Customer can see/change their entered details.

        return;
      }

      // ---------------------------------------
      // 6. OTHER CUSTOMER - APPROVED BOOKING
      // ---------------------------------------

      if (result.code === "DATE_ALREADY_BOOKED") {
        setBookingSuccess(false);

        showBookingMessage(
          result.message ||
            "This date is already booked by another customer. Please try another date.",
          "warning",
        );

        // IMPORTANT:
        // Do NOT clear the form.
        // Customer can simply change the date.

        return;
      }

      // ---------------------------------------
      // 7. OTHER CUSTOMER - PENDING BOOKING
      // ---------------------------------------

      // 7.1 SAME CUSTOMER - PENDING BOOKING
      if (result.code === "PENDING_ALREADY_SUBMITTED") {
        setBookingSuccess(false);

        showBookingMessage(
          result.message ||
            "You have already submitted an enquiry for this date. Please wait for admin confirmation.",
          "info",
        );

        // IMPORTANT:
        // Booking was NOT created.
        // Do NOT clear the form.
        // Do NOT close the modal.
        return;
      }
      if (result.code === "PENDING_DATE") {
        setBookingSuccess(false);

        showBookingMessage(
          result.message ||
            "Your enquiry has been submitted. This date already has a pending enquiry. Please contact admin as soon as possible.",
          "warning",
        );

        // Backend CREATED the booking.
        // Therefore clear the form.
        resetBookingForm();

        return;
      }

      // ---------------------------------------
      // 8. NORMAL SUCCESS
      // ---------------------------------------

      if (result.code === "BOOKING_CREATED") {
        setBookingSuccess(true);

        showBookingMessage(
          result.message || "Customer and booking registered successfully.",
          "success",
        );

        // Backend successfully created customer + booking.
        resetBookingForm();

        // Close modal only if this form was submitted from modal.
        setTimeout(() => {
          setBookingSuccess(false);
          setBookingMessage("");
          setModalOpen(false);
        }, 3000);

        return;
      }

      // ---------------------------------------
      // 9. BACKEND VALIDATION / OTHER ERROR
      // ---------------------------------------

      if (!response.ok || result.success === false) {
        throw new Error(result.message || "Booking registration failed");
      }

      // ---------------------------------------
      // 10. UNKNOWN RESPONSE
      // ---------------------------------------

      throw new Error("Unexpected response from booking server");
    } catch (error) {
      console.error("Booking submission error:", error);

      setBookingSuccess(false);

      const message =
        error instanceof Error ? error.message : "Failed to register booking";

      showBookingMessage(message, "error");

      // IMPORTANT:
      // Do NOT clear form on server/network error.
      // Customer can retry.
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleCheckAvailability = (e: React.FormEvent) => {
    e.preventDefault();
    if (!checkDate) return;
    setAvailabilityStatus(
      `Great news! Palaiya Marriage Hall is available on ${checkDate}. Book now to secure your date.`,
    );
  };

  const roomsData: Room[] = [
    {
      id: 1,
      name: "Royal AC Suite 101",
      type: "ac",
      price: "₹2,500 / day",
      capacity: "3 Guests",
      image:
        "https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=600&q=80",
      features: ["King Size Bed", "Attached Bathroom", "Smart TV", "Intercom"],
    },
    {
      id: 2,
      name: "Deluxe AC Room 102",
      type: "ac",
      price: "₹2,200 / day",
      capacity: "2 Guests",
      image:
        "https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80",
      features: [
        "Queen Size Bed",
        "AC & Air Circulator",
        "Wardrobe",
        "Hot Water",
      ],
    },
    {
      id: 3,
      name: "Premium AC Room 103",
      type: "ac",
      price: "₹2,200 / day",
      capacity: "2 Guests",
      image:
        "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80",
      features: ["Attached Modern Bath", "AC", "Free Wi-Fi", "Room Service"],
    },
    {
      id: 4,
      name: "Executive AC Room 104",
      type: "ac",
      price: "₹2,000 / day",
      capacity: "2 Guests",
      image:
        "https://images.unsplash.com/photo-1595576508898-0ad5c879a061?auto=format&fit=crop&w=600&q=80",
      features: ["Comfortable Bedding", "AC", "Power Backup", "Table & Chair"],
    },
    {
      id: 5,
      name: "Comfort AC Room 105",
      type: "ac",
      price: "₹2,000 / day",
      capacity: "2 Guests",
      image:
        "https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=600&q=80",
      features: [
        "Air Conditioned",
        "Clean Linens",
        "Sanitized Daily",
        "Intercom",
      ],
    },
    {
      id: 6,
      name: "Standard Non-AC Room 201",
      type: "non-ac",
      price: "₹1,200 / day",
      capacity: "2 Guests",
      image:
        "https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=600&q=80",
      features: ["Spacious Ventilation", "Attached Bathroom", "Table", "Fan"],
    },
    {
      id: 7,
      name: "Standard Non-AC Room 202",
      type: "non-ac",
      price: "₹1,200 / day",
      capacity: "2 Guests",
      image:
        "https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=600&q=80",
      features: ["Twin Beds", "Natural Lighting", "Wardrobe", "Clean Washroom"],
    },
    {
      id: 8,
      name: "Economy Non-AC Room 203",
      type: "non-ac",
      price: "₹1,000 / day",
      capacity: "2 Guests",
      image:
        "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=600&q=80",
      features: [
        "Comfortable Beds",
        "Ceiling Fan",
        "Daily Housekeeping",
        "Power Backup",
      ],
    },
    {
      id: 9,
      name: "Economy Non-AC Room 204",
      type: "non-ac",
      price: "₹1,000 / day",
      capacity: "2 Guests",
      image:
        "https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80",
      features: [
        "Peaceful Ambiance",
        "Attached Bathroom",
        "Writing Desk",
        "Chairs",
      ],
    },
    {
      id: 10,
      name: "Budget Non-AC Room 205",
      type: "non-ac",
      price: "₹900 / day",
      capacity: "2 Guests",
      image:
        "https://images.unsplash.com/photo-1568495248636-6432b97bd949?auto=format&fit=crop&w=600&q=80",
      features: [
        "Clean & Tidy",
        "Essential Furnishing",
        "Secure Lock",
        "Good Ventilation",
      ],
    },
  ];

  const galleryData: GalleryItem[] = [
    {
      id: 1,
      title: "Grand Marriage Mandapam",
      category: "Hall",
      image:
        "https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80",
    },
    {
      id: 2,
      title: "Reception Stage Lighting",
      category: "Hall",
      image:
        "https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?auto=format&fit=crop&w=600&q=80",
    },
    {
      id: 3,
      title: "Luxury AC Suite Interior",
      category: "Rooms",
      image:
        "https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=600&q=80",
    },
    {
      id: 4,
      title: "Spacious Dining Hall",
      category: "Dining",
      image:
        "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80",
    },
    {
      id: 5,
      title: "20-Car Secure Parking Area",
      category: "Parking",
      image:
        "https://images.unsplash.com/photo-1506521781263-d8422e82f27a?auto=format&fit=crop&w=600&q=80",
    },
    {
      id: 6,
      title: "Standard Non-AC Room",
      category: "Rooms",
      image:
        "https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=600&q=80",
    },
  ];

  const filteredGallery =
    galleryFilter === "All"
      ? galleryData
      : galleryData.filter((item) => item.category === galleryFilter);

  return (
    <Box
      sx={{ backgroundColor: "#faf7f2", minHeight: "100vh", color: "#2d2d2d" }}
    >
      {/* HEADER & NAVIGATION */}
      <AppBar
        position="sticky"
        sx={{
          backgroundColor: "#ffffff",
          color: "#6d3b24",
          boxShadow: "0 2px 10px rgba(0,0,0,0.05)",
        }}
      >
        <Container maxWidth="lg">
          <Toolbar disableGutters sx={{ justifyContent: "space-between" }}>
            <Typography
              variant="h6"
              component="a"
              href="#"
              sx={{
                fontWeight: 800,
                color: "#6d3b24",
                textDecoration: "none",
                fontFamily: "serif",
                letterSpacing: ".5px",
              }}
            >
              Palaiya Marriage Hall
            </Typography>

            {/* Desktop Menu */}
            <Box
              sx={{
                display: { xs: "none", md: "flex" },
                gap: 3,
                alignItems: "center",
              }}
            >
              <Button href="#about" color="inherit" sx={{ fontWeight: 600 }}>
                About
              </Button>
              <Button href="#hall" color="inherit" sx={{ fontWeight: 600 }}>
                Hall
              </Button>
              <Button href="#rooms" color="inherit" sx={{ fontWeight: 600 }}>
                Rooms (10)
              </Button>
              <Button
                href="#facilities"
                color="inherit"
                sx={{ fontWeight: 600 }}
              >
                Facilities
              </Button>
              <Button href="#gallery" color="inherit" sx={{ fontWeight: 600 }}>
                Gallery
              </Button>
              <Button href="#contact" color="inherit" sx={{ fontWeight: 600 }}>
                Contact
              </Button>
              <Button
                variant="contained"
                onClick={() => {
                  setBookingSuccess(false);
                  setBookingError("");
                  setModalOpen(true);
                }}
                sx={{
                  backgroundColor: "#6d3b24",
                  color: "#fff",
                  fontWeight: 600,
                  "&:hover": { backgroundColor: "#522b1a" },
                  borderRadius: "20px",
                  px: 3,
                }}
              >
                Enquire Now
              </Button>
            </Box>

            {/* Mobile Menu Button */}
            <IconButton
              color="inherit"
              aria-label="open drawer"
              edge="start"
              onClick={handleDrawerToggle}
              sx={{ display: { md: "none" }, color: "#6d3b24" }}
            >
              <MenuIcon />
            </IconButton>
          </Toolbar>
        </Container>
      </AppBar>

      {/* Mobile Navigation Drawer */}
      <Drawer anchor="right" open={mobileOpen} onClose={handleDrawerToggle}>
        <Box
          sx={{ width: 260, p: 2 }}
          role="presentation"
          onClick={handleDrawerToggle}
        >
          <Box
            sx={{
              display: "flex",
              justifyContent: "space-between",
              alignItems: "center",
              mb: 2,
            }}
          >
            <Typography variant="h6" sx={{ fontWeight: 700, color: "#6d3b24" }}>
              Menu
            </Typography>
            <IconButton>
              <CloseIcon />
            </IconButton>
          </Box>
          <Divider sx={{ mb: 2 }} />
          <List>
            {["About", "Hall", "Rooms", "Facilities", "Gallery", "Contact"].map(
              (text) => (
                <ListItem key={text} disablePadding>
                  <ListItemButton
                    component="a"
                    href={`#${text.toLowerCase().replace(/\s+/g, "")}`}
                  >
                    <ListItemText primary={text} />
                  </ListItemButton>
                </ListItem>
              ),
            )}
            <ListItem disablePadding sx={{ mt: 2 }}>
              <Button
                fullWidth
                variant="contained"
                onClick={() => {
                  setBookingSuccess(false);
                  setBookingError("");
                  setModalOpen(true);
                }}
                sx={{
                  backgroundColor: "#6d3b24",
                  color: "#fff",
                  borderRadius: "20px",
                }}
              >
                Enquire Now
              </Button>
            </ListItem>
          </List>
        </Box>
      </Drawer>

      {/* HERO SECTION */}
      <Box
        sx={{
          position: "relative",
          backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1920&q=80')`,
          backgroundSize: "cover",
          backgroundPosition: "center",
          color: "#fff",
          py: { xs: 12, md: 20 },
          textAlign: "center",
        }}
      >
        <Container maxWidth="md">
          <Chip
            label="Grand Celebrations & Comfortable Stay"
            sx={{
              backgroundColor: "#d4af37",
              color: "#000",
              fontWeight: 700,
              mb: 3,
            }}
          />
          <Typography
            variant="h2"
            component="h1"
            sx={{
              fontWeight: 800,
              mb: 2,
              fontFamily: "serif",
              fontSize: { xs: "2.5rem", md: "3.5rem" },
            }}
          >
            Palaiya Marriage Hall
          </Typography>
          <Typography
            variant="h6"
            sx={{
              mb: 4,
              fontWeight: 400,
              color: "#f0f0f0",
              maxWidth: "700px",
              mx: "auto",
            }}
          >
            The perfect destination for your weddings, receptions, and family
            gatherings. Featuring spacious marriage halls and 10 well-maintained
            rooms (5 AC & 5 Non-AC).
          </Typography>
          <Box
            sx={{
              display: "flex",
              gap: 2,
              justifyContent: "center",
              flexWrap: "wrap",
            }}
          >
            <Button
              variant="contained"
              size="large"
              onClick={() => {
                setBookingSuccess(false);
                setBookingError("");
                setModalOpen(true);
              }}
              sx={{
                backgroundColor: "#d4af37",
                color: "#000",
                fontWeight: 700,
                px: 4,
                py: 1.5,
                "&:hover": { backgroundColor: "#b5952f" },
                borderRadius: "30px",
              }}
            >
              Book Your Date
            </Button>
            <Button
              variant="outlined"
              size="large"
              href="#rooms"
              sx={{
                borderColor: "#fff",
                color: "#fff",
                fontWeight: 700,
                px: 4,
                py: 1.5,
                "&:hover": {
                  borderColor: "#d4af37",
                  backgroundColor: "rgba(255,255,255,0.1)",
                },
                borderRadius: "30px",
              }}
            >
              Explore Rooms
            </Button>
          </Box>
        </Container>
      </Box>

      {/* QUICK AVAILABILITY CHECKER BAR */}
      <Container
        maxWidth="md"
        sx={{ mt: -5, position: "relative", zIndex: 10 }}
      >
        <Paper
          elevation={4}
          sx={{ p: 3, borderRadius: 3, backgroundColor: "#ffffff" }}
        >
          <form onSubmit={handleCheckAvailability}>
            <Box
              sx={{
                display: "flex",
                gap: 2,
                flexWrap: { xs: "wrap", sm: "nowrap" },
                alignItems: "center",
              }}
            >
              <Box sx={{ flex: { xs: "1 1 100%", sm: "1 1 70%" } }}>
                <TextField
                  fullWidth
                  type="date"
                  label="Check Hall Availability"
                  value={checkDate}
                  onChange={(e) => setCheckDate(e.target.value)}
                  required
                  slotProps={{ inputLabel: { shrink: true } }}
                />
              </Box>
              <Box sx={{ flex: { xs: "1 1 100%", sm: "1 1 30%" } }}>
                <Button
                  fullWidth
                  type="submit"
                  variant="contained"
                  size="large"
                  sx={{
                    backgroundColor: "#6d3b24",
                    height: "56px",
                    fontWeight: 600,
                    "&:hover": { backgroundColor: "#522b1a" },
                  }}
                >
                  Check Status
                </Button>
              </Box>
            </Box>
          </form>
          {availabilityStatus && (
            <Alert severity="success" sx={{ mt: 2 }}>
              {availabilityStatus}
            </Alert>
          )}
        </Paper>
      </Container>

      {/* ABOUT / OVERVIEW */}
      <Container maxWidth="lg" sx={{ py: 10 }} id="about">
        <Box
          sx={{
            display: "flex",
            flexDirection: { xs: "column", md: "row" },
            gap: 6,
            alignItems: "center",
          }}
        >
          <Box sx={{ flex: 1, width: "100%" }}>
            <Box
              component="img"
              src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80"
              alt="Palaiya Marriage Hall Exterior"
              sx={{
                width: "100%",
                borderRadius: 4,
                boxShadow: "0 10px 30px rgba(0,0,0,0.1)",
                display: "block",
              }}
            />
          </Box>
          <Box sx={{ flex: 1 }}>
            <Typography
              variant="overline"
              sx={{ color: "#d4af37", fontWeight: 700, letterSpacing: 2 }}
            >
              WELCOME TO PALAIYA MARRIAGE HALL
            </Typography>
            <Typography
              variant="h4"
              sx={{
                fontWeight: 800,
                my: 2,
                color: "#6d3b24",
                fontFamily: "serif",
              }}
            >
              Creating Timeless Memories For Your Special Occasions
            </Typography>
            <Typography
              variant="body1"
              sx={{ color: "#555", mb: 3, lineHeight: 1.8 }}
            >
              Palaiya Marriage Hall stands as a hallmark of elegance, grand
              tradition, and modern comfort. Whether you are planning a
              magnificent wedding ceremony, a joyous reception, a cultural
              event, or a family get-together, our venue provides the ideal
              backdrop.
            </Typography>
            <Box sx={{ display: "flex", flexWrap: "wrap", gap: 2 }}>
              {[
                "Spacious Mandapam",
                "10 Guest Rooms",
                "20-Car Parking",
                "Power Backup",
              ].map((text, i) => (
                <Box
                  key={i}
                  sx={{
                    display: "flex",
                    alignItems: "center",
                    gap: 1,
                    width: "45%",
                  }}
                >
                  <CheckCircleIcon sx={{ color: "#6d3b24" }} />
                  <Typography variant="subtitle1" sx={{ fontWeight: 600 }}>
                    {text}
                  </Typography>
                </Box>
              ))}
            </Box>
          </Box>
        </Box>
      </Container>

      {/* THE MARRIAGE HALL SECTION */}
      <Box sx={{ backgroundColor: "#f3ede2", py: 10 }} id="hall">
        <Container maxWidth="lg">
          <Box sx={{ textAlign: "center", mb: 6 }}>
            <Typography
              variant="overline"
              sx={{ color: "#d4af37", fontWeight: 700, letterSpacing: 2 }}
            >
              WORLD-CLASS INFRASTRUCTURE
            </Typography>
            <Typography
              variant="h4"
              sx={{ fontWeight: 800, color: "#6d3b24", fontFamily: "serif" }}
            >
              The Marriage Hall & Dining Facility
            </Typography>
          </Box>
          <Box
            sx={{
              display: "flex",
              flexDirection: { xs: "column", md: "row" },
              gap: 4,
            }}
          >
            <Box sx={{ flex: 1 }}>
              <Card
                sx={{
                  height: "100%",
                  borderRadius: 3,
                  boxShadow: "0 6px 20px rgba(0,0,0,0.06)",
                }}
              >
                <CardMedia
                  component="img"
                  height="280"
                  image="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80"
                  alt="Main Marriage Hall"
                />
                <CardContent>
                  <Typography
                    variant="h5"
                    sx={{ fontWeight: 700, color: "#6d3b24", mb: 1 }}
                  >
                    Grand Wedding Hall
                  </Typography>
                  <Typography
                    variant="body2"
                    sx={{ color: "#666", lineHeight: 1.7 }}
                  >
                    A magnificent pillar-less or spacious hall layout customized
                    to accommodate massive gatherings comfortably. Fully
                    equipped with modern acoustics, aesthetic stage setups, and
                    bright atmospheric illumination.
                  </Typography>
                </CardContent>
              </Card>
            </Box>
            <Box sx={{ flex: 1 }}>
              <Card
                sx={{
                  height: "100%",
                  borderRadius: 3,
                  boxShadow: "0 6px 20px rgba(0,0,0,0.06)",
                }}
              >
                <CardMedia
                  component="img"
                  height="280"
                  image="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80"
                  alt="Dining Hall"
                />
                <CardContent>
                  <Typography
                    variant="h5"
                    sx={{ fontWeight: 700, color: "#6d3b24", mb: 1 }}
                  >
                    Hygienic Dining Hall
                  </Typography>
                  <Typography
                    variant="body2"
                    sx={{ color: "#666", lineHeight: 1.7 }}
                  >
                    Spacious traditional and modern dining arrangement allowing
                    smooth buffet or banana-leaf serving styles for hundreds of
                    guests at a time with separate wash areas and clean
                    kitchens.
                  </Typography>
                </CardContent>
              </Card>
            </Box>
          </Box>
        </Container>
      </Box>

      {/* ACCOMMODATION: 5 AC & 5 NON-AC ROOMS */}
      <Container maxWidth="lg" sx={{ py: 10 }} id="rooms">
        <Box sx={{ textAlign: "center", mb: 4 }}>
          <Typography
            variant="overline"
            sx={{ color: "#d4af37", fontWeight: 700, letterSpacing: 2 }}
          >
            COMFORTABLE STAY
          </Typography>
          <Typography
            variant="h4"
            sx={{ fontWeight: 800, color: "#6d3b24", fontFamily: "serif" }}
          >
            Our Guest Rooms (5 AC & 5 Non-AC)
          </Typography>
          <Typography variant="body1" sx={{ color: "#666", mt: 1 }}>
            Rest and relax in our well-furnished, clean, and secure
            accommodation rooms.
          </Typography>
        </Box>

        <Box sx={{ display: "flex", justifyContent: "center", mb: 6 }}>
          <Tabs
            value={roomTab}
            onChange={(e, newVal) => setRoomTab(newVal)}
            textColor="primary"
            indicatorColor="primary"
            sx={{
              "& .MuiTab-root": {
                fontWeight: 700,
                fontSize: "1rem",
                color: "#6d3b24",
              },
              "& .Mui-selected": { color: "#d4af37 !important" },
            }}
          >
            <Tab label="Luxury AC Rooms (5)" />
            <Tab label="Standard Non-AC Rooms (5)" />
          </Tabs>
        </Box>

        {/* Room Flex Wrap Container */}
        <Box
          sx={{
            display: "flex",
            flexWrap: "wrap",
            gap: 3,
            justifyContent: "center",
          }}
        >
          {roomsData
            .filter((room) =>
              roomTab === 0 ? room.type === "ac" : room.type === "non-ac",
            )
            .map((room) => (
              <Box
                key={room.id}
                sx={{
                  width: {
                    xs: "100%",
                    sm: "calc(50% - 16px)",
                    md: "calc(33.333% - 16px)",
                  },
                  display: "flex",
                }}
              >
                <Card
                  sx={{
                    width: "100%",
                    display: "flex",
                    flexDirection: "column",
                    borderRadius: 3,
                    boxShadow: "0 4px 15px rgba(0,0,0,0.05)",
                  }}
                >
                  <CardMedia
                    component="img"
                    height="200"
                    image={room.image}
                    alt={room.name}
                  />
                  <CardContent sx={{ flexGrow: 1 }}>
                    <Typography
                      variant="h6"
                      sx={{ fontWeight: 700, color: "#6d3b24", mb: 1 }}
                    >
                      {room.name}
                    </Typography>
                    <Typography
                      variant="subtitle1"
                      sx={{ fontWeight: 700, color: "#d4af37", mb: 2 }}
                    >
                      {room.price}
                    </Typography>
                    <Box
                      sx={{ display: "flex", gap: 1, flexWrap: "wrap", mb: 2 }}
                    >
                      {room.features.map((feat, idx) => (
                        <Chip
                          key={idx}
                          label={feat}
                          size="small"
                          variant="outlined"
                          sx={{ fontSize: "0.75rem" }}
                        />
                      ))}
                    </Box>
                  </CardContent>
                  <CardActions sx={{ p: 2, pt: 0 }}>
                    <Button
                      fullWidth
                      variant="contained"
                      onClick={() => setModalOpen(true)}
                      sx={{
                        backgroundColor: "#6d3b24",
                        color: "#fff",
                        fontWeight: 600,
                        "&:hover": { backgroundColor: "#522b1a" },
                        borderRadius: "20px",
                      }}
                    >
                      Book Room
                    </Button>
                  </CardActions>
                </Card>
              </Box>
            ))}
        </Box>
      </Container>

      {/* FACILITIES & 20-CAR PARKING SECTION */}
      <Box sx={{ backgroundColor: "#f3ede2", py: 10 }} id="facilities">
        <Container maxWidth="lg">
          <Box sx={{ textAlign: "center", mb: 6 }}>
            <Typography
              variant="overline"
              sx={{ color: "#d4af37", fontWeight: 700, letterSpacing: 2 }}
            >
              AMENITIES & CONVENIENCES
            </Typography>
            <Typography
              variant="h4"
              sx={{ fontWeight: 800, color: "#6d3b24", fontFamily: "serif" }}
            >
              Facilities Provided at Palaiya Marriage Hall
            </Typography>
          </Box>
          <Box
            sx={{
              display: "flex",
              flexWrap: "wrap",
              gap: 3,
              justifyContent: "center",
            }}
          >
            {[
              {
                icon: (
                  <DirectionsCarIcon sx={{ fontSize: 40, color: "#6d3b24" }} />
                ),
                title: "20-Car Parking Space",
                desc: "Spacious, secure parking lot accommodating up to 20 cars plus ample two-wheeler parking slots.",
              },
              {
                icon: <AcUnitIcon sx={{ fontSize: 40, color: "#6d3b24" }} />,
                title: "Air-Conditioned Rooms",
                desc: "Top-tier climate-controlled suites and rooms ensuring supreme relaxation for your VIP guests.",
              },
              {
                icon: <PowerIcon sx={{ fontSize: 40, color: "#6d3b24" }} />,
                title: "Uninterrupted Power Backup",
                desc: "High-capacity heavy-duty generators ensuring seamless events without any power interruptions.",
              },
              {
                icon: <WaterDropIcon sx={{ fontSize: 40, color: "#6d3b24" }} />,
                title: "Pure Water Supply",
                desc: "Round-the-clock filtered running water supply for catering, restrooms, and sanitation needs.",
              },
              {
                icon: <SecurityIcon sx={{ fontSize: 40, color: "#6d3b24" }} />,
                title: "24/7 Safety & Security",
                desc: "Complete premises monitoring with security personnel and safety protocols for total peace of mind.",
              },
              {
                icon: (
                  <RestaurantIcon sx={{ fontSize: 40, color: "#6d3b24" }} />
                ),
                title: "Spacious Dining Hall",
                desc: "Hygiene-focused dining facilities designed to serve large numbers of attendees smoothly and quickly.",
              },
            ].map((fac, index) => (
              <Box
                key={index}
                sx={{
                  width: {
                    xs: "100%",
                    sm: "calc(50% - 16px)",
                    md: "calc(33.333% - 16px)",
                  },
                  display: "flex",
                }}
              >
                <Paper
                  elevation={2}
                  sx={{
                    p: 4,
                    width: "100%",
                    borderRadius: 3,
                    backgroundColor: "#ffffff",
                    textAlign: "center",
                  }}
                >
                  <Box sx={{ mb: 2 }}>{fac.icon}</Box>
                  <Typography
                    variant="h6"
                    sx={{ fontWeight: 700, color: "#6d3b24", mb: 1 }}
                  >
                    {fac.title}
                  </Typography>
                  <Typography
                    variant="body2"
                    sx={{ color: "#666", lineHeight: 1.6 }}
                  >
                    {fac.desc}
                  </Typography>
                </Paper>
              </Box>
            ))}
          </Box>
        </Container>
      </Box>

      {/* PHOTO GALLERY SECTION */}
      <Container maxWidth="lg" sx={{ py: 10 }} id="gallery">
        <Box sx={{ textAlign: "center", mb: 4 }}>
          <Typography
            variant="overline"
            sx={{ color: "#d4af37", fontWeight: 700, letterSpacing: 2 }}
          >
            VISUAL TOUR
          </Typography>
          <Typography
            variant="h4"
            sx={{ fontWeight: 800, color: "#6d3b24", fontFamily: "serif" }}
          >
            Photo Gallery
          </Typography>
        </Box>

        <Box
          sx={{
            display: "flex",
            justifyContent: "center",
            gap: 1,
            mb: 6,
            flexWrap: "wrap",
          }}
        >
          {["All", "Hall", "Rooms", "Dining", "Parking"].map((category) => (
            <Button
              key={category}
              variant={galleryFilter === category ? "contained" : "outlined"}
              onClick={() => setGalleryFilter(category)}
              sx={{
                borderRadius: "20px",
                borderColor: "#6d3b24",
                color: galleryFilter === category ? "#fff" : "#6d3b24",
                backgroundColor:
                  galleryFilter === category ? "#6d3b24" : "transparent",
                "&:hover": { backgroundColor: "#522b1a", color: "#fff" },
              }}
            >
              {category}
            </Button>
          ))}
        </Box>

        <Box
          sx={{
            display: "flex",
            flexWrap: "wrap",
            gap: 3,
            justifyContent: "center",
          }}
        >
          {filteredGallery.map((item) => (
            <Box
              key={item.id}
              sx={{
                width: {
                  xs: "100%",
                  sm: "calc(50% - 12px)",
                  md: "calc(33.333% - 16px)",
                },
                display: "flex",
              }}
            >
              <Card
                sx={{
                  width: "100%",
                  borderRadius: 3,
                  overflow: "hidden",
                  boxShadow: "0 4px 15px rgba(0,0,0,0.06)",
                }}
              >
                <CardMedia
                  component="img"
                  height="240"
                  image={item.image}
                  alt={item.title}
                />
                <CardContent sx={{ backgroundColor: "#fff" }}>
                  <Typography
                    variant="subtitle1"
                    sx={{ fontWeight: 700, color: "#6d3b24" }}
                  >
                    {item.title}
                  </Typography>
                  <Typography
                    variant="caption"
                    sx={{ color: "#d4af37", fontWeight: 600 }}
                  >
                    {item.category}
                  </Typography>
                </CardContent>
              </Card>
            </Box>
          ))}
        </Box>
      </Container>

      {/* TESTIMONIALS */}
      <Box sx={{ backgroundColor: "#f3ede2", py: 10 }}>
        <Container maxWidth="md" sx={{ textAlign: "center" }}>
          <Typography
            variant="overline"
            sx={{ color: "#d4af37", fontWeight: 700, letterSpacing: 2 }}
          >
            HAPPY CLIENTS
          </Typography>
          <Typography
            variant="h4"
            sx={{
              fontWeight: 800,
              color: "#6d3b24",
              fontFamily: "serif",
              mb: 6,
            }}
          >
            What Families Say About Us
          </Typography>
          <Paper
            elevation={3}
            sx={{
              p: { xs: 3, md: 6 },
              borderRadius: 4,
              backgroundColor: "#ffffff",
            }}
          >
            <Box
              sx={{
                display: "flex",
                justifyContent: "center",
                mb: 2,
                color: "#d4af37",
              }}
            >
              {[...Array(5)].map((_, i) => (
                <StarRateIcon key={i} />
              ))}
            </Box>
            <Typography
              variant="body1"
              sx={{
                fontStyle: "italic",
                color: "#444",
                mb: 3,
                lineHeight: 1.8,
                fontSize: "1.1rem",
              }}
            >
              "We celebrated our daughter's wedding at Palaiya Marriage Hall.
              The hall spaciousness, the cleanliness of the 10 rooms, and the
              20-car parking area made it completely stress-free for all our
              relatives. Highly recommended!"
            </Typography>
            <Typography
              variant="subtitle1"
              sx={{ fontWeight: 700, color: "#6d3b24" }}
            >
              — Karthikeyan & Family
            </Typography>
            <Typography variant="caption" sx={{ color: "#777" }}>
              Wedding Reception Host
            </Typography>
          </Paper>
        </Container>
      </Box>

      {/* CONTACT & ENQUIRY SECTION */}
      <Container maxWidth="lg" sx={{ py: 10 }} id="contact">
        <Box
          sx={{
            display: "flex",
            flexDirection: { xs: "column", md: "row" },
            gap: 6,
          }}
        >
          <Box sx={{ flex: 1, minWidth: 0 }}>
            <Typography
              variant="overline"
              sx={{ color: "#d4af37", fontWeight: 700, letterSpacing: 2 }}
            >
              GET IN TOUCH
            </Typography>
            <Typography
              variant="h4"
              sx={{
                fontWeight: 800,
                color: "#6d3b24",
                fontFamily: "serif",
                my: 2,
              }}
            >
              Plan Your Event With Us Today
            </Typography>
            <Typography
              variant="body1"
              sx={{ color: "#666", mb: 4, lineHeight: 1.8 }}
            >
              Contact our booking desk for date availability, inspection visits,
              and catering assistance. We are happy to serve you.
            </Typography>

            <Box sx={{ display: "flex", flexDirection: "column", gap: 3 }}>
              <Box sx={{ display: "flex", alignItems: "center", gap: 2 }}>
                <LocationOnIcon sx={{ color: "#6d3b24", fontSize: 28 }} />
                <Typography variant="body1" sx={{ fontWeight: 500 }}>
                  Palaiya Marriage Hall, Main Road, Tamil Nadu
                </Typography>
              </Box>
              <Box sx={{ display: "flex", alignItems: "center", gap: 2 }}>
                <PhoneIcon sx={{ color: "#6d3b24", fontSize: 28 }} />
                <Typography variant="body1" sx={{ fontWeight: 500 }}>
                  +91 98765 43210 / +91 43210 98765
                </Typography>
              </Box>
              <Box sx={{ display: "flex", alignItems: "center", gap: 2 }}>
                <EmailIcon sx={{ color: "#6d3b24", fontSize: 28 }} />
                <Typography variant="body1" sx={{ fontWeight: 500 }}>
                  bookings@palaiyamarriagehall.com
                </Typography>
              </Box>
            </Box>
          </Box>
          <Box
            sx={{
              flex: 1,
              minWidth: 0,
            }}
          >
            <BookingForm
              title="Quick Booking Enquiry"
              formData={formData}
              formErrors={formErrors}
              bookingSuccess={bookingSuccess}
              bookingError={bookingError}
              bookingMessage={bookingMessage}
              bookingMessageType={bookingMessageType}
              isSubmitting={isSubmitting}
              handleFormChange={handleFormChange}
              handleBookingSubmit={handleBookingSubmit}
              setBookingMessage={setBookingMessage}
            />
          </Box>
        </Box>
      </Container>

      {/* FOOTER */}
      <Box
        sx={{
          backgroundColor: "#2d2d2d",
          color: "#aaa",
          py: 4,
          textAlign: "center",
        }}
      >
        <Container maxWidth="lg">
          <Typography variant="body2">
            © {new Date().getFullYear()} Palaiya Marriage Hall. All Rights
            Reserved. Designed for Grand Celebrations & Comfortable Stays.
          </Typography>
        </Container>
      </Box>

      {/* MODAL ENQUIRY POPUP */}
      <Modal open={modalOpen} onClose={() => setModalOpen(false)}>
        <Box
          sx={{
            position: "absolute",
            top: "50%",
            left: "50%",
            transform: "translate(-50%, -50%)",
            width: { xs: "90%", sm: 500 },
            backgroundColor: "background.paper",
            borderRadius: 4,
            boxShadow: 24,
            p: 4,
            maxHeight: "90vh",
            overflowY: "auto",
          }}
        >
          <Box
            sx={{
              display: "flex",
              justifyContent: "space-between",
              alignItems: "center",
              mb: 2,
            }}
          >
            <Typography variant="h6" sx={{ fontWeight: 700, color: "#6d3b24" }}>
              Book Palaiya Marriage Hall
            </Typography>
            <IconButton onClick={() => setModalOpen(false)}>
              <CloseIcon />
            </IconButton>
          </Box>
          <BookingForm
            formData={formData}
            formErrors={formErrors}
            bookingSuccess={bookingSuccess}
            bookingError={bookingError}
            bookingMessage={bookingMessage}
            bookingMessageType={bookingMessageType}
            isSubmitting={isSubmitting}
            handleFormChange={handleFormChange}
            handleBookingSubmit={handleBookingSubmit}
            setBookingMessage={setBookingMessage}
          />
        </Box>
      </Modal>
    </Box>
  );
};

export default Home;
