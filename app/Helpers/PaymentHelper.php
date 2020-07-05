<?php

namespace App\Helpers;

use App\Payment;
use App\PettyCash;
use App\Nonpurchase;
use App\SalaryPayment;
use App\Exceptions\Handler;

class PaymentHelper {
    public static function savePaymentPartial($data) {
        try {
            $payment = new Payment;
            $payment->payment_name  = $data['payment_name'];
            $payment->payment_type  = $data['payment_type'];
            $payment->payment_total = $data['payment_total'];
            $payment->payment_id    = $data['payment_id'];
            $payment->paid_date     = NULL;
            $payment->payment_method= 'TRANSFER';
            $payment->payment_status= 'PENDING';
            $payment->description   = '-';
            $payment->project_id    = $data['project_id'];
            $payment->save();
        } catch (Throwable $e) {
            report($e);

            return false;
        }

        return;
    }

    public static function generateCode() {

        $counted_code_by_year = PettyCash::whereRaw('SUBSTRING(number, 1, 4) = ' . date('Y'))->count();

        $code = self::getFirstCode() . 'KAS/' . ($counted_code_by_year + 1);

        return $code;
    }

    public static function generateCodeNonPurchase() {

        $counted_code_by_year = Nonpurchase::whereRaw('SUBSTRING(number, 1, 4) = ' . date('Y'))->count();

        $code = self::getFirstCode() . 'NP/' . ($counted_code_by_year + 1);

        return $code;
    }

    private static function getFirstCode() {
        $month_romawi = [
            '1' => 'I',
            '2' => 'II',
            '3' => 'III',
            '4' => 'IV',
            '5' => 'V',
            '6' => 'VI',
            '7' => 'VII',
            '8' => 'VIII',
            '9' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII',
        ];
        $month = (int) date('m');

        return  date('Y') . '/' . $month_romawi[$month] . '/';
    }

    public static function savePaymentToPettyCash($data) {
        if ($data['source_id'] == NULL) {
            $budget_for = 'OFFICE';
        } else {
            $budget_for = 'PROJECT';
        }
        $input = [
            'budget_for' => $budget_for,
            'project_id' => $data['project_id'] ? $data['project_id'] : NULL,
            'number' => self::generateCode(),
            'date' => $data['paid_date'],
            'noted_news' => $data['payment_name'],
            'nominal' => '-'.$data['payment_total'],
            'upload' => $data['upload'],
            'type' => 'DEBIT',
            'source_type' => 'PAYMENT',
            'source_id' => $data['id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        try {
            //check if exist
            $is_exist = PettyCash::where('source_type', 'PAYMENT')
                        ->where('source_id', $data['id'])->first();
            //if exist then soft delete existing
            if ($is_exist) {
                PettyCash::where('source_type', 'PAYMENT')
                ->where('source_id', $data['id'])
                ->delete();
            }
            PettyCash::create($input);
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }

    public static function updatePaymentProcessStatus($source, $id, $status, $reason = null) {
        try {
            if ($source == "NONPURCHASE") {
                Nonpurchase::where('id', $id)->update(['payment_process_status' => $status, 'payment_process_reason' => $reason]);
                return;
            }

            if ($source == "SALARY") {
                SalaryPayment::where('id', $id)->update(['payment_process_status' => $status, 'payment_process_reason' => $reason]);
                return;
            }
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }
}
