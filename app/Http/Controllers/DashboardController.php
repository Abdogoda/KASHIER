<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Notification;
use App\Models\PurchasingInvoice;
use App\Models\SellingInvoice;
use App\Models\Supplier;
use App\Models\WarehouseProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller{
    public function index(){
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $currentYear = Carbon::now()->year;

        // records
        $warehouse_products = WarehouseProduct::orderBy('balance', 'desc')->limit(5)->get();

        // counts
        $counts = [
            ['title' => 'الموظفون', 'amount' => Employee::count(), 'color' => 'primary', 'icon' => 'settings', 'url' => 'employees.index'],
            ['title' => 'العملاء', 'amount' => Customer::count(), 'color' => 'warning', 'icon' => 'users', 'url' => 'customers.index'],
            ['title' => 'الموردون', 'amount' => Supplier::count(), 'color' => 'danger', 'icon' => 'user-plus', 'url' => 'suppliers.index'],
            ['title' => 'الفواتير', 'amount' => SellingInvoice::count() + PurchasingInvoice::count() + Invoice::count(), 'color' => 'success', 'icon' => 'dollar-sign', 'url' => 'dashboard'],
        ];
        
        // top sold products
        $top_sold = InvoiceProduct::whereNotNull('selling_invoice_id')
            ->whereBetween('invoice_products.created_at', [$currentMonthStart, $currentMonthEnd])
            ->select('products.name', DB::raw('SUM(invoice_products.quantity) as total_quantity'))
            ->join('products', 'invoice_products.product_id', '=', 'products.id')
            ->groupBy('products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // invoices count per month
        $invoices_count_this_month = [
            $this->countInvoicesCurrentMonth('selling_invoices'),
            $this->countInvoicesCurrentMonth('purchasing_invoices'),
            $this->countInvoicesCurrentMonth('invoices'),
        ];

        // payments sum per month
        $payment_sum_per_month = [
            $this->sumAmountsByMonth($type='قبض'),
            $this->sumAmountsByMonth($type='صرف'),
        ];

        // most customers
        $top_customers = $this->topAttributeByInvoicesCount('selling_invoices', 'customer_id');

        // 
        $selling_invoices_this_month = $this->invoicesPerDay('selling_invoices');


        return view('index', compact(
            'counts',
            'warehouse_products',
            'top_sold',
            'invoices_count_this_month',
            'payment_sum_per_month',
            'top_customers',
            'selling_invoices_this_month',
        ));
    }

    function countInvoicesCurrentMonth($table, $type=null){
        $currentMonth = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        if($type){
            $count = DB::table($table)
                ->whereBetween('created_at', [$currentMonth, $currentMonthEnd])
                ->where('type', $type)
                ->count();
        }else{
            $count = DB::table($table)
                ->whereBetween('created_at', [$currentMonth, $currentMonthEnd])
                ->count();
        }
        return $count;
    }

    function sumAmountsByMonth($type){
        // $results = DB::table('payments')
        //     ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total_amount'))
        //     ->whereYear('created_at', Carbon::now()->year)
        //     ->where('payment_type', $type)
        //     ->groupBy('month')
        //     ->get()
        //     ->pluck('total_amount', 'month')
        //     ->toArray();

        $results = DB::table('payments')
            ->select(DB::raw("strftime('%m', created_at) as month"), DB::raw('SUM(amount) as total_amount'))
            ->where(DB::raw("strftime('%Y', created_at)"), Carbon::now()->year)
            ->where('payment_type', $type)
            ->groupBy(DB::raw("strftime('%m', created_at)"))
            ->get()
            ->pluck('total_amount', 'month')
            ->toArray();

        $months = range(1, 12);
        return collect($months)->map(function ($month) use ($results) {
            return $results[$month] ?? 0;
        })->toArray();
    }

    function topAttributeByInvoicesCount($table, $attribute){
        $records = DB::table($table)
            ->select($attribute, DB::raw('COUNT(*) as count'), DB::raw('SUM(total_cost) as sum'))
            ->whereNotNull($attribute)
            ->whereYear('invoice_date', Carbon::now()->year)
            ->groupBy($attribute)
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();

        return $records;
    }

    function invoicesPerDay($table){
        $startDate = Carbon::now()->startOfMonth()->modify('last sunday');
        $endDate = $startDate->copy()->addWeeks(5)->endOfWeek();
    
        $dates = collect(range(0, $endDate->diffInDays($startDate)))
            ->map(function ($day) use ($startDate) {
                $date = $startDate->copy()->addDays($day);
                return [
                    'day' => $date->format('Y-m-d'),
                    'day_number' => $date->day,
                    'day_name' => $date->format('l'),
                    'status' => 'view', // Initially set all days to 'view'
                    'count' => 0
                ];
            });
    
        $invoiceCounts = DB::table($table)
            ->select(DB::raw('DATE(invoice_date) as day'), DB::raw('COUNT(*) as count'))
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->groupBy('day')
            ->get()
            ->pluck('count', 'day');
    
        return $dates->map(function ($item, $key) use ($invoiceCounts) {
            $item['count'] = $invoiceCounts[$item['day']] ?? 0;
            $item['status'] = $item['day_name'] === 'Saturday' || $item['day_name'] === 'Sunday' ? 'hidden' : 'view';
            return $item;
        })->toArray();
    }


    public function notification_read(Request $request){
        $notifications = Notification::where('read', false)->orderBy('created_at', 'DESC')->limit(7)->delete();
        toastr()->success('تم عمل الاشعارات كمقروءة');
        return redirect()->back();
    }
}