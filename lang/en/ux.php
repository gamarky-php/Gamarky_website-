<?php

return [
    /*
    |--------------------------------------------------------------------------
    | UX Copy - Call to Actions (CTAs)
    | Note: Profile moved to auth.php, Dashboard moved to dashboard.php
    |--------------------------------------------------------------------------
    */
    'cta' => [
        'start_now' => 'Start Now',
        'get_started' => 'Get Started',
        'view_details' => 'View Details',
        'show_details' => 'Show Details',
        'confirm_booking' => 'Confirm Booking',
        'enable_notifications' => 'Enable Notifications',
        'save_changes' => 'Save Changes',
        'cancel' => 'Cancel',
        'submit' => 'Submit',
        'continue' => 'Continue',
        'back' => 'Back',
        'next' => 'Next',
        'finish' => 'Finish',
        'download' => 'Download',
        'upload' => 'Upload',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'create' => 'Create',
        'update' => 'Update',
        'search' => 'Search',
        'filter' => 'Filter',
        'export' => 'Export',
        'import' => 'Import',
        'print' => 'Print',
        'share' => 'Share',
        'copy' => 'Copy',
        'retry' => 'Retry',
        'contact_support' => 'Contact Support',
        'learn_more' => 'Learn More',
        'add' => 'Add',
        'remove' => 'Remove',
        'close' => 'Close',
        'open' => 'Open',
        'expand' => 'Expand',
        'explore' => 'Explore',
        'collapse' => 'Collapse',
        'refresh' => 'Refresh',
        'reload' => 'Reload',
        'clear' => 'Clear',
        'reset' => 'Reset',
        'apply' => 'Apply',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'save' => 'Save',
        'send' => 'Send',
        'receive' => 'Receive',
        'more' => 'More',
        'menu' => 'Menu',
    ],

    /*
    |--------------------------------------------------------------------------
    | Empty States
    |--------------------------------------------------------------------------
    */
    'empty' => [
        'no_results' => 'No matching results',
        'no_results_hint' => 'Adjust your filters or try another port.',
        'no_shipments' => 'No shipments yet',
        'no_shipments_hint' => 'Start by creating a new shipment.',
        'no_bookings' => 'No bookings',
        'no_bookings_hint' => 'Create a new booking to get started.',
        'no_quotes' => 'No quotes',
        'no_quotes_hint' => 'Get a quote for your shipment.',
        'no_notifications' => 'No new notifications',
        'no_notifications_hint' => 'We\'ll notify you when there are updates.',
        'no_documents' => 'No documents',
        'no_documents_hint' => 'Upload required documents.',
        'no_containers' => 'No containers',
        'no_containers_hint' => 'Add a container to continue.',
        'no_customs' => 'No customs clearance requests',
        'no_customs_hint' => 'Submit a new clearance request.',
        'no_data' => 'No data available',
        'no_items' => 'No items',
        'no_history' => 'No history',
        'no_favorites' => 'No favorites',
        'no_favorites_hint' => 'Add items to favorites for quick access.',
        'no_search_results' => 'We couldn\'t find what you\'re looking for',
        'no_search_results_hint' => 'Try using different keywords.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    */
    'errors' => [
        'generic' => 'An error occurred',
        'generic_hint' => 'Please try again or contact support.',
        'network_error' => 'Connection error',
        'network_error_hint' => 'Check your internet connection.',
        'server_error' => 'Server error',
        'server_error_hint' => 'We\'re working on fixing this. Try again later.',
        'not_found' => 'Page not found',
        'not_found_hint' => 'Check the URL or return to the homepage.',
        'unauthorized' => 'Unauthorized',
        'unauthorized_hint' => 'Please log in to continue.',
        'forbidden' => 'Forbidden',
        'forbidden_hint' => 'You don\'t have permission to access this.',
        'validation_error' => 'Invalid input',
        'validation_error_hint' => 'Check the highlighted fields.',
        'timeout' => 'Request timeout',
        'timeout_hint' => 'The request took too long. Try again.',
        'file_too_large' => 'File too large',
        'file_too_large_hint' => 'Maximum size is {size} MB.',
        'invalid_format' => 'Invalid format',
        'invalid_format_hint' => 'Use {format} format.',
        'already_exists' => 'Already exists',
        'payment_failed' => 'Payment failed',
        'payment_failed_hint' => 'Check your card details.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Success Messages
    |--------------------------------------------------------------------------
    */
    'success' => [
        'saved' => 'Changes saved successfully.',
        'created' => 'Created successfully.',
        'updated' => 'Updated successfully.',
        'deleted' => 'Deleted successfully.',
        'uploaded' => 'Uploaded successfully.',
        'downloaded' => 'Downloaded successfully.',
        'sent' => 'Sent successfully.',
        'copied' => 'Copied.',
        'exported' => 'Exported successfully.',
        'imported' => 'Imported successfully.',
        'email_sent' => 'Email sent.',
        'sms_sent' => 'SMS sent.',
        'login_success' => 'Welcome back!',
        'logout_success' => 'Logged out.',
        'register_success' => 'Account created successfully.',
        'password_changed' => 'Password changed.',
        'email_verified' => 'Email verified.',
        'phone_verified' => 'Phone verified.',
        'profile_updated' => 'Profile updated.',
        'payment_success' => 'Payment successful.',
        'subscription_activated' => 'Subscription activated.',
        'subscription_cancelled' => 'Subscription cancelled.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Booking Messages
    |--------------------------------------------------------------------------
    */
    'booking' => [
        'confirmed' => 'Your booking has been confirmed.',
        'confirmed_with_ref' => 'Your booking has been confirmed. Reference number: :ref.',
        'pending' => 'Your booking is under review.',
        'cancelled' => 'Booking cancelled.',
        'expired' => 'Booking expired.',
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
    ],

    /*
    |--------------------------------------------------------------------------
    | Customs Clearance Messages
    |--------------------------------------------------------------------------
    */
    'customs' => [
        'appointment_scheduled' => 'Inspection appointment on :date at :time.',
        'appointment_hint' => 'Ensure all documents are ready.',
        'documents_required' => 'Required documents for customs clearance:',
        'pending_inspection' => 'Awaiting customs inspection.',
        'inspection_passed' => 'Customs inspection passed.',
        'inspection_failed' => 'Customs inspection requires review.',
        'clearance_approved' => 'Customs clearance approved.',
        'clearance_rejected' => 'Customs clearance rejected.',
        'duty_payment_required' => 'Duty payment required.',
        'duty_payment_received' => 'Duty payment received.',
        'documents_incomplete' => 'Documents incomplete.',
        'additional_docs_needed' => 'Additional documents needed.',
        'release_approved' => 'Goods released.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipment Status Messages
    |--------------------------------------------------------------------------
    */
    'shipment' => [
        'status_draft' => 'Draft',
        'status_pending' => 'Pending',
        'status_confirmed' => 'Confirmed',
        'status_in_transit' => 'In Transit',
        'status_at_port' => 'At Port',
        'status_customs' => 'Customs Clearance',
        'status_delivered' => 'Delivered',
        'status_cancelled' => 'Cancelled',
        'estimated_arrival' => 'Estimated arrival: :date',
        'departed_from' => 'Departed from :location',
        'arrived_at' => 'Arrived at :location',
        'delayed' => 'Expected delay: :hours hours',
        'on_time' => 'On time',
    ],

    /*
    |--------------------------------------------------------------------------
    | Form Labels & Placeholders
    |--------------------------------------------------------------------------
    */
    'form' => [
        'required_field' => 'Required field',
        'optional_field' => 'Optional',
        'select_option' => 'Select...',
        'search_placeholder' => 'Search here...',
        'enter_text' => 'Enter text',
        'choose_file' => 'Choose file',
        'drag_drop' => 'Drag and drop file here',
        'or' => 'or',
        'characters_remaining' => ':count characters remaining',
        'min_characters' => 'Minimum :count characters',
        'max_characters' => 'Maximum :count characters',
        'loading' => 'Loading...',
        'processing' => 'Processing...',
        'saving' => 'Saving...',
        'uploading' => 'Uploading...',
        'downloading' => 'Downloading...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Confirmation Messages
    |--------------------------------------------------------------------------
    */
    'confirm' => [
        'delete' => 'Are you sure you want to delete?',
        'delete_hint' => 'This action cannot be undone.',
        'cancel_booking' => 'Do you want to cancel the booking?',
        'cancel_booking_hint' => 'Cancellation fees may apply.',
        'submit_order' => 'Do you want to confirm the order?',
        'logout' => 'Do you want to log out?',
        'discard_changes' => 'Do you want to discard changes?',
        'continue_action' => 'Do you want to continue?',
        'yes' => 'Yes',
        'no' => 'No',
        'confirm' => 'Confirm',
        'cancel' => 'Cancel',
    ],

    /*
    |--------------------------------------------------------------------------
    | Time & Date
    |--------------------------------------------------------------------------
    */
    'time' => [
        'just_now' => 'Just now',
        'minutes_ago' => ':count minute ago|:count minutes ago',
        'hours_ago' => ':count hour ago|:count hours ago',
        'days_ago' => ':count day ago|:count days ago',
        'weeks_ago' => ':count week ago|:count weeks ago',
        'months_ago' => ':count month ago|:count months ago',
        'years_ago' => ':count year ago|:count years ago',
        'in_minutes' => 'In :count minute|In :count minutes',
        'in_hours' => 'In :count hour|In :count hours',
        'in_days' => 'In :count day|In :count days',
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'tomorrow' => 'Tomorrow',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'new_message' => 'You have a new message',
        'shipment_update' => 'Shipment update',
        'booking_confirmed' => 'Your booking is confirmed',
        'payment_received' => 'Payment received',
        'document_approved' => 'Document approved',
        'customs_cleared' => 'Customs cleared',
        'delivery_scheduled' => 'Delivery scheduled',
        'mark_all_read' => 'Mark all as read',
        'view_all' => 'View all',
        'settings' => 'Notification settings',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tooltips & Hints
    |--------------------------------------------------------------------------
    */
    'hints' => [
        'unsaved_changes' => 'You have unsaved changes',
        'auto_save_enabled' => 'Auto-save enabled',
        'last_saved' => 'Last saved: :time',
        'click_to_edit' => 'Click to edit',
        'drag_to_reorder' => 'Drag to reorder',
        'double_click' => 'Double click',
        'right_click' => 'Right click',
        'keyboard_shortcut' => 'Keyboard shortcut: :key',
        'beta_feature' => 'Beta feature',
        'new_feature' => 'New feature',
        'coming_soon' => 'Coming soon',
        'maintenance_mode' => 'Maintenance mode',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'showing' => 'Showing :from to :to of :total',
        'results' => ':count result|:count results',
        'per_page' => 'Per page',
        'first' => 'First',
        'last' => 'Last',
        'previous' => 'Previous',
        'next' => 'Next',
        'page' => 'Page :page',
        'of' => 'of',
    ],
];
