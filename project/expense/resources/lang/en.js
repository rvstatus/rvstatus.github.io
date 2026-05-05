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
    }
};