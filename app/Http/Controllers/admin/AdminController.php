<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Customization;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SiteVisit;
use App\Models\Skill;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $dailyLabels = collect(range(23, 0))->map(fn($hoursAgo) => now()->subHours($hoursAgo)->format('H\h'));
        $dailySite = collect(range(23, 0))->map(function ($hoursAgo) {
            $date = now()->subHours($hoursAgo);

            return SiteVisit::where('type', 'site')
                ->whereDate('created_at', $date->toDateString())
                ->whereTime('created_at', '>=', $date->copy()->startOfHour()->toTimeString())
                ->whereTime('created_at', '<=', $date->copy()->endOfHour()->toTimeString())
                ->count();
        });
        $dailyLinks = collect(range(23, 0))->map(function ($hoursAgo) {
            $date = now()->subHours($hoursAgo);

            return SiteVisit::where('type', 'links')
                ->whereDate('created_at', $date->toDateString())
                ->whereTime('created_at', '>=', $date->copy()->startOfHour()->toTimeString())
                ->whereTime('created_at', '<=', $date->copy()->endOfHour()->toTimeString())
                ->count();
        });

        $weeklyLabels = collect(range(6, 0))->map(fn($daysAgo) => now()->subDays($daysAgo)->locale('pt_BR')->isoFormat('ddd'));
        $weeklySite = $this->visitSeriesByDay('site', 6);
        $weeklyLinks = $this->visitSeriesByDay('links', 6);

        $monthlyLabels = collect(range(11, 0))->map(fn($monthsAgo) => now()->subMonths($monthsAgo)->format('M'));
        $monthlySite = $this->visitSeriesByMonth('site', 11);
        $monthlyLinks = $this->visitSeriesByMonth('links', 11);

        return view('admin.dashboard', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'dashboardData' => [
                'kpis' => [
                    'siteVisits' => SiteVisit::where('type', 'site')->count(),
                    'linkVisits' => SiteVisit::where('type', 'links')->count(),
                    'projects' => Project::count(),
                    'messages' => ContactMessage::count(),
                    'skills' => Skill::count(),
                    'experiences' => Experience::count(),
                ],
                'visitsDaily' => [
                    'labels' => $dailyLabels,
                    'site' => $dailySite,
                    'links' => $dailyLinks,
                ],
                'visitsWeekly' => [
                    'labels' => $weeklyLabels,
                    'site' => $weeklySite,
                    'links' => $weeklyLinks,
                ],
                'visitsMonthly' => [
                    'labels' => $monthlyLabels,
                    'site' => $monthlySite,
                    'links' => $monthlyLinks,
                ],
            ],
        ]);
    }

    public function users()
    {
        $users = User::get();

        return view('admin.users', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'users' => $users
        ]);
    }

    protected function search($config, $else = null)
    {
        $customizations = Customization::where('config', $config);
        $customization = $customizations->first();

        if ($customizations->count() > 0) {
            return $customization->encode ? base64_decode($customization->value) : $customization->value;
        }
        return $else;
    }

    private function visitSeriesByDay(string $type, int $daysBack)
    {
        return collect(range($daysBack, 0))->map(function ($daysAgo) use ($type) {
            $date = Carbon::today()->subDays($daysAgo);

            return SiteVisit::where('type', $type)->whereDate('created_at', $date)->count();
        });
    }

    private function visitSeriesByMonth(string $type, int $monthsBack)
    {
        return collect(range($monthsBack, 0))->map(function ($monthsAgo) use ($type) {
            $date = now()->subMonths($monthsAgo);

            return SiteVisit::where('type', $type)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        });
    }
}
