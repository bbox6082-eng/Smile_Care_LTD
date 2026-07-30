<?php

return [

            [
                'title' => 'Financial Reports',
                'color' => 'success',
                'icon'  => 'fa-money-bill-wave',

                'reports' => [

                    [
                        'icon'=>'fa-money-bill-wave',
                        'title'=>'Payment Report',
                        'desc'=>'View all payment transactions.'
                    ],

                    [
                        'icon'=>'fa-hand-holding-usd',
                        'title'=>'Due Report',
                        'desc'=>'Outstanding payment report.'
                    ],

                    [
                        'icon'=>'fa-chart-line',
                        'title'=>'Collection Report',
                        'desc'=>'Daily, monthly & yearly collections.'
                    ],

                    [
                        'icon'=>'fa-university',
                        'title'=>'Payment Method',
                        'desc'=>'Cash, Card, Bank & Mobile Banking.'
                    ],

                ]

            ],

            [

                'title'=>'Patient Reports',
                'color'=>'primary',
                'icon'=>'fa-user-injured',

                'reports'=>[

                    [
                        'icon'=>'fa-user-injured',
                        'title'=>'Patient Report',
                        'desc'=>'Complete patient information.'
                    ],

                    [
                        'icon'=>'fa-user-check',
                        'title'=>'Active Patients',
                        'desc'=>'Currently active patients.'
                    ],

                    [
                        'icon'=>'fa-user-times',
                        'title'=>'Inactive Patients',
                        'desc'=>'Completed or inactive patients.'
                    ],

                    [
                        'icon'=>'fa-user-md',
                        'title'=>'Patient by Doctor',
                        'desc'=>'Doctor-wise patient report.'
                    ],

                ]

            ],

            [

                'title'=>'Case Management Reports',
                'color'=>'danger',
                'icon'=>'fa-folder-open',

                'reports'=>[

                    [
                        'icon'=>'fa-folder-open',
                        'title'=>'Active Cases',
                        'desc'=>'Running treatment cases.'
                    ],

                    [
                        'icon'=>'fa-check-circle',
                        'title'=>'Completed Cases',
                        'desc'=>'Completed treatment cases.'
                    ],

                    [
                        'icon'=>'fa-wallet',
                        'title'=>'Payment Due',
                        'desc'=>'Cases with payment due.'
                    ],

                    [
                        'icon'=>'fa-truck',
                        'title'=>'Delivery Pending',
                        'desc'=>'Pending delivery cases.'
                    ],

                    [
                        'icon'=>'fa-exclamation-triangle',
                        'title'=>'Delivery Overdue',
                        'desc'=>'Overdue delivery cases.'
                    ],

                ]

            ],

            [

                'title'=>'Doctor Reports',
                'color'=>'info',
                'icon'=>'fa-user-doctor',

                'reports'=>[

                    [
                        'icon'=>'fa-user-doctor',
                        'title'=>'Doctor-wise Patients',
                        'desc'=>'Patients grouped by doctor.'
                    ],

                    [
                        'icon'=>'fa-coins',
                        'title'=>'Doctor Revenue',
                        'desc'=>'Doctor revenue summary.'
                    ],

                ]

            ],

            [

                'title'=>'Marketing Representative Reports',
                'color'=>'warning',
                'icon'=>'fa-users',

                'reports'=>[

                    [
                        'icon'=>'fa-users',
                        'title'=>'MR-wise Patients',
                        'desc'=>'Patients grouped by MR.'
                    ],

                    [
                        'icon'=>'fa-chart-pie',
                        'title'=>'MR Performance',
                        'desc'=>'Marketing performance report.'
                    ],

                ]

            ],

            [

                'title'=>'Inventory Reports',
                'color'=>'secondary',
                'icon'=>'fa-boxes',

                'reports'=>[

                    [
                        'icon'=>'fa-boxes',
                        'title'=>'Stock Report',
                        'desc'=>'Current inventory report.'
                    ],

                    [
                        'icon'=>'fa-exclamation-circle',
                        'title'=>'Low Stock',
                        'desc'=>'Products below minimum stock.'
                    ],

                    [
                        'icon'=>'fa-chart-bar',
                        'title'=>'Product Usage',
                        'desc'=>'Inventory usage history.'
                    ],

                ]

            ],

            [

                'title'=>'Production Reports',
                'color'=>'dark',
                'icon'=>'fa-industry',

                'reports'=>[

                    [
                        'icon'=>'fa-industry',
                        'title'=>'Production Queue',
                        'desc'=>'Current production queue.'
                    ],

                    [
                        'icon'=>'fa-check-double',
                        'title'=>'Completed Production',
                        'desc'=>'Completed production jobs.'
                    ],

                    [
                        'icon'=>'fa-clock',
                        'title'=>'Delayed Production',
                        'desc'=>'Delayed production jobs.'
                    ],

                ]

            ],



];