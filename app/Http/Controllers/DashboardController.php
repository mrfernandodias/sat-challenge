<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'stats' => $this->getStats(),
            'customersByState' => $this->getCustomersByState(),
            'latestCustomers' => $this->getLatestCustomers(),
            'customersByMonth' => $this->getCustomersByMonth(),
        ]);
    }

    private function getStats(): array
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return [
            'total' => Customer::count(),
            'thisMonth' => Customer::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
            'today' => Customer::whereDate('created_at', $today)->count(),
            'states' => Customer::distinct('state')->count('state'),
        ];
    }

    private function getCustomersByState(): array
    {
        return Customer::selectRaw('state, COUNT(*) as total')
            ->groupBy('state')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'state')
            ->toArray();
    }

    private function getLatestCustomers(): Collection
    {
        return Customer::latest()->limit(5)->get();
    }

    private function getCustomersByMonth(): array
    {
        $monthlyData = collect();

        for ($monthsAgo = 5; $monthsAgo >= 0; $monthsAgo--) {
            $date = Carbon::now()->subMonths($monthsAgo);

            $customersCount = Customer::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $monthlyData->push([
                'month' => $date->translatedFormat('M/Y'),
                'count' => $customersCount,
            ]);
        }

        return $monthlyData->toArray();
    }
}
