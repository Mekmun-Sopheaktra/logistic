<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function getRevenues()
    {
        $Revenue = Revenue::sum('revenue');
        return response()->json([
            "Total Revenue" => $Revenue,
            "status" => 200,
        ]);
    }


    public function getTodayRevenue()
    {

        $todayRevenue = Revenue::whereDate('created_at', today())->sum('revenue');
        return response()->json(['today_revenue' => $todayRevenue]);
    }

    public function getMonthRevenue()
    {
        $now = Carbon::now();
        $sumMonthRevenue = Revenue::whereMonth('created_at', $now->month)->sum('revenue');
        return response()->json([
            "total" => $sumMonthRevenue,
            'status' => 200
        ]);
    }


    public function getYearRevenue()
    {
        $now = Carbon::now();
        $sumYearRevenue = Revenue::whereYear('created_at', $now->year)->sum('revenue');
        return response()->json([

            "total" => $sumYearRevenue,
            'status' => 200
        ]);
    }
    public function getRevenueInRange($formDate, $toDate)
    {

        $formDate = date_format(date_create($formDate), "Y-m-d 0:0:0");
        $toDate = date_format(date_create($toDate), "Y-m-d 0:0:0");

        $total_revenue = Revenue::whereBetween('created_at', [$formDate, $toDate])
            ->sum('revenue');

        $revenueInRange = Revenue::whereBetween('created_at', [$formDate, $toDate])
            ->get();

        return response()->json([
            "RevenueInRange" => $revenueInRange,
            "total_revenue" => $total_revenue,
            'status' => 200
        ]);
    }
}