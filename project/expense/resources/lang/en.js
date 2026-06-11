 // global language object (used for all UI text / messages)
window.lang = {
    project_type: {
        // form validation messages
        validation: {
            name: {
                required: "Project Type Name is required",
                min: "Project Type Name must be at least 5 characters",
                max: "The Project Type must be 50 characters or less."
            }
        },

        // popup (SweetAlert) messages
        popup: {
            // common popup texts (shared buttons / title)
            common: {
                title: "Are you sure?",
                confirm_button: "Yes, proceed!",
                cancel_button: "Cancel"
            },

            toggle: {
                text: "You want to change the status of this Project Type."
            },

            create: {
                text: "Do you want to create this Project Type?"
            },

            update: {
                text: "Do you want to update this Project Type?"
            }
        },
        // form labels and button text
        form: {
            add_title: "Add Project Type",
            edit_title: "Edit Project Type",
            add_button: "Register",
            edit_button: "Update"
        }
    },


    work_category: {
        // form validation messages
        validation: {
            name: {
                required: "Work Category Name is required",
                min: "Work Category Name must be at least 5 characters",
                max: "The Work Category must be 50 characters or less."
            }
        },

        // popup (SweetAlert) messages
        popup: {
            // common popup texts (shared buttons / title)
            common: {
                title: "Are you sure?",
                confirm_button: "Yes, proceed!",
                cancel_button: "Cancel"
            },

            toggle: {
                text: "You want to change the status of this Work Category."
            },

            create: {
                text: "Do you want to create this Work Category?"
            },

            update: {
                text: "Do you want to update this Work Category?"
            }
        },
        // form labels and button text
        form: {
            add_title: "Add Work Category",
            edit_title: "Edit Work Category",
            add_button: "Register",
            edit_button: "Update"
        }
    },


    work_type: {
        // form validation messages
        validation: {
            name: {
                required: "Work Type Name is required",
                min: "Work Type Name must be at least 5 characters",
                max: "The Work Type must be 50 characters or less."
            }
        },

        // popup (SweetAlert) messages
        popup: {
            // common popup texts (shared buttons / title)
            common: {
                title: "Are you sure?",
                confirm_button: "Yes, proceed!",
                cancel_button: "Cancel"
            },

            toggle: {
                text: "You want to change the status of this Work Type."
            },

            create: {
                text: "Do you want to create this Work Type?"
            },

            update: {
                text: "Do you want to update this Work Type?"
            }
        },
        // form labels and button text
        form: {
            add_title: "Add Work Type",
            edit_title: "Edit Work Type",
            add_button: "Register",
            edit_button: "Update"
        }
    },
    employee: {
        // form validation messages
        validation: {
            emp_name: {
                required: "Employee Name is required",
                min: "Employee Name must be at least 3 characters",
                max: "Employee Name must be less than 50 characters"
            },
            gender: {
                required: "Gender is required"
            },
            mobile_no: {
                required: "Mobile Number is required",
                invalid: "Enter valid 10 digit mobile number"
            },
            email: {
                required: "Email is required",
                invalid: "Enter valid email address"
            },
            address: {
                required: "Address is required",
                max: "Address must be less than 500 characters"
            },
            category_id: {
                required: "Category is required"
            },
            join_date: {
                required: "Join Date is required"
            },
            salary: {
                required: "Salary is required",
                invalid: "Enter valid salary"
            }
        },
        // popup (SweetAlert) messages
        popup: {
            common: {
                title: "Are you sure?",
                confirm_button: "Yes, proceed!",
                cancel_button: "Cancel"
            },
            update: {
                text: "Do you want to update this Employee?"
            }
        }
    }
};