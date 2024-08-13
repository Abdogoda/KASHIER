<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder{

    public function run(): void{
        $permissions = [

            // invoices
            'عرض فواتير السندات' => 'view_invoices',
            'عرض فاتورة سند' => 'view_invoice',
            'اضافة فاتورة سند' => 'add_invoice',
            'استرجاع فاتورة سند' => 'return_invoice',
            'طباعة فاتورة سند' => 'print_invoice',

            'عرض فواتير البيع' => 'view_selling_invoices',
            'عرض فاتورة بيع' => 'view_selling_invoice',
            'اضافة فاتورة بيع' => 'add_selling_invoice',
            'استرجاع فاتورة بيع' => 'return_selling_invoice',
            'توصيل فاتورة بيع' => 'delivery_selling_invoice',
            'طباعة فاتورة بيع' => 'print_selling_invoice',
            'سداد مديونية فاتورة بيع' => 'repayment_selling_invoice',
            'صرف مديونية للعميل فاتورة بيع' => 'repayment_for_customer_selling_invoice',
            'استلام مبلغ فاتورة بيع' => 'payment_fund_selling_invoice',

            'عرض فواتير الشراء' => 'view_purchasing_invoices',
            'عرض فاتورة شراء' => 'view_purchasing_invoice',
            'اضافة فاتورة شراء' => 'add_purchasing_invoice',
            'طباعة فاتورة شراء' => 'print_purchasing_invoice',
            'سداد مديونية فاتورة شراء' => 'repayment_purchasing_invoice',

            // warehouse
            'عرض المخزن' => 'view_warehouse',
            'تعديل الرصيد الافتتاحي' => 'edit_warehouse',

            // employees
            'عرض موظفين' => 'view_employees',
            'عرض موظف' => 'view_employee',
            'اضافة موظف' => 'add_employee',
            'تغيير حالة الموظف' => 'change_employee_status',
            'تغيير وظيفة الموظف' => 'change_employee_role',

            // customers
            'عرض عملاء' => 'view_customers',
            'عرض عميل' => 'view_customer',
            'اضافة عميل' => 'add_customer',
            'تعديل عميل' => 'edit_customer',
            'ارشفة عميل' => 'archive_customer',

            // suppliers
            'عرض موردين' => 'view_suppliers',
            'عرض مورد' => 'view_supplier',
            'اضافة مورد' => 'add_supplier',
            'تعديل مورد' => 'edit_supplier',
            'ارشفة مورد' => 'archive_supplier',

            // settings
            'عرض اعدادات' => 'view_settings',
            'تعديل اعدادات' => 'edit_settings',

            // activities
            'عرض العمليات' => 'view_activities',

            // notifications
            'حذف الاشعارات' => 'delete_notifications',

            // accounts
            'ادارة الخزنة' => 'safe',
            'كشف حساب' => 'view_account',

            // shifts
            'عرض ورديات' => 'view_shifts',
            'عرض وردية' => 'view_shift',
            'بدء وردية' => 'start_shift',
            'وقف وردية' => 'end_shift',

            // roles
            'عرض وظائف' => 'view_roles',
            'عرض وظيفة' => 'view_role',
            'اضافة وظيفة' => 'add_role',
            'تعديل وظيفة' => 'edit_role',
            'حذف وظيفة' => 'delete_role',
            
            // products
            'عرض منتجات' => 'view_products',
            'اضافة منتج' => 'add_product',
            'عرض منتج' => 'view_product',
            'تعديل منتج' => 'edit_product',

            // statistics
            'عرض احصائيات المنتجات' => 'products_statistics',
            'عرض احصائيات المخزن' => 'warehouse_statistics',
            'عرض احصائيات المعاملات' => 'transaction_statistics',
            'عرض احصائيات الفواتير' => 'invoices_statistics',
            'عرض احصائيات العملاء' => 'customers_statistics',
        ];


        foreach ($permissions as $ar_name => $en_name) {
            Permission::create([
                'ar_name' => $ar_name,
                'en_name' => $en_name,
            ]);
        }
    }
}