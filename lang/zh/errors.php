<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    */

    // Generic Errors
    'generic' => '发生错误',
    'generic_hint' => '请重试或联系支持。',
    'something_went_wrong' => '出了点问题',
    'try_again' => '请重试',
    
    // Network Errors
    'network_error' => '网络错误',
    'network_error_hint' => '请检查您的网络连接。',
    'connection_lost' => '连接丢失',
    'connection_restored' => '连接已恢复',
    
    // Server Errors
    'server_error' => '服务器错误',
    'server_error_hint' => '我们正在处理。请稍后再试。',
    'service_unavailable' => '服务不可用',
    'maintenance_mode' => '网站维护中',
    
    // Page Errors
    'not_found' => '页面未找到',
    'not_found_hint' => '检查网址或返回主页。',
    'page_not_found' => '404 - 页面未找到',
    'go_home' => '返回主页',
    
    // Authorization Errors
    'unauthorized' => '未授权',
    'unauthorized_hint' => '请登录以继续。',
    'forbidden' => '禁止',
    'forbidden_hint' => '您没有访问权限。',
    'access_denied' => '访问被拒绝',
    'permission_required' => '需要权限',
    
    // Validation Errors
    'validation_error' => '验证错误',
    'validation_error_hint' => '检查标记的字段。',
    'invalid_input' => '无效输入',
    'required_fields' => '请填写所有必填字段',
    
    // Timeout Errors
    'timeout' => '请求超时',
    'timeout_hint' => '请求时间过长。请重试。',
    'request_timeout' => '请求超时',
    
    // File Errors
    'file_too_large' => '文件太大',
    'file_too_large_hint' => '最大大小为 {size} MB。',
    'invalid_format' => '格式无效',
    'invalid_format_hint' => '使用 {format} 格式。',
    'upload_failed' => '上传失败',
    'file_not_found' => '文件未找到',
    
    // Data Errors
    'already_exists' => '已存在',
    'not_found_in_db' => '记录未找到',
    'duplicate_entry' => '重复条目',
    'data_corrupted' => '数据损坏',
    
    // Payment Errors
    'payment_failed' => '付款失败',
    'payment_failed_hint' => '检查您的卡详细信息。',
    'payment_declined' => '付款被拒绝',
    'insufficient_funds' => '余额不足',
    
    // Rate Limiting
    'too_many_requests' => '请求过多',
    'rate_limit_exceeded' => '超过频率限制',
    'please_wait' => '请稍候再试',
];
