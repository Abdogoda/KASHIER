<?php
namespace App\Traits;

use App\Models\FundAccount;
use App\Models\Payment;

trait PaymentTrait{
    /**
     * Add a payment record.
     *
     * @param string $type
     * @param int|null $shiftId
     * @param int|null $customerId
     * @param int|null $supplierId
     * @param int|null $employeeId
     * @param int|null $invoiceId
     * @param float $amount
     * @return Payment
     */
    public function addPayment($type, $shiftId, $customerId = null, $supplierId = null, $employeeId, $invoiceId, $amount, $fund_type = 'قبض'){
        $payment =  Payment::create([
            'type' => $type,
            'shift_id' => $shiftId,
            'customer_id' => $customerId,
            'supplier_id' => $supplierId,
            'employee_id' => $employeeId,
            'invoice_id' => $invoiceId,
            'amount' => $amount,
            'before_balance' => FundAccount::first()->balance,
            'payment_type' => $fund_type
        ]);
        
        $stystemBalance = FundAccount::first();
        if($fund_type == 'قبض'){
            $stystemBalance->increment('balance', $amount);
        }else{
            $stystemBalance->decrement('balance', $amount);
        }

        $payment->update(['after_balance' => FundAccount::first()->balance]);

        return $payment;
    }


    public function applyCustomerExcessPayment($customer, $extraPayment, $toaster_view = true){
        // Get all unpaid invoices with 'remaining' amount greater than 0
        $invoices = $customer->invoices()->where('payment_status', 'معلق')->orderBy('created_at', 'desc')->get();

        $original_extra = $extraPayment;
        
        foreach ($invoices as $invoice) {
            // Check how much remaining to be paid
            $remainingAmount = $invoice->remaining;

            if ($extraPayment <= 0) {
                break; // Stop if there is no extra payment left
            }

            if($invoice->payment_fund_status != 'معلق'){
                if ($extraPayment >= $remainingAmount) {
                    // Pay off the entire remaining amount
                    $invoice->paid += $remainingAmount;
                    $extraPayment -= $remainingAmount;
                    $invoice->remaining = 0;
                    $invoice->payment_status = 'تم الدفع';
                    $invoice->account_after += $remainingAmount;
                } else {
                    // Partially pay the remaining amount
                    $invoice->paid += $extraPayment;
                    $invoice->remaining -= $extraPayment;
                    $extraPayment = 0;
                    $invoice->account_after += $extraPayment;
                }
            }

            // Save the invoice
            $invoice->save();
        }

        // Update the customer's account amount
        $customer->account += $original_extra;
        $customer->save();
        logActivity(' قام الموظف بسداد مديونية فواتير العميل رقم '.$customer->id.' بقيمة '.$original_extra - $extraPayment, 'الفواتير');
        if ($toaster_view) {
            toastr()->success(' قام الموظف بسداد مديونية فواتير العميل رقم '.$customer->id.' بقيمة '.$original_extra - $extraPayment);
        }

        if($extraPayment > 0){
            logActivity(' قام الموظف بإيداع مبلغ '.$extraPayment.' في حساب العميل رقم '.$customer->id, 'العملاء');
            if ($toaster_view) {
                toastr()->success(' قام الموظف بإيداع مبلغ '.$extraPayment.' في حساب العميل رقم '.$customer->id);
            }
        }

        return $customer;
    }

    public function applySupplierExcessPayment($supplier, $extraPayment, $toaster_view = true){
        // Get all unpaid invoices with 'remaining' amount greater than 0
        $invoices = $supplier->invoices()->where('payment_status', 'معلق')->orderBy('created_at', 'desc')->get();

        $original_extra = $extraPayment;
        
        foreach ($invoices as $invoice) {
            // Check how much remaining to be paid
            $remainingAmount = $invoice->remaining;

            if ($extraPayment <= 0) {
                break; // Stop if there is no extra payment left
            }

            if($invoice->payment_fund_status != 'معلق'){
                if ($extraPayment >= $remainingAmount) {
                    // Pay off the entire remaining amount
                    $invoice->paid += $remainingAmount;
                    $extraPayment -= $remainingAmount;
                    $invoice->remaining = 0;
                    $invoice->payment_status = 'تم الدفع';
                } else {
                    // Partially pay the remaining amount
                    $invoice->paid += $extraPayment;
                    $invoice->remaining -= $extraPayment;
                    $extraPayment = 0;
                }
            }

            // Save the invoice
            $invoice->save();
        }

        // Update the supplier's account amount
        $supplier->account += $original_extra;
        $supplier->save();
        logActivity(' قام الموظف بسداد مديونية فواتير المورد رقم '.$supplier->id.' بقيمة '.$original_extra, 'الفواتير');
        if ($toaster_view) {
            toastr()->success(' قام الموظف بسداد مديونية فواتير المورد رقم '.$supplier->id.' بقيمة '.$original_extra);
        }

        return $supplier;
    }
}