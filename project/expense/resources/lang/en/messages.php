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
];
