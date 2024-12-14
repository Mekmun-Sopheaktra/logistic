<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\Income;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IncomeController extends Controller
{

    public function getIncome($id)
    {
        $income = Income::findOrFail($id);
        return response()->json([
            "income" => $income,
            'status' => 200,
        ]);
    }

    public function getIncomes()
    {
        $income = Income::sum('income_amount');

        return response()->json([
            "Income" => $income,
            'status' => 200
        ]);
    }

    public function getTodayIncome()
    {

        $todayIncome = Income::whereDate('created_at', today())->sum('income_amount');
        return response()->json(['today_income' => $todayIncome]);
    }

    public function getMonthIncome()
    {
        $now = Carbon::now();
        $summonthIncome = Income::whereMonth('created_at', $now->month)->sum('income_amount');
        return response()->json([
            "total"=> $summonthIncome,
            'status' => 200
        ]);

    }

    public function getIncomeInRange($formDate, $toDate)
    {

        $formDate = date_format(date_create($formDate), "Y-m-d 0:0:0");
        $toDate = date_format(date_create($toDate), "Y-m-d 0:0:0");

        $total_expense = Income::whereBetween('created_at', [$formDate, $toDate])
            ->sum('income_amount');

        $expenseInRange = Income::whereBetween('created_at', [$formDate, $toDate])
            ->get();

        return response()->json([
            "IncomeInRange" => $expenseInRange,
            "income_amount" => $total_expense,
            'status' => 200
        ]);
    }

    public function getYearIncome()
    {
        $now = Carbon::now();
        $sumyearIncome = Income::whereYear('created_at', $now->year)->sum('income_amount');
        return response()->json([

            "total" => $sumyearIncome,
            'status' => 200
        ]);
    }

}
