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
                        'desc'=>'View all payment transactions.',
                        'route' => 'admin.reports.payment.index',
                    ],

                    [
                        'icon'=>'fa-hand-holding-usd',
                        'title'=>'Due Report',
                        'desc'=>'Outstanding payment report.',
                        'route' => 'admin.reports.due.index',
                    ],

                    [
                        'icon'=>'fa-chart-line',
                        'title'=>'Collection Report',
                        'desc'=>'Daily, monthly & yearly collections.',
                        'route' => 'admin.reports.collection.index',
                    ],

                    [
                        'icon'=>'fa-university',
                        'title'=>'Payment Method',
                        'desc'=>'Cash, Card, Bank & Mobile Banking.',
                        'route' => 'admin.reports.payment-method.index',
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
                        'desc'=>'Complete patient information.',
                        'route' => 'admin.reports.patient.index',
                    ],

                    [
                        'icon'=>'fa-user-check',
                        'title'=>'Active Patients',
                        'desc'=>'Currently active patients.',
                        'route' => 'admin.reports.active-patients.index',
                    ],

                    [
                        'icon'=>'fa-user-times',
                        'title'=>'Inactive Patients',
                        'desc'=>'Completed or inactive patients.',
                        'route' => 'admin.reports.inactive-patients.index',
                    ],

                    [
                        'icon'=>'fa-user-md',
                        'title'=>'Patient by Doctor',
                        'desc'=>'Doctor-wise patient report.',
                        'route' => 'admin.reports.patient-by-doctor.index',
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
                        'desc'=>'Running treatment cases.',
                        'route' => 'admin.reports.active-cases.index',
                    ],

                    [
                        'icon'=>'fa-check-circle',
                        'title'=>'Completed Cases',
                        'desc'=>'Completed treatment cases.',
                        'route' => 'admin.reports.completed-cases.index',

                    ],

                    [
                        'icon'=>'fa-wallet',
                        'title'=>'Payment Due',
                        'desc'=>'Cases with payment due.',
                        'route' => 'admin.reports.payment-due.index',
                    ],

                    [
                        'icon'=>'fa-truck',
                        'title'=>'Delivery Pending',
                        'desc'=>'Pending delivery cases.',
                        'route' => 'admin.reports.delivery-pending.index',
                        
                    ],

                    [
                        'icon'=>'fa-exclamation-triangle',
                        'title'=>'Delivery Overdue',
                        'desc'=>'Overdue delivery cases.',
                        'route' => 'admin.reports.delivery-overdue.index',
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
                        'desc'=>'Patients grouped by doctor.',
                        'route' => 'admin.reports.doctor-wise-patients.index',
                    ],

                    [
                        'icon'=>'fa-coins',
                        'title'=>'Doctor Revenue',
                        'desc'=>'Doctor revenue summary.',
                        'route' => 'admin.reports.doctor-revenue.index',
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
                        'desc'=>'Patients grouped by MR.',
                        'route' => 'admin.reports.mr-wise-patients.index',
                    ],

                    [
                        'icon'=>'fa-chart-pie',
                        'title'=>'MR Performance',
                        'desc'=>'Marketing performance report.',
                        'route' => 'admin.reports.mr-performance.index',
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