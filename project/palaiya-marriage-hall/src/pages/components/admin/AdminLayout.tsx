import React from "react";
import {
  AppBar,
  Box,
  Drawer,
  IconButton,
  List,
  ListItemButton,
  ListItemIcon,
  ListItemText,
  Toolbar,
  Typography,
} from "@mui/material";

import DashboardIcon from "@mui/icons-material/Dashboard";
import EventIcon from "@mui/icons-material/Event";
import PeopleIcon from "@mui/icons-material/People";
import LogoutIcon from "@mui/icons-material/Logout";
import MenuIcon from "@mui/icons-material/Menu";
import EmailIcon from "@mui/icons-material/Email";

import { Outlet, useLocation, useNavigate } from "react-router-dom";

const drawerWidth = 240;

const AdminLayout: React.FC = () => {
  const navigate = useNavigate();
  const location = useLocation();

  const [mobileOpen, setMobileOpen] = React.useState(false);

  const adminData = localStorage.getItem("admin");
  const admin = adminData ? JSON.parse(adminData) : null;

  // Handle logout
  const handleLogout = () => {
    localStorage.removeItem("admin");
    navigate("/admin/login");
  };

  // Handle mobile drawer open and close
  const handleDrawerToggle = () => {
    setMobileOpen((previous) => !previous);
  };

  // Close mobile drawer
  const handleDrawerClose = () => {
    setMobileOpen(false);
  };

  // Handle navigation and close mobile drawer
  const handleNavigation = (path: string) => {
    navigate(path);
    setMobileOpen(false);
  };

  // Check active navigation item
  const isActive = (path: string) => {
    return location.pathname === path;
  };

  // Common navigation item styles
  const navigationStyles = {
    borderRadius: 2,
    mb: 0.5,
    "&.Mui-selected": {
      backgroundColor: "#f3e7df",
      color: "#6d3b24",
    },
    "&.Mui-selected:hover": {
      backgroundColor: "#ead9ce",
    },
  };

  // Navigation drawer content
  const drawerContent = (
    <Box>
      {/* Logo */}
      <Box
        sx={{
          height: 64,
          display: "flex",
          alignItems: "center",
          px: 2,
          backgroundColor: "#6d3b24",
          color: "#fff",
        }}
      >
        <Typography
          variant="h6"
          sx={{
            fontWeight: 700,
            fontFamily: "serif",
          }}
        >
          Palaiya Marriage Hall
        </Typography>
      </Box>

      {/* Navigation */}
      <List sx={{ px: 1, mt: 1 }}>
        {/* Dashboard */}
        <ListItemButton
          selected={isActive("/admin/dashboard")}
          onClick={() => handleNavigation("/admin/dashboard")}
          sx={navigationStyles}
        >
          <ListItemIcon
            sx={{
              minWidth: 40,
              color: isActive("/admin/dashboard") ? "#6d3b24" : "inherit",
            }}
          >
            <DashboardIcon />
          </ListItemIcon>

          <ListItemText primary="Dashboard" />
        </ListItemButton>

        {/* Bookings */}
        <ListItemButton
          selected={isActive("/admin/bookings")}
          onClick={() => handleNavigation("/admin/bookings")}
          sx={navigationStyles}
        >
          <ListItemIcon
            sx={{
              minWidth: 40,
              color: isActive("/admin/bookings") ? "#6d3b24" : "inherit",
            }}
          >
            <EventIcon />
          </ListItemIcon>

          <ListItemText primary="Bookings" />
        </ListItemButton>

        {/* Customers */}
        <ListItemButton
          selected={isActive("/admin/customers")}
          onClick={() => handleNavigation("/admin/customers")}
          sx={navigationStyles}
        >
          <ListItemIcon
            sx={{
              minWidth: 40,
              color: isActive("/admin/customers") ? "#6d3b24" : "inherit",
            }}
          >
            <PeopleIcon />
          </ListItemIcon>

          <ListItemText primary="Customers" />
        </ListItemButton>

        {/* Mail History */}
        <ListItemButton
          selected={isActive("/admin/mail-history")}
          onClick={() => handleNavigation("/admin/mail-history")}
          sx={navigationStyles}
        >
          <ListItemIcon
            sx={{
              minWidth: 40,
              color: isActive("/admin/mail-history") ? "#6d3b24" : "inherit",
            }}
          >
            <EmailIcon />
          </ListItemIcon>

          <ListItemText primary="Mail Send History" />
        </ListItemButton>

        {/* Logout */}
        <ListItemButton
          onClick={handleLogout}
          sx={{
            borderRadius: 2,
            mt: 2,
            color: "#c62828",
          }}
        >
          <ListItemIcon
            sx={{
              minWidth: 40,
              color: "#c62828",
            }}
          >
            <LogoutIcon />
          </ListItemIcon>

          <ListItemText primary="Logout" />
        </ListItemButton>
      </List>
    </Box>
  );

  return (
    <Box
      sx={{
        display: "flex",
        minHeight: "100vh",
        backgroundColor: "#f7f5f3",
      }}
    >
      {/* Top App Bar */}
      <AppBar
        position="fixed"
        elevation={0}
        sx={{
          width: {
            sm: `calc(100% - ${drawerWidth}px)`,
          },
          ml: {
            sm: `${drawerWidth}px`,
          },
          backgroundColor: "#fff",
          color: "#333",
          borderBottom: "1px solid #e5e5e5",
        }}
      >
        <Toolbar sx={{ minHeight: 64 }}>
          {/* Mobile Menu */}
          <IconButton
            color="inherit"
            edge="start"
            onClick={handleDrawerToggle}
            sx={{
              mr: 2,
              display: {
                sm: "none",
              },
            }}
          >
            <MenuIcon />
          </IconButton>

          <Box sx={{ flexGrow: 1 }}>
            <Typography
              variant="h6"
              sx={{
                fontWeight: 700,
                color: "#6d3b24",
              }}
            >
              Admin Panel
            </Typography>
          </Box>

          {/* Admin Name */}
          <Box>
            <Typography variant="body2" sx={{ fontWeight: 600 }}>
              {admin?.name || "Admin"}
            </Typography>

            <Typography variant="caption" sx={{ color: "#777" }}>
              Administrator
            </Typography>
          </Box>
        </Toolbar>
      </AppBar>

      {/* Navigation */}
      <Box
        component="nav"
        sx={{
          width: {
            sm: drawerWidth,
          },
          flexShrink: {
            sm: 0,
          },
        }}
      >
        {/* Mobile Drawer */}
        <Drawer
          variant="temporary"
          open={mobileOpen}
          onClose={handleDrawerClose}
          ModalProps={{
            keepMounted: true,
          }}
          sx={{
            display: {
              xs: "block",
              sm: "none",
            },
            "& .MuiDrawer-paper": {
              width: drawerWidth,
              boxSizing: "border-box",
            },
          }}
        >
          {drawerContent}
        </Drawer>

        {/* Desktop Drawer */}
        <Drawer
          variant="permanent"
          open
          sx={{
            display: {
              xs: "none",
              sm: "block",
            },
            "& .MuiDrawer-paper": {
              width: drawerWidth,
              boxSizing: "border-box",
              borderRight: "1px solid #e5e5e5",
            },
          }}
        >
          {drawerContent}
        </Drawer>
      </Box>

      {/* Page Content */}
      <Box
        component="main"
        sx={{
          flexGrow: 1,
          width: {
            sm: `calc(100% - ${drawerWidth}px)`,
          },
          p: {
            xs: 2,
            sm: 3,
          },
          pt: {
            xs: 10,
            sm: 10,
          },
        }}
      >
        <Outlet />
      </Box>
    </Box>
  );
};

export default AdminLayout;
