<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrarModel;
use App\Models\PickUpRequest;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Models\PaymentMethodTransaction;
use App\Models\PaymentsModel;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class DashboardController extends Controller
{
    // Admin Dashboard
    public function adminDashboard()
    {

     $total_documents = RegistrarModel::count() ?: 0;
    // Total requests
    $total_requests = PickUpRequest::count() ?: 0;

    // Verified Requests
    $registrar_verified_requests = PickUpRequest::join('payments', 'pickup_requests.id', '=', 'payments.pickup_request_id')
                                      ->where('payments.is_verified', 1)
                                      ->count() ?: 0;

        // Total requests
    $total_requests = PickUpRequest::count() ?: 0;

    // Not Verified Requests
    $not_verified_requests = PickUpRequest::join('payments', 'pickup_requests.id', '=', 'payments.pickup_request_id')
                                          ->where('payments.is_verified', 0)
                                          ->count() ?: 0;

    // Verified Requests
    $verified_requests = PickUpRequest::join('payments', 'pickup_requests.id', '=', 'payments.pickup_request_id')
                                      ->where('payments.is_verified', 1)
                                      ->count() ?: 0;

    // Weekly Revenue (only verified payments)
    $weekly_revenue = PaymentsModel::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                                   ->where('is_verified', 1) 
                                   ->sum('amount') ?: 0.00;

    // Monthly Revenue (only verified payments)
    $monthly_revenue = PaymentsModel::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->where('is_verified', 1)
                                    ->sum('amount') ?: 0.00;

    // Annual Revenue (only verified payments)
    $annual_revenue = PaymentsModel::whereYear('created_at', now()->year)
                                   ->where('is_verified', 1)
                                   ->sum('amount') ?: 0.00;

    // Fetch weekly data for the chart (sum of amounts per day)
    $weekly_data = [];
    foreach (range(0, 6) as $dayOffset) {
        $startOfDay = now()->startOfWeek()->addDays($dayOffset)->startOfDay();
        $endOfDay = now()->startOfWeek()->addDays($dayOffset)->endOfDay();
        
        $daily_sum = PaymentsModel::whereBetween('created_at', [$startOfDay, $endOfDay])
                                  ->where('is_verified', 1)
                                  ->sum('amount') ?: 0.00;

        $weekly_data[] = $daily_sum;
    }

    // Fetch monthly data for the chart (sum of amounts per week)
    $monthly_data = [];
    for ($week = 1; $week <= 4; $week++) {
        $startOfWeek = now()->month(now()->month)->week($week)->startOfWeek();
        $endOfWeek = now()->month(now()->month)->week($week)->endOfWeek();
        
        $weekly_sum = PaymentsModel::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                                   ->where('is_verified', 1)
                                   ->sum('amount') ?: 0.00;

        $monthly_data[] = $weekly_sum;
    }

    // Fetch annual data for the chart (sum of amounts per month)
    $annual_data = [];
    for ($month = 1; $month <= 12; $month++) {
        $startOfMonth = now()->year(now()->year)->month($month)->startOfMonth();
        $endOfMonth = now()->year(now()->year)->month($month)->endOfMonth();

        $monthly_sum = PaymentsModel::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                    ->where('is_verified', 1)
                                    ->sum('amount') ?: 0.00;

        $annual_data[] = $monthly_sum;
    }

    // Weekly chart (create data points for the week)
    $weekly_chart = new Chart;
    $weekly_chart->labels(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
                 ->dataset('Weekly Revenue', 'line', $weekly_data) // Use dynamic weekly data
                 ->color('rgba(54, 162, 235, 0.2)')
                 ->backgroundColor('rgba(54, 162, 235, 0.2)');

    // Monthly chart (create data points for the month)
    $monthly_chart = new Chart;
    $monthly_chart->labels(['Week 1', 'Week 2', 'Week 3', 'Week 4'])
                 ->dataset('Monthly Revenue', 'line', $monthly_data) // Use dynamic monthly data
                 ->color('rgba(255, 99, 132, 0.2)')
                 ->backgroundColor('rgba(255, 99, 132, 0.2)');

    // Annual chart (create data points for the year)
    $annual_chart = new Chart;
    $annual_chart->labels(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'])
                ->dataset('Annual Revenue', 'line', $annual_data) // Use dynamic annual data
                ->color('rgba(75, 192, 192, 0.2)')
                ->backgroundColor('rgba(75, 192, 192, 0.2)');

    return view('admin.dashboard', compact(
        'total_requests',
        'registrar_verified_requests',
        'total_documents',
        'not_verified_requests',
        'verified_requests',
        'weekly_revenue',
        'monthly_revenue',
        'annual_revenue',
        'weekly_chart',
        'monthly_chart',
        'annual_chart'
    ));
    }




// Registrar Dashboard
public function registrarDashboard()
{
    $total_documents = RegistrarModel::count() ?: 0;
    // Total requests
    $total_requests = PickUpRequest::count() ?: 0;

    // Verified Requests
    $registrar_verified_requests = PickUpRequest::join('payments', 'pickup_requests.id', '=', 'payments.pickup_request_id')
                                      ->where('payments.is_verified', 1)
                                      ->count() ?: 0;

    // Log the data
    \Log::info("Registrar - Total Documents: " . $total_documents);
    \Log::info("Registrar - Total Pickup Requests: " . $total_requests);

    return view('registrar.dashboard', compact('total_documents', 'total_requests', 'registrar_verified_requests'));
}








    // Accounting Dashboard
    public function accountingDashboard()
{
    // Total requests
    $total_requests = PickUpRequest::count() ?: 0;

    // Not Verified Requests
    $not_verified_requests = PickUpRequest::join('payments', 'pickup_requests.id', '=', 'payments.pickup_request_id')
                                          ->where('payments.is_verified', 0)
                                          ->count() ?: 0;

    // Verified Requests
    $verified_requests = PickUpRequest::join('payments', 'pickup_requests.id', '=', 'payments.pickup_request_id')
                                      ->where('payments.is_verified', 1)
                                      ->count() ?: 0;

    // Weekly Revenue (only verified payments)
    $weekly_revenue = PaymentsModel::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                                   ->where('is_verified', 1) 
                                   ->sum('amount') ?: 0.00;

    // Monthly Revenue (only verified payments)
    $monthly_revenue = PaymentsModel::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->where('is_verified', 1)
                                    ->sum('amount') ?: 0.00;

    // Annual Revenue (only verified payments)
    $annual_revenue = PaymentsModel::whereYear('created_at', now()->year)
                                   ->where('is_verified', 1)
                                   ->sum('amount') ?: 0.00;

    // Fetch weekly data for the chart (sum of amounts per day)
    $weekly_data = [];
    foreach (range(0, 6) as $dayOffset) {
        $startOfDay = now()->startOfWeek()->addDays($dayOffset)->startOfDay();
        $endOfDay = now()->startOfWeek()->addDays($dayOffset)->endOfDay();
        
        $daily_sum = PaymentsModel::whereBetween('created_at', [$startOfDay, $endOfDay])
                                  ->where('is_verified', 1)
                                  ->sum('amount') ?: 0.00;

        $weekly_data[] = $daily_sum;
    }

    // Fetch monthly data for the chart (sum of amounts per week)
    $monthly_data = [];
    for ($week = 1; $week <= 4; $week++) {
        $startOfWeek = now()->month(now()->month)->week($week)->startOfWeek();
        $endOfWeek = now()->month(now()->month)->week($week)->endOfWeek();
        
        $weekly_sum = PaymentsModel::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                                   ->where('is_verified', 1)
                                   ->sum('amount') ?: 0.00;

        $monthly_data[] = $weekly_sum;
    }

    // Fetch annual data for the chart (sum of amounts per month)
    $annual_data = [];
    for ($month = 1; $month <= 12; $month++) {
        $startOfMonth = now()->year(now()->year)->month($month)->startOfMonth();
        $endOfMonth = now()->year(now()->year)->month($month)->endOfMonth();

        $monthly_sum = PaymentsModel::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                    ->where('is_verified', 1)
                                    ->sum('amount') ?: 0.00;

        $annual_data[] = $monthly_sum;
    }

    // Weekly chart (create data points for the week)
    $weekly_chart = new Chart;
    $weekly_chart->labels(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
                 ->dataset('Weekly Revenue', 'line', $weekly_data) // Use dynamic weekly data
                 ->color('rgba(54, 162, 235, 0.2)')
                 ->backgroundColor('rgba(54, 162, 235, 0.2)');

    // Monthly chart (create data points for the month)
    $monthly_chart = new Chart;
    $monthly_chart->labels(['Week 1', 'Week 2', 'Week 3', 'Week 4'])
                 ->dataset('Monthly Revenue', 'line', $monthly_data) // Use dynamic monthly data
                 ->color('rgba(255, 99, 132, 0.2)')
                 ->backgroundColor('rgba(255, 99, 132, 0.2)');

    // Annual chart (create data points for the year)
    $annual_chart = new Chart;
    $annual_chart->labels(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'])
                ->dataset('Annual Revenue', 'line', $annual_data) // Use dynamic annual data
                ->color('rgba(75, 192, 192, 0.2)')
                ->backgroundColor('rgba(75, 192, 192, 0.2)');

    return view('accounting.dashboard', compact(
        'total_requests',
        'not_verified_requests',
        'verified_requests',
        'weekly_revenue',
        'monthly_revenue',
        'annual_revenue',
        'weekly_chart',
        'monthly_chart',
        'annual_chart'
    ));
}

}
