<?php

namespace App\Imports;

use App\Models\Supplier;

class FastSupplierImport
{
    /**
     * استيراد الموردين من ملف Excel باستخدام FastExcel
     */
    public function import($filePath)
    {
        $suppliers = collect();
        
        // قراءة الملف
        $fastExcel = new \Rap2hpoutre\FastExcel\FastExcel();
        $rows = $fastExcel->import($filePath);
        
        foreach ($rows as $row) {
            // تنظيف البيانات
            $companyName = trim($row['Company Name'] ?? $row['company_name'] ?? '');
            
            // تجاهل الصفوف الفارغة
            if (empty($companyName)) {
                continue;
            }

            // معالجة عمود Mr/Ms - قد يأتي بأسماء مختلفة
            $mrMs = trim($row['Mr/Ms'] ?? $row['mr_ms'] ?? '');
            
            // معالجة عمود الشركة بالصينية - قد يأتي بأسماء مختلفة
            $companyNameCn = trim($row['Company Name（CN）'] ?? $row['company_name_cn'] ?? '');
            
            $supplier = Supplier::create([
                'company_name' => $companyName,
                'province' => trim($row['Province'] ?? $row['province'] ?? ''),
                'city' => trim($row['City'] ?? $row['city'] ?? ''),
                'contact_person' => trim($row['Contact Person'] ?? $row['contact_person'] ?? ''),
                'mr_ms' => $mrMs,
                'mobile_phone' => trim($row['Mobile Phone'] ?? $row['mobile_phone'] ?? ''),
                'tel' => trim($row['Tel'] ?? $row['tel'] ?? ''),
                'fax' => trim($row['Fax'] ?? $row['fax'] ?? ''),
                'address' => trim($row['Address'] ?? $row['address'] ?? ''),
                'post_code' => trim($row['Post Code'] ?? $row['post_code'] ?? ''),
                'website' => $this->cleanUrl($row['Website'] ?? $row['website'] ?? ''),
                'introduction' => trim($row['Introduction'] ?? $row['introduction'] ?? ''),
                'main_products' => trim($row['Main Products'] ?? $row['main_products'] ?? ''),
                'company_name_cn' => $companyNameCn,
                'country_code' => 'CN',
                'status' => 'pending',
                'source' => 'excel_upload',
            ]);
            
            $suppliers->push($supplier);
        }
        
        return $suppliers;
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