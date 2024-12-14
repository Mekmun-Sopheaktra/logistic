<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Import;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function getExpenses()
    {
        $Expense = Import::sum('total_import_price');
        return response()->json([
            "Total Expense" => $Expense,
            "status" => 200,
        ]);
    }

    public function getExpense($id_product)
    {
        $Expense = Import::where('id', $id_product)->sum('total_import_price');
        return response()->json([
            "Total Expense" => $Expense,
            "status" => 200,
        ]);
    }


    public function getTodayExpense()
    {

        $todayExpense = Expense::whereDate('created_at', today())->sum('expense_amount');
        return response()->json(['today_expense' => $todayExpense]);
    }

    public function getMonthExpense()
    {
        $now = Carbon::now();
        $summonthExpense = Expense::whereMonth('created_at', $now->month)->sum('expense_amount');
        return response()->json([
            "total" => $summonthExpense,
            'status' => 200
        ]);
    }


    public function getYearExpense()
    {
        $now = Carbon::now();
        $sumyearIncome = Expense::whereYear('created_at', $now->year)->sum('expense_amount');
        return response()->json([

            "total" => $sumyearIncome,
            'status' => 200
        ]);
    }
    public function getExpenseInRange($formDate, $toDate)
    {
        $formDate = date_format(date_create($formDate), "Y-m-d 0:0:0");
        $toDate = date_format(date_create($toDate), "Y-m-d 0:0:0");

        $total_expense = Expense::whereBetween('created_at', [$formDate, $toDate])
            ->sum('expense_amount');

        $expenseInRange = Expense::whereBetween('created_at', [$formDate, $toDate])
            ->get();

        return response()->json([
            "ExpenseInRange" => $expenseInRange,
            "total_expense" => $total_expense,
            'status' => 200
        ]);
    }

}
