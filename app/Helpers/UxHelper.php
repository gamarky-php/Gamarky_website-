<?php

namespace App\Helpers;

class UxHelper
{
    /**
     * Get UX copy by key with optional parameters
     * 
     * @param string $key
     * @param array $params
     * @param string|null $locale
     * @return string
     */
    public static function copy(string $key, array $params = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $translation = __("ux.{$key}", $params, $locale);
        
        return $translation;
    }

    /**
     * Get CTA (Call to Action) text
     * 
     * @param string $action
     * @param string|null $locale
     * @return string
     */
    public static function cta(string $action, ?string $locale = null): string
    {
        return self::copy("cta.{$action}", [], $locale);
    }

    /**
     * Get empty state message
     * 
     * @param string $type
     * @param string|null $locale
     * @return array
     */
    public static function emptyState(string $type, ?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        
        return [
            'message' => __("ux.empty.{$type}", [], $locale),
            'hint' => __("ux.empty.{$type}_hint", [], $locale),
        ];
    }

    /**
     * Get error message with hint
     * 
     * @param string $errorType
     * @param array $params
     * @param string|null $locale
     * @return array
     */
    public static function error(string $errorType, array $params = [], ?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        
        return [
            'message' => __("ux.errors.{$errorType}", $params, $locale),
            'hint' => __("ux.errors.{$errorType}_hint", $params, $locale),
        ];
    }

    /**
     * Get success message
     * 
     * @param string $action
     * @param string|null $locale
     * @return string
     */
    public static function success(string $action, ?string $locale = null): string
    {
        return self::copy("success.{$action}", [], $locale);
    }

    /**
     * Get booking message with reference
     * 
     * @param string $status
     * @param string|null $ref
     * @param string|null $locale
     * @return string
     */
    public static function booking(string $status, ?string $ref = null, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        
        if ($status === 'confirmed' && $ref) {
            return __('ux.booking.confirmed_with_ref', ['ref' => $ref], $locale);
        }
        
        return __("ux.booking.{$status}", [], $locale);
    }

    /**
     * Get customs clearance message with appointment details
     * 
     * @param string $type
     * @param array $params
     * @param string|null $locale
     * @return string
     */
    public static function customs(string $type, array $params = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        
        if ($type === 'appointment_scheduled') {
            $message = __('ux.customs.appointment_scheduled', $params, $locale);
            $hint = __('ux.customs.appointment_hint', [], $locale);
            return "{$message} {$hint}";
        }
        
        return __("ux.customs.{$type}", $params, $locale);
    }

    /**
     * Get shipment status with details
     * 
     * @param string $status
     * @param array $params
     * @param string|null $locale
     * @return string
     */
    public static function shipment(string $status, array $params = [], ?string $locale = null): string
    {
        return self::copy("shipment.{$status}", $params, $locale);
    }

    /**
     * Get confirmation dialog data
     * 
     * @param string $action
     * @param string|null $locale
     * @return array
     */
    public static function confirm(string $action, ?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        
        return [
            'title' => __("ux.confirm.{$action}", [], $locale),
            'hint' => __("ux.confirm.{$action}_hint", [], $locale),
            'confirm_text' => __('ux.confirm.confirm', [], $locale),
            'cancel_text' => __('ux.confirm.cancel', [], $locale),
        ];
    }

    /**
     * Get notification message
     * 
     * @param string $type
     * @param array $params
     * @param string|null $locale
     * @return string
     */
    public static function notification(string $type, array $params = [], ?string $locale = null): string
    {
        return self::copy("notifications.{$type}", $params, $locale);
    }

    /**
     * Get time ago text
     * 
     * @param \DateTime|string $datetime
     * @param string|null $locale
     * @return string
     */
    public static function timeAgo($datetime, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        
        if (is_string($datetime)) {
            $datetime = new \DateTime($datetime);
        }
        
        $now = new \DateTime();
        $diff = $now->diff($datetime);
        
        if ($diff->y > 0) {
            return __('ux.time.years_ago', ['count' => $diff->y], $locale);
        } elseif ($diff->m > 0) {
            return __('ux.time.months_ago', ['count' => $diff->m], $locale);
        } elseif ($diff->d > 7) {
            $weeks = floor($diff->d / 7);
            return __('ux.time.weeks_ago', ['count' => $weeks], $locale);
        } elseif ($diff->d > 0) {
            return __('ux.time.days_ago', ['count' => $diff->d], $locale);
        } elseif ($diff->h > 0) {
            return __('ux.time.hours_ago', ['count' => $diff->h], $locale);
        } elseif ($diff->i > 0) {
            return __('ux.time.minutes_ago', ['count' => $diff->i], $locale);
        } else {
            return __('ux.time.just_now', [], $locale);
        }
    }

    /**
     * Get pagination info
     * 
     * @param int $from
     * @param int $to
     * @param int $total
     * @param string|null $locale
     * @return string
     */
    public static function paginationInfo(int $from, int $to, int $total, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        
        return __('ux.pagination.showing', [
            'from' => $from,
            'to' => $to,
            'total' => $total,
        ], $locale);
    }

    /**
     * Format JSON response with UX messages
     * 
     * @param string $type ('success', 'error', 'info', 'warning')
     * @param string $messageKey
     * @param array $params
     * @param array $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function jsonResponse(
        string $type,
        string $messageKey,
        array $params = [],
        array $data = [],
        int $statusCode = 200
    ) {
        $locale = app()->getLocale();
        $message = self::copy($messageKey, $params, $locale);
        
        return response()->json([
            'type' => $type,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Format booking confirmation response
     * 
     * @param string $ref
     * @param array $bookingData
     * @return \Illuminate\Http\JsonResponse
     */
    public static function bookingConfirmed(string $ref, array $bookingData = [])
    {
        $message = self::booking('confirmed', $ref);
        
        return response()->json([
            'type' => 'success',
            'message' => $message,
            'data' => array_merge([
                'ref' => $ref,
            ], $bookingData),
        ], 200);
    }

    /**
     * Format customs appointment response
     * 
     * @param string $date
     * @param string $time
     * @param array $appointmentData
     * @return \Illuminate\Http\JsonResponse
     */
    public static function customsAppointment(string $date, string $time, array $appointmentData = [])
    {
        $message = self::customs('appointment_scheduled', [
            'date' => $date,
            'time' => $time,
        ]);
        
        return response()->json([
            'type' => 'info',
            'message' => $message,
            'data' => array_merge([
                'date' => $date,
                'time' => $time,
            ], $appointmentData),
        ], 200);
    }

    /**
     * Format empty state response
     * 
     * @param string $type
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function emptyStateResponse(string $type, array $data = [])
    {
        $emptyState = self::emptyState($type);
        
        return response()->json([
            'type' => 'info',
            'message' => $emptyState['message'],
            'hint' => $emptyState['hint'],
            'data' => $data,
        ], 200);
    }

    /**
     * Format error response
     * 
     * @param string $errorType
     * @param array $params
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function errorResponse(string $errorType, array $params = [], int $statusCode = 500)
    {
        $error = self::error($errorType, $params);
        
        return response()->json([
            'type' => 'error',
            'message' => $error['message'],
            'hint' => $error['hint'],
        ], $statusCode);
    }
}
