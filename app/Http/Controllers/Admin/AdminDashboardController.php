<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'month');

        switch ($filter) {
            case 'today':
                $start = Carbon::today();
                $end = Carbon::today()->endOfDay();
                $prevStart = Carbon::yesterday()->startOfDay();
                $prevEnd = Carbon::yesterday()->endOfDay();
                break;
            case 'year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                $prevStart = Carbon::now()->subYear()->startOfYear();
                $prevEnd = Carbon::now()->subYear()->endOfYear();
                break;
            case 'month':
            default:
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                $prevStart = Carbon::now()->subMonth()->startOfMonth();
                $prevEnd = Carbon::now()->subMonth()->endOfMonth();
                break;
        }

        // Current period data
        $sales = Invoice::whereBetween('created_at', [$start, $end])->count();
        $revenue = Invoice::whereBetween('created_at', [$start, $end])->sum('amount');
        $customers = User::whereBetween('created_at', [$start, $end])->count();

        // Previous period data
        $prevSales = Invoice::whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $prevRevenue = Invoice::whereBetween('created_at', [$prevStart, $prevEnd])->sum('amount');
        $prevCustomers = User::whereBetween('created_at', [$prevStart, $prevEnd])->count();

        // Growth calculations (avoid division by zero)
        $salesGrowth = $prevSales > 0 ? round((($sales - $prevSales) / $prevSales) * 100, 2) : ($sales > 0 ? 100 : 0);
        $revenueGrowth = $prevRevenue > 0 ? round((($revenue - $prevRevenue) / $prevRevenue) * 100, 2) : ($revenue > 0 ? 100 : 0);
        $customerGrowth = $prevCustomers > 0 ? round((($customers - $prevCustomers) / $prevCustomers) * 100, 2) : ($customers > 0 ? 100 : 0);

        // Prepare chart data for last 5 months
        $days = collect(range(9, 0))->map(function ($i) {
            return Carbon::now()->subDays($i)->format('Y-m-d');
        });

        $chartSales = $days->map(function ($day) {
            return Invoice::whereDate('created_at', $day)->count();
        });

        $chartRevenue = $days->map(function ($day) {
            return Invoice::whereDate('created_at', $day)->sum('amount');
        });

        $chartCustomers = $days->map(function ($day) {
            return User::whereDate('created_at', $day)->count();
        });

        $categories = $days->map(function ($day) {
            return Carbon::parse($day)->format('d M'); // e.g. Mon, Tue...
        });

        return view('dashboard.admin.main', compact(
            'sales',
            'revenue',
            'customers',
            'chartSales',
            'chartRevenue',
            'chartCustomers',
            'categories',
            'salesGrowth',
            'revenueGrowth',
            'customerGrowth',
            'filter'
        ));
    }
}
