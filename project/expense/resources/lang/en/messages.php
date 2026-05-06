<?php

return [

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

];
