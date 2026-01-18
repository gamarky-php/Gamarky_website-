<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class SupplierImport implements ToModel, WithHeadingRow
{
    /**
     * Transform each row into a Supplier model
     */
    public function model(array $row)
    {
        // تنظيف البيانات
        $companyName = trim($row['company_name'] ?? '');
        
        // تجاهل الصفوف الفارغة
        if (empty($companyName)) {
            return null;
        }

        // معالجة عمود Mr/Ms - قد يأتي بأسماء مختلفة
        $mrMs = trim($row['mr/ms'] ?? $row['mr_ms'] ?? '');
        
        // معالجة عمود الشركة بالصينية - قد يأتي بأسماء مختلفة
        $companyNameCn = trim($row['company_name（cn）'] ?? $row['company_name_cn'] ?? '');
        
        return new Supplier([
            'company_name' => $companyName,
            'province' => trim($row['province'] ?? ''),
            'city' => trim($row['city'] ?? ''),
            'contact_person' => trim($row['contact_person'] ?? ''),
            'mr_ms' => $mrMs,
            'mobile_phone' => trim($row['mobile_phone'] ?? ''),
            'tel' => trim($row['tel'] ?? ''),
            'fax' => trim($row['fax'] ?? ''),
            'address' => trim($row['address'] ?? ''),
            'post_code' => trim($row['post_code'] ?? ''),
            'website' => $this->cleanUrl($row['website'] ?? ''),
            'introduction' => trim($row['introduction'] ?? ''),
            'main_products' => trim($row['main_products'] ?? ''),
            'company_name_cn' => $companyNameCn,
            'country_code' => 'CN',
            'status' => 'pending',
            'source' => 'excel_upload',
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * تحديد رقم صف العناوين
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * تنظيف رابط الموقع الإلكتروني - إزالة البروتوكول
     */
    private function cleanUrl($url)
    {
        $url = trim($url);
        if (empty($url)) {
            return null;
        }

        // إزالة http:// أو https://
        $url = preg_replace('/^https?:\/\//', '', $url);
        
        return $url;
    }
}