Mandapam Booking

A web-based Marriage Hall / Mandapam Booking application for presenting hall information, guest rooms, facilities, gallery, availability, and booking enquiries.

The customer website is designed as a single-page application. The main customer experience is handled in Home.tsx, while the admin section provides separate pages for managing the application.

Application Flow
Customer Website
Home
│
├── About
├── Marriage Hall
├── Guest Rooms
│ ├── 5 AC Rooms
│ └── 5 Non-AC Rooms
├── Facilities
├── Gallery
│ ├── Hall
│ ├── Rooms
│ ├── Dining
│ └── Parking
├── Testimonials
├── Check Availability
├── Booking Enquiry
└── Contact

Admin Panel
Admin Login
│
└── Dashboard
│
├── Manage Mandapams
├── Manage Bookings
├── Manage Customers
└── View Enquiries

Project Structure
mandapam_booking/
│
├── package.json
├── yarn.lock
├── tsconfig.json
├── webpack.web.config.js
├── README.md
├── .gitignore
│
├── prisma/
│ └── schema.prisma
│
├── .env
├── .env.development
├── .env.production
│
└── src/
│
├── api/
│ ├── server.ts
│ ├── prisma.ts
│ │
│ ├── controllers/
│ │ ├── authController.ts
│ │ ├── mandapamController.ts
│ │ └── bookingController.ts
│ │
│ ├── routes/
│ │ ├── authRoute.ts
│ │ ├── mandapamRoute.ts
│ │ └── bookingRoute.ts
│ │
│ └── middleware/
│ └── authMiddleware.ts
│
└── pages/
│
├── Home.tsx
│
└── admin/
├── Login.tsx
├── Dashboard.tsx
├── Mandapams.tsx
├── Bookings.tsx
└── Customers.tsx

This project was created as a private Mandapam / Marriage Hall Booking application for showcasing the venue and managing customer booking enquiries.
