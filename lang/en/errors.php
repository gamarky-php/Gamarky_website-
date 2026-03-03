<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    */

    // Generic Errors
    'generic' => 'An error occurred',
    'generic_hint' => 'Try again or contact support.',
    'something_went_wrong' => 'Something went wrong',
    'try_again' => 'Please try again',
    
    // Network Errors
    'network_error' => 'Network error',
    'network_error_hint' => 'Check your internet connection.',
    'connection_lost' => 'Connection lost',
    'connection_restored' => 'Connection restored',
    
    // Server Errors
    'server_error' => 'Server error',
    'server_error_hint' => 'We\'re working on it. Try later.',
    'service_unavailable' => 'Service unavailable',
    'maintenance_mode' => 'Site under maintenance',
    
    // Page Errors
    'not_found' => 'Page not found',
    'not_found_hint' => 'Check the URL or return to home.',
    'page_not_found' => '404 - Page not found',
    'go_home' => 'Go to Homepage',
    
    // Authorization Errors
    'unauthorized' => 'Unauthorized',
    'unauthorized_hint' => 'Please login to continue.',
    'forbidden' => 'Forbidden',
    'forbidden_hint' => 'You don\'t have permission to access.',
    'access_denied' => 'Access denied',
    'permission_required' => 'Permission required',
    
    // Validation Errors
    'validation_error' => 'Validation error',
    'validation_error_hint' => 'Check the highlighted fields.',
    'invalid_input' => 'Invalid input',
    'required_fields' => 'Please fill all required fields',
    
    // Timeout Errors
    'timeout' => 'Request timeout',
    'timeout_hint' => 'Request took too long. Try again.',
    'request_timeout' => 'Request timeout',
    
    // File Errors
    'file_too_large' => 'File too large',
    'file_too_large_hint' => 'Maximum size is {size} MB.',
    'invalid_format' => 'Invalid format',
    'invalid_format_hint' => 'Use {format} format.',
    'upload_failed' => 'Upload failed',
    'file_not_found' => 'File not found',
    
    // Data Errors
    'already_exists' => 'Already exists',
    'not_found_in_db' => 'Record not found',
    'duplicate_entry' => 'Duplicate entry',
    'data_corrupted' => 'Data corrupted',
    
    // Payment Errors
    'payment_failed' => 'Payment failed',
    'payment_failed_hint' => 'Check your card details.',
    'payment_declined' => 'Payment declined',
    'insufficient_funds' => 'Insufficient funds',
    
    // Rate Limiting
    'too_many_requests' => 'Too many requests',
    'rate_limit_exceeded' => 'Rate limit exceeded',
    'please_wait' => 'Please wait before trying again',
];
