<?php

return [
    'register' => [
        'validation' => [
            'name' => [
                'required' => 'Name field is required.',
                'min' => 'Name must be minimum 3 characters.',
                'max' => 'Name must be less than 50 characters.',
            ],
            'email' => [
                'required' => 'Email field is required.',
                'email' => 'Please enter valid email address.',
                'unique' => 'This email already exists.',
            ],
            'password' => [
                'required' => 'Password field is required.',
                'min' => 'Password must be minimum 6 characters.',
                'confirmed' => 'Password confirmation does not match.',
            ],
        ],
        'create' => [
            'success' => 'Registration Completed Successfully.',
            'fail' => 'Registration Failed.',
        ],
    ],
    'login' => [
        'validation' => [
            'email' => [
                'required' => 'Email field is required.',
                'email' => 'Please enter valid email address.',
            ],
            'password' => [
                'required' => 'Password field is required.',
                'min' => 'Password must be minimum 6 characters.',
            ],
        ],
        'invalid_credentials' => 'Invalid email or password.',
    ],
    'project_type' => [

        'validation' => [
            'name' => [
                'required' => 'The Project Type Name is required.',
                'min' => 'The Work Type Name must be at least 5 characters.',
                'max' => 'The Project Type must be 50 characters or less.',
            ],
        ],

        'create' => [
            'success' => 'Project Type Registered Successfully.',
            'fail'    => 'Project Type Registration Unsuccessful...',
        ],

        'update' => [
            'success' => 'Project Type Updated Successfully.',
            'fail'    => 'Project Type Update Unsuccessful...',
        ],

        'toggle' => [
            'success' => 'Project Type Status Updated Successfully.',
            'fail'    => 'Project Type Status Update Failed...',
        ],
    ],

    'work_category' => [

        'validation' => [
            'name' => [
                'required' => 'The Work Category Name is required.',
                'min' => 'The Work Category Name must be at least 5 characters.',
                'max' => 'The Work Category Name must be 50 characters or less.',
            ],
        ],

        'create' => [
            'success' => 'Work Category Registered Successfully.',
            'fail'    => 'Work Category Registration Unsuccessful...',
        ],

        'update' => [
            'success' => 'Work Category Updated Successfully.',
            'fail'    => 'Work Category Update Unsuccessful...',
        ],

        'toggle' => [
            'success' => 'Work Category Status Updated Successfully.',
            'fail'    => 'Work Category Status Update Failed...',
        ],

    ],

    'work_type' => [

        'validation' => [
            'name' => [
                'required' => 'The Work Type Name is required.',
                'min' => 'The Work Type Name must be at least 5 characters.',
                'max' => 'The Work Type Name must be 50 characters or less.',
            ]
        ],

        'create' => [
            'success' => 'Work Type Registered Successfully.',
            'fail'    => 'Work Type Registration Unsuccessful...',
        ],

        'update' => [
            'success' => 'Work Type Updated Successfully.',
            'fail'    => 'Work Type Update Unsuccessful...',
        ],

        'toggle' => [
            'success' => 'Work Type Status Updated Successfully.',
            'fail'    => 'Work Type Status Update Failed...',
        ],

    ],
    'expense' => [
        'list' => [
            'empty' => 'No Data Found.',
        ],
        'validation' => [
            'project_type_name' => [
                'required' => 'The Project Type Name is required.',
            ],
            'mason_name' => [
                'required' => 'The Mason Name is required.',
            ],
            'working_date' => [
                'required' => 'Please select a Working Date.',
                'date' => 'The Working Date must be a valid date.',
                'before' => 'The Working Date must be a date before today.',
            ],
            'working_cat' => [
                'required' => 'The Working Category is required.',
            ],
            'working_type' => [
                'required' => 'The Working Type is required.',
            ],
            'salary' => [
                'required' => 'The Salary is required.',
                'integer' => 'The Salary must be a valid integer.',
                'min' => 'The Salary must be at least 1.',
                'not_in' => 'The Salary cannot be zero.',
            ],
        ],
    ],
    'forgot_password' => [
        'validation' => [
            'email_not_found' => 'Email address not found.',
            'invalid_request' => 'Invalid password reset request.',
            'invalid_token' => 'Invalid or Expired reset token.',
        ],
        'mail' => [
            'success' => 'Password reset link sent successfully.',
            'fail' => 'Failed to send password reset link.',
        ],
        'reset' => [
            'success' => 'Password reset successful.',
            'fail' => 'Password reset failed. Please try again.',
        ],
    ],
    'user_approval' => [

        'approve' => [
            'success' => 'User Approved Successfully.',
            'fail'    => 'User Approval Failed.',
        ],

        'reject' => [
            'success' => 'User Rejected Successfully.',
            'fail'    => 'User Rejection Failed.',
        ],

        'pending' => [
            'success' => 'User Changed To Pending Successfully.',
            'fail'    => 'Failed To Change User To Pending.',
        ],

    ],
    'employee' => [
        'list' => [
            'empty' => 'No Data Found.',
        ],
        'validation' => [
            'emp_name' => [
                'required' => 'Employee Name is required.',
                'min' => 'Employee Name must be at least 3 characters.',
                'max' => 'Employee Name must be less than 50 characters.',
            ],
            'gender' => [
                'required' => 'Gender is required.',
            ],
            'mobile_no' => [
                'required' => 'Mobile Number is required.',
                'digits_between' => 'Mobile Number must be valid.',
            ],
            'email' => [
                'required' => 'Email is required.',
                'email' => 'Please enter valid Email Address.',
            ],
            'address' => [
                'required' => 'Address is required.',
                'max' => 'Address must be less than 500 characters.',
            ],
            'join_date' => [
                'required' => 'Join Date is required.',
                'date' => 'Join Date must be a valid date.',
                'before_or_equal' => 'Employee must be at least 18 years old.',
            ],
            'category_id' => [
                'required' => 'Category is required.',
            ],
            'salary' => [
                'required' => 'Salary is required.',
                'numeric' => 'Salary must be numeric.',
                'min' => 'Salary must be greater than zero.',
                'max' => 'Salary is too large.',
            ],
        ],
        'create' => [
            'success' => 'Employee Registered Successfully.',
            'fail' => 'Employee Registration Failed.',
        ],
        'detail' => [
            'not_found' => 'Temporary technical issue. Please try again in a few minutes.',
        ],
        'update' => [
            'success' => 'Employee Updated Successfully.',
            'fail' => 'Employee Update Failed.',
        ],
        'delete' => [
            'success' => 'Employee Deleted Successfully.',
            'fail' => 'Employee Delete Failed.',
        ],
        'revert' => [
            'success' => 'Employee Restored Successfully.',
            'fail' => 'Employee Restore Failed.',
        ],
    ],
    'dashboard' => [
        'expense' => [
            'total_exp' => 'No Expenses Logged Yet.',
            'total_today_exp' => 'No Expenses Logged Today.',
            'total_yesterday_exp' => 'No Expenses Logged Yesterday.',
            'total_last_seven_day_exp' => 'No Expenses Logged This Week.',
            'total_current_month_exp' => 'No Expenses This Month.',
            'total_last_month_exp' => 'No Expenses Last Month.',
        ],

    ],
    'salary' => [
        'index' => [
            'error' => 'Temporary technical issue. Please try again in a few minutes.',
        ],
        'employee_selection' => [
            'success' => 'Employees Selected Successfully.',
            'fail'    => 'Employee Selection Failed.',
        ],
        'create' => [
            'success' => 'Salary Registered Successfully.',
            'fail'    => 'Salary Registration Failed.',
        ],
        // 'update' => [
        //     'success' => 'Salary Updated Successfully.',
        //     'fail'    => 'Salary Update Failed.',
        // ],
        // 'delete' => [
        //     'success' => 'Salary Deleted Successfully.',
        //     'fail'    => 'Salary Delete Failed.',
        // ],
        // 'detail' => [
        //     'not_found' => 'Salary details not found.',
        // ],
    ],
    'payslip' => [],
];
