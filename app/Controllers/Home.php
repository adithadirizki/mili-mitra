<?php

namespace App\Controllers;

use App\Models\TransaksiModel;

class Home extends BaseController
{
    public function index()
    {
        $transactionModel = new TransaksiModel();
        $today = $transactionModel->getStatisticTransactionToday();
        $week = $transactionModel->getTransactionOneWeek();
        $oneMonth = $transactionModel->getTransactionOneMonth();
        $oneYear = $transactionModel->getTransactionOneYear();

        $status = array_column($today, "status");
        $time_now = strtotime('now');
        $time_last_week = strtotime('-6 day');
        $time_last_month = strtotime('-29 day');
        $time_last_year = strtotime('-11 month');
        $daysofweek = [];
        $dates = [];
        $months = [];

        while ($time_last_week <= $time_now) {
            $daysofweek[] = date('w', $time_last_week);
            $time_last_week = strtotime("+1 day", $time_last_week);
        }

        while ($time_last_month <= $time_now) {
            $dates[] = date('Y-m-d', $time_last_month);
            $time_last_month = strtotime("+1 day", $time_last_month);
        }

        while ($time_last_year <= $time_now) {
            $months[] = date('Y-m', $time_last_year);
            $time_last_year = strtotime("+1 month", $time_last_year);
        }

        // dd($week);

        $transaction = [
            "today" => [
                "failed" => ($index = array_search(2, $status)) > -1 ? $today[$index] : (object) [
                    "status" => "2",
                    "sum" => "0",
                    "total" => "0"
                ],
                "refund" => ($index = array_search(3, $status)) > -1 ? $today[$index] : (object) [
                    "status" => "3",
                    "sum" => "0",
                    "total" => "0"
                ],
                "success" => ($index = array_search(4, $status)) > -1 ? $today[$index] : (object) [
                    "status" => "4",
                    "sum" => "0",
                    "total" => "0"
                ],
            ],
            "oneWeek" => [
                "daysofweek" => $daysofweek,
                "sum" => array_fill(0, 7, 0),
                "total" => array_fill(0, 7, 0),
            ],
            "oneMonth" => [
                "dates" => $dates,
                "sum" => array_fill(0, 30, 0),
                "total" => array_fill(0, 30, 0),
            ],
            "oneYear" => [
                "months" => $months,
                "sum" => array_fill(0, 12, 0),
                "total" => array_fill(0, 12, 0),
            ]
        ];

        foreach ($week as $value) {
            $index = array_search($value->dayofweek - 1, $daysofweek);
            $transaction['oneWeek']['sum'][$index] = $value->sum;
            $transaction['oneWeek']['total'][$index] = $value->total;
        }

        foreach ($oneMonth as $value) {
            $index = array_search($value->date, $dates);
            $transaction['oneMonth']['sum'][$index] = $value->sum;
            $transaction['oneMonth']['total'][$index] = $value->total;
        }

        foreach ($oneYear as $value) {
            $index = array_search($value->month, $months);
            $transaction['oneYear']['sum'][$index] = $value->sum;
            $transaction['oneYear']['total'][$index] = $value->total;
        }

        $data = [
            "title" => "Dashboard",
            "nav_active" => "dashboard",
            "data" => [
                "transaction" => $transaction
            ]
        ];
        return view('dashboard', $data);
    }
}
