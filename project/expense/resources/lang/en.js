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
                title: "Are you sure ?",
                confirm_button: "Yes, proceed !",
                cancel_button: "Cancel"
            },

            toggle: {
                text: "You want to change the status of this Project Type."
            },

            create: {
                text: "Do you want to create this Project Type ?"
            },

            update: {
                text: "Do you want to update this Project Type ?"
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
                title: "Are you sure ?",
                confirm_button: "Yes, proceed !",
                cancel_button: "Cancel"
            },

            toggle: {
                text: "You want to change the status of this Work Category."
            },

            create: {
                text: "Do you want to create this Work Category ?"
            },

            update: {
                text: "Do you want to update this Work Category ?"
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
                title: "Are you sure ?",
                confirm_button: "Yes, proceed !",
                cancel_button: "Cancel"
            },

            toggle: {
                text: "You want to change the status of this Work Type."
            },

            create: {
                text: "Do you want to create this Work Type ?"
            },

            update: {
                text: "Do you want to update this Work Type ?"
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
            date_of_birth: {
                required: "Date of Birth is required",
                invalid: "Enter valid date of birth",
                age: "Employee must be at least 18 years old"
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
                required: "Join Date is required",
                invalid: "Enter valid join date",
                before_or_equal : "Join date must be today or earlier.",
                after_or_equal: "Join Date must be after Date of Birth"
            },
            salary: {
                required: "Salary is required",
                invalid: "Enter valid salary"
            }
        },
        // popup (SweetAlert) messages
        popup: {
            common: {
                title: "Are you sure ?",
                confirm_button: "Yes, proceed !",
                cancel_button: "Cancel"
            },
            update: {
                text: "Do you want to update this Employee ?"
            },
            delete: {
                text: "Do you want to delete this Employee ?"
            },
            revert_delete: {
                text: "Do you want to revert delete this Employee ?"
            }
        }
    },
    salary: {
        popup: {
            common: {
                title: "Are you sure ?",
                confirm_button: "Yes, proceed !",
                cancel_button: "Cancel"
            },
            cancel: {
                text: "Do you want to cancel the changes ?"
            },
            register: {
                text: "Do you want to register salary details ?"
            },
            update: {
                text: "Do you want to update salary details ?"
            },
            employee_selection: {
                text: "Do you want to add the selected employees ?"
            },
            day_change: {
                text: "Do you want to change the day ?"
            },
        },
        validation: {
            employee: {
                required: "Please select at least one employee."
            },
            day: {
                required: "Please Select Day."
            },
            basic_salary: {
                required: "Please Enter Basic Salary Amount"
            },
            insentive: {
                required: "Please Enter Insentive Amount"
            },
            // pf_amount: {
            //     required: "Please Enter PF Amount"
            // },
            // esi_amount: {
            //     required: "Please Enter ESI Amount"
            // },
        }
    },
    expense: {
        lables_select_expense: "Select Expense Name",
        lables_others: "Others",
        validation: {
            project_type_id: {
                required: "Project Type is required"
            },
            expense_type_id: {
                required: "Expense Type is required"
            },
            other_expense_type: {
                required: "Please enter other Expense Type",
                max: "Other Expense Type must not exceed 150 characters.",
                min: "Other Expense Type must be at least 3 characters."
            },
            expense_name_id: {
                required: "Expense is required"
            },
            other_expense_name: {
                required: "Please enter other expense",
                max: "Other Expense Name must not exceed 150 characters.",
                min: "Other Expense Name must be at least 3 characters."
            },
            expense_amount: {
                required: "Expense Amount is required",
                numeric: "Expense Amount must be numeric",
                min: "Expense Amount must be greater than zero",
                max: "Expense Amount must not exceed 1,50,000"
            },
            expense_date: {
                required: "Expense Date is required",
                invalid: "Enter valid Expense Date",
                before_or_equal: "Expense Date must be today or earlier"
            },
            expense_description: {
                max: "Expense Description must be less than 255 characters"
            },
            vendor_name: {
                max: "Vendor Name must be less than 100 characters"
            },
            bill_no: {
                max: "Bill Number must be less than 50 characters"
            },
            remarks: {
                max: "Remarks must be less than 255 characters"
            }
        },
        popup: {
            common: {
                title: "Are you sure ? ",
                confirm_button: "Yes, proceed ! ",
                cancel_button: "Cancel"
            },
            create: {
                text: "Do you want to register this Expense ? "
            },
            update: {
                text: "Do you want to update this Expense ? "
            },
            delete: {
                text: "Do you want to delete this Expense ? "
            }

        }
    },
};