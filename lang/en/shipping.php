<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shipping, Booking & Shipment Status
    |--------------------------------------------------------------------------
    */

    // Booking Messages
    'booking' => [
        'confirmed' => 'Your booking is confirmed.',
        'confirmed_with_ref' => 'Your booking is confirmed. Reference: :ref.',
        'pending' => 'Your booking is under review.',
        'cancelled' => 'Booking cancelled.',
        'expired' => 'Booking has expired.',
        'modified' => 'Booking modified.',
        'payment_pending' => 'Payment pending.',
        'payment_received' => 'Payment received.',
        'ready_for_pickup' => 'Ready for pickup.',
        'in_transit' => 'In transit.',
        'delivered' => 'Delivered.',
        'ref_label' => 'Reference Number',
        'booking_number' => 'Booking Number',
        'container_number' => 'Container Number',
        'tracking_number' => 'Tracking Number',
        'success_truck' => 'Truck booking submitted successfully!',
        'success_container' => 'Shipment booking submitted successfully! We will contact you within 24 hours.',
    ],

    // Shipment Status Messages
    'shipment' => [
        'status_draft' => 'Draft',
        'status_pending' => 'Pending',
        'status_confirmed' => 'Confirmed',
        'status_in_transit' => 'In Transit',
        'status_at_port' => 'At Port',
        'status_customs' => 'Customs Clearance',
        'status_delivered' => 'Delivered',
        'status_cancelled' => 'Cancelled',
        'estimated_arrival' => 'Estimated Arrival: :date',
        'departed_from' => 'Departed from :location',
        'arrived_at' => 'Arrived at :location',
        'delayed' => 'Delayed by :hours hours',
        'on_time' => 'On Time',
        'tracking_info' => 'Tracking Information',
        'shipment_updates' => 'Shipment Updates',
    ],
    
    // Container & Cargo
    'container' => [
        'available' => 'Available',
        'unavailable' => 'Unavailable',
        'loading' => 'Loading',
        'loaded' => 'Loaded',
        'in_transit' => 'In Transit',
        'at_destination' => 'At Destination',
        'released' => 'Released',
    ],
    
    // Ports & Routes
    'port' => [
        'origin' => 'Origin Port',
        'destination' => 'Destination Port',
        'transit' => 'Transit Port',
        'current_location' => 'Current Location',
    ],

    'actions' => [
        'track' => 'Track',
        'searching' => 'Searching...',
        'search_quotes' => 'Search Quotes',
        'search_offers' => 'Search Offers',
        'book_now' => 'Book Now',
        'previous' => 'Previous',
        'next' => 'Next',
        'confirm_booking' => 'Confirm Booking',
    ],

    'manufacturing' => [
        'placeholder_page' => 'Manufacturing (Temporary Page)',
    ],

    'truck_tracker' => [
        'tracking_placeholder' => 'Enter tracking number (e.g., TRK123456)',
        'status' => 'Status',
        'current_location' => 'Current Location',
        'speed' => 'Speed',
        'estimated_arrival' => 'Estimated Arrival',
        'driver_info' => 'Driver Information',
        'driver_name' => 'Name:',
        'driver_phone' => 'Phone:',
        'truck_plate' => 'Truck Plate:',
        'live_map' => 'Live Map',
        'progress' => 'Progress',
        'journey_log' => 'Journey Log',
        'kmh' => 'km/h',
        'kg' => 'kg',
        'demo' => [
            'status_in_transit' => 'In transit',
            'current_location' => 'Riyadh - Dammam Highway',
            'driver_name' => 'Ahmed Mohamed',
            'truck_plate' => 'R B J 1234',
            'events' => [
                'start_location' => 'Riyadh - Starting Point',
                'started' => 'Trip started',
                'station_location' => 'Fuel Station - Al Kharj',
                'short_stop' => 'Short stop',
                'on_road_location' => 'On the road',
                'in_transit' => 'In transit',
            ],
        ],
    ],

    'container_tracker' => [
        'tracking_placeholder' => 'Enter tracking number (e.g., MAEU123456789)',
        'current_status' => 'Current Status',
        'progress' => 'Progress',
        'estimated_arrival' => 'Estimated Arrival',
        'journey_log' => 'Journey Log',
        'pending' => 'Pending',
        'demo' => [
            'status_at_sea' => 'At sea',
            'current_location' => 'Near Jeddah Port',
            'events' => [
                'shanghai_port' => 'Shanghai Port',
                'loaded' => 'Loaded',
                'at_sea' => 'At sea',
                'in_transit' => 'In transit',
                'suez_crossing' => 'Suez Canal crossing',
                'crossing' => 'Canal crossing',
                'jeddah_port' => 'Jeddah Port',
                'expected_arrival' => 'Expected arrival',
            ],
        ],
    ],

    'truck_quote_form' => [
        'origin_city' => 'Origin City',
        'origin_city_placeholder' => 'e.g., Riyadh',
        'destination_city' => 'Destination City',
        'destination_city_placeholder' => 'e.g., Jeddah',
        'pickup_date' => 'Pickup Date',
        'weight_kg' => 'Weight (kg)',
        'truck_type' => 'Truck Type',
        'price' => 'Price:',
        'delivery_time' => 'Delivery Time:',
        'rating' => 'Rating:',
        'points' => 'Points',
        'day' => 'day',
        'types' => [
            'flatbed' => 'Flatbed Truck',
            'box' => 'Box Truck',
            'refrigerated' => 'Refrigerated Truck',
            'tanker' => 'Tanker',
        ],
    ],

    'container_quote_form' => [
        'origin_port' => 'Origin Port',
        'origin_port_placeholder' => 'e.g., Jeddah - KSA',
        'destination_port' => 'Destination Port',
        'destination_port_placeholder' => 'e.g., Shanghai - CN',
        'loading_date' => 'Loading Date',
        'weight_kg' => 'Weight (kg)',
        'cbm' => 'Volume (m³)',
        'cargo_type' => 'Cargo Type',
        'container_type' => 'Container Type',
        'price' => 'Price:',
        'transit_duration' => 'Transit Duration:',
        'valid_until' => 'Valid Until:',
        'rating' => 'Rating:',
        'points' => 'Points',
        'day' => 'day',
        'no_results' => 'Sorry, no quotes match these criteria.',
        'cargo_types' => [
            'general' => 'General Cargo',
            'hazmat' => 'Hazardous Materials',
            'perishable' => 'Perishable Goods',
            'fragile' => 'Fragile Goods',
        ],
        'container_types' => [
            '20GP' => '20 ft Standard',
            '40GP' => '40 ft Standard',
            '40HC' => '40 ft High Cube',
            '20RF' => '20 ft Reefer',
            '40RF' => '40 ft Reefer',
        ],
    ],

    'truck_booking_wizard' => [
        'stepper' => ['Route', 'Cargo', 'Documents', 'Payment', 'Confirmation'],
        'step_1_title' => 'Step 1: Route Details',
        'step_2_title' => 'Step 2: Cargo Details',
        'step_3_title' => 'Step 3: Documents',
        'step_4_title' => 'Step 4: Payment Method',
        'step_5_title' => 'Step 5: Confirmation',
        'origin_city' => 'Origin City',
        'origin_city_placeholder' => 'Riyadh',
        'destination_city' => 'Destination City',
        'destination_city_placeholder' => 'Jeddah',
        'pickup_date' => 'Pickup Date',
        'delivery_date' => 'Expected Delivery Date',
        'weight_kg' => 'Weight (kg)',
        'truck_type' => 'Truck Type',
        'cargo_description' => 'Cargo Description',
        'cargo_description_placeholder' => 'Describe the cargo...',
        'invoice' => 'Invoice',
        'packing_list' => 'Packing List',
        'uploaded' => 'Uploaded',
        'terms_accept' => 'I agree to the terms and conditions',
        'types' => [
            'flatbed' => 'Flatbed Truck',
            'box' => 'Box Truck',
            'refrigerated' => 'Refrigerated Truck',
        ],
        'payment' => [
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'cod' => 'Cash on Delivery',
        ],
        'summary' => [
            'route' => 'Route:',
            'date' => 'Date:',
            'weight' => 'Weight:',
        ],
    ],
];
