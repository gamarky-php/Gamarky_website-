return [
    /*
    |--------------------------------------------------------------------------
    | UX Copy - Call to Actions (CTAs)
    | Note: Profile moved to auth.php, Dashboard moved to dashboard.php
    |--------------------------------------------------------------------------
    */
    'cta' => [
        'start_now' => '立即开始',
        'get_started' => '开始使用',
        'view_details' => '查看详情',
        'show_details' => '显示详情',
        'confirm_booking' => '确认预订',
        'enable_notifications' => '启用通知',
        'save_changes' => '保存更改',
        'cancel' => '取消',
        'submit' => '提交',
        'continue' => '继续',
        'back' => '返回',
        'next' => '下一步',
        'finish' => '完成',
        'download' => '下载',
        'upload' => '上传',
        'delete' => '删除',
        'edit' => '编辑',
        'create' => '创建',
        'update' => '更新',
        'search' => '搜索',
        'filter' => '筛选',
        'export' => '导出',
        'import' => '导入',
        'print' => '打印',
        'share' => '分享',
        'copy' => '复制',
        'retry' => '重试',
        'contact_support' => '联系支持',
        'learn_more' => '了解更多',
        'add' => '添加',
        'remove' => '移除',
        'close' => '关闭',
        'open' => '打开',
        'expand' => '展开',
        'explore' => '探索',
        'collapse' => '折叠',
        'refresh' => '刷新',
        'reload' => '重新加载',
        'clear' => '清除',
        'reset' => '重置',
        'apply' => '应用',
        'approve' => '批准',
        'reject' => '拒绝',
        'save' => '保存',
        'send' => '发送',
        'receive' => '接收',
        'more' => '更多',
        'menu' => '菜单',
    ],

    /*
    |--------------------------------------------------------------------------
    | Empty States
    |--------------------------------------------------------------------------
    */
    'empty' => [
        'no_results' => '没有匹配结果',
        'no_results_hint' => '调整您的筛选条件或尝试其他港口。',
        'no_shipments' => '暂无货运',
        'no_shipments_hint' => '开始创建新的货运。',
        'no_bookings' => '暂无预订',
        'no_bookings_hint' => '创建新预订以开始。',
        'no_quotes' => '暂无报价',
        'no_quotes_hint' => '获取您的货运报价。',
        'no_notifications' => '暂无新通知',
        'no_notifications_hint' => '有更新时我们会通知您。',
        'no_documents' => '暂无文档',
        'no_documents_hint' => '上传所需文档。',
        'no_containers' => '暂无集装箱',
        'no_containers_hint' => '添加集装箱以继续。',
        'no_customs' => '暂无清关请求',
        'no_customs_hint' => '提交新的清关请求。',
        'no_data' => '暂无数据',
        'no_items' => '暂无项目',
        'no_history' => '暂无历史记录',
        'no_favorites' => '暂无收藏',
        'no_favorites_hint' => '添加项目到收藏以便快速访问。',
        'no_search_results' => '找不到您要查找的内容',
        'no_search_results_hint' => '尝试使用其他关键词。',
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    */
    'errors' => [
        'generic' => '发生错误',
        'generic_hint' => '请重试或联系支持。',
        'network_error' => '连接错误',
        'network_error_hint' => '检查您的互联网连接。',
        'server_error' => '服务器错误',
        'server_error_hint' => '我们正在修复此问题。请稍后重试。',
        'not_found' => '页面未找到',
        'not_found_hint' => '检查网址或返回首页。',
        'unauthorized' => '未授权',
        'unauthorized_hint' => '请登录以继续。',
        'forbidden' => '禁止访问',
        'forbidden_hint' => '您没有访问权限。',
        'validation_error' => '输入无效',
        'validation_error_hint' => '检查突出显示的字段。',
        'timeout' => '请求超时',
        'timeout_hint' => '请求时间过长。请重试。',
        'file_too_large' => '文件过大',
        'file_too_large_hint' => '最大大小为 {size} MB。',
        'invalid_format' => '格式无效',
        'invalid_format_hint' => '请使用 {format} 格式。',
        'already_exists' => '已存在',
        'payment_failed' => '支付失败',
        'payment_failed_hint' => '检查您的卡详情。',
    ],

    /*
    |--------------------------------------------------------------------------
    | Success Messages
    |--------------------------------------------------------------------------
    */
    'success' => [
        'saved' => '更改已成功保存。',
        'created' => '创建成功。',
        'updated' => '更新成功。',
        'deleted' => '删除成功。',
        'uploaded' => '上传成功。',
        'downloaded' => '下载成功。',
        'sent' => '发送成功。',
        'copied' => '已复制。',
        'exported' => '导出成功。',
        'imported' => '导入成功。',
        'email_sent' => '邮件已发送。',
        'sms_sent' => '短信已发送。',
        'login_success' => '欢迎回来！',
        'logout_success' => '已退出登录。',
        'register_success' => '账户创建成功。',
        'password_changed' => '密码已更改。',
        'email_verified' => '邮箱已验证。',
        'phone_verified' => '手机已验证。',
        'profile_updated' => '个人资料已更新。',
        'payment_success' => '支付成功。',
        'subscription_activated' => '订阅已激活。',
        'subscription_cancelled' => '订阅已取消。',
    ],

    /*
    |--------------------------------------------------------------------------
    | Booking Messages
    |--------------------------------------------------------------------------
    */
    'booking' => [
        'confirmed' => '您的预订已确认。',
        'confirmed_with_ref' => '您的预订已确认。参考号：:ref。',
        'pending' => '您的预订正在审核中。',
        'cancelled' => '预订已取消。',
        'expired' => '预订已过期。',
        'modified' => '预订已修改。',
        'payment_pending' => '待付款。',
        'payment_received' => '已收到付款。',
        'ready_for_pickup' => '准备提货。',
        'in_transit' => '运输中。',
        'delivered' => '已交付。',
        'ref_label' => '参考号',
        'booking_number' => '预订号',
        'container_number' => '集装箱号',
        'tracking_number' => '跟踪号',
    ],

    /*
    |--------------------------------------------------------------------------
    | Customs Clearance Messages
    |--------------------------------------------------------------------------
    */
    'customs' => [
        'appointment_scheduled' => '检查预约时间：:date :time。',
        'appointment_hint' => '确保所有文档准备就绪。',
        'documents_required' => '清关所需文档：',
        'pending_inspection' => '等待海关检查。',
        'inspection_passed' => '海关检查通过。',
        'inspection_failed' => '海关检查需要复核。',
        'clearance_approved' => '海关已批准放行。',
        'clearance_rejected' => '海关拒绝放行。',
        'duty_payment_required' => '需要支付关税。',
        'duty_payment_received' => '已收到关税。',
        'documents_incomplete' => '文档不完整。',
        'additional_docs_needed' => '需要补充文档。',
        'release_approved' => '货物已放行。',
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipment Status Messages
    |--------------------------------------------------------------------------
    */
    'shipment' => [
        'status_draft' => '草稿',
        'status_pending' => '待处理',
        'status_confirmed' => '已确认',
        'status_in_transit' => '运输中',
        'status_at_port' => '在港口',
        'status_customs' => '清关中',
        'status_delivered' => '已交付',
        'status_cancelled' => '已取消',
        'estimated_arrival' => '预计到达：:date',
        'departed_from' => '已从 :location 出发',
        'arrived_at' => '已到达 :location',
        'delayed' => '预计延迟：:hours 小时',
        'on_time' => '准时',
    ],

    /*
    |--------------------------------------------------------------------------
    | Form Labels & Placeholders
    |--------------------------------------------------------------------------
    */
    'form' => [
        'required_field' => '必填字段',
        'optional_field' => '可选',
        'select_option' => '请选择...',
        'search_placeholder' => '在此搜索...',
        'enter_text' => '输入文本',
        'choose_file' => '选择文件',
        'drag_drop' => '拖放文件到此处',
        'or' => '或',
        'characters_remaining' => '剩余 :count 个字符',
        'min_characters' => '最少 :count 个字符',
        'max_characters' => '最多 :count 个字符',
        'loading' => '加载中...',
        'processing' => '处理中...',
        'saving' => '保存中...',
        'uploading' => '上传中...',
        'downloading' => '下载中...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Confirmation Messages
    |--------------------------------------------------------------------------
    */
    'confirm' => [
        'delete' => '您确定要删除吗？',
        'delete_hint' => '此操作无法撤消。',
        'cancel_booking' => '您要取消预订吗？',
        'cancel_booking_hint' => '可能会收取取消费用。',
        'submit_order' => '您要确认订单吗？',
        'logout' => '您要退出登录吗？',
        'discard_changes' => '您要放弃更改吗？',
        'continue_action' => '您要继续吗？',
        'yes' => '是',
        'no' => '否',
        'confirm' => '确认',
        'cancel' => '取消',
    ],

    /*
    |--------------------------------------------------------------------------
    | Time & Date
    |--------------------------------------------------------------------------
    */
    'time' => [
        'just_now' => '刚刚',
        'minutes_ago' => ':count 分钟前',
        'hours_ago' => ':count 小时前',
        'days_ago' => ':count 天前',
        'weeks_ago' => ':count 周前',
        'months_ago' => ':count 个月前',
        'years_ago' => ':count 年前',
        'in_minutes' => ':count 分钟后',
        'in_hours' => ':count 小时后',
        'in_days' => ':count 天后',
        'today' => '今天',
        'yesterday' => '昨天',
        'tomorrow' => '明天',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'new_message' => '您有新消息',
        'shipment_update' => '货运更新',
        'booking_confirmed' => '您的预订已确认',
        'payment_received' => '已收到付款',
        'document_approved' => '文档已批准',
        'customs_cleared' => '已清关',
        'delivery_scheduled' => '已安排交付',
        'mark_all_read' => '标记全部为已读',
        'view_all' => '查看全部',
        'settings' => '通知设置',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tooltips & Hints
    |--------------------------------------------------------------------------
    */
    'hints' => [
        'unsaved_changes' => '您有未保存的更改',
        'auto_save_enabled' => '已启用自动保存',
        'last_saved' => '上次保存：:time',
        'click_to_edit' => '点击编辑',
        'drag_to_reorder' => '拖动以重新排序',
        'double_click' => '双击',
        'right_click' => '右键点击',
        'keyboard_shortcut' => '键盘快捷键：:key',
        'beta_feature' => '测试版功能',
        'new_feature' => '新功能',
        'coming_soon' => '即将推出',
        'maintenance_mode' => '维护模式',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'showing' => '显示 :from 到 :to，共 :total',
        'results' => ':count 个结果',
        'per_page' => '每页',
        'first' => '首页',
        'last' => '末页',
        'previous' => '上一页',
        'next' => '下一页',
        'page' => '第 :page 页',
        'of' => '共',
    ],
];
