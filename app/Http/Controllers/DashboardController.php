<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\customer;
use App\followup_remark;
use App\inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Basic Counts
        $totalInquiries = inquiry::count();
        $totalCustomers = customer::count();
        $totalUsers = User::count();

        // 2. Follow-up Analytics
        $overdueFollowupsCount = followup_remark::whereDate('followup_date', '<', today())->count();
        $todaysFollowupsCount = followup_remark::whereDate('followup_date', today())->count();
        $upcomingFollowupsCount = followup_remark::whereDate('followup_date', '>', today())->count();

        // 3. Inquiry Status Overview (Grouping by status)
        $inquiryStatusCounts = inquiry::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 4. User Performance (Inquiries per Sales Person)
        $userPerformance = User::leftJoin('inquiry', 'users.id', '=', 'inquiry.saleperson')
            ->select('users.id', 'users.name', DB::raw('count(inquiry.id_inquiry) as inquiry_count'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('inquiry_count')
            ->take(8)
            ->get();

        // 5. Prioritized Follow-ups (Overdue + Upcoming)
        // We show latest 15 follow-ups, prioritizing Overdue and Today
        //  $latestFollowups = followup_remark::with('inquiry.customer')
        //     ->orderBy('followup_date', 'ASC')  // ASC to see upcoming/today first? Or DESC for latest added? Usually upcoming.
        //     // If we want "Today's" or "Upcoming" followups, ASC from today is better.
        //     // If we simply want "Latest Created", then created_at DESC.
        //     // The original code had orderBy('followup_date', 'ASC'), I'll keep it.
        //     ->whereDate('followup_date', '>=', today())  // Optional: Should we only show future? Or all? Original didn't filter date.
        //     // Let's stick to original logic but maybe add a filter if requested. The user said "show followups".
        //     // Original: ->orderBy('followup_date', 'ASC')->take(20)->get();
        $latestFollowups = followup_remark::with(['inquiry.customer', 'createdBy'])
            ->orderBy('followup_date', 'desc')
            ->take(20)
            ->get();

        // 6. Inquiry Status by Salesperson
        $rawCounts = inquiry::join('users', 'inquiry.saleperson', '=', 'users.id')
            ->select('users.id as salesperson_id', 'users.name as salesperson_name', 'inquiry.status', DB::raw('count(*) as total'))
            ->groupBy('users.id', 'users.name', 'inquiry.status')
            ->get();

        $salespersonStatusCounts = [];
        $allStatuses = ['Open', 'In-Progress', 'Quotation', 'Confirmed', 'Hold', 'Cancelled'];
        
        foreach ($rawCounts as $row) {
            $sp = $row->salesperson_name;
            if (!isset($salespersonStatusCounts[$sp])) {
                $salespersonStatusCounts[$sp] = [
                    'id' => $row->salesperson_id,
                    'counts' => array_fill_keys($allStatuses, 0)
                ];
            }
            if (array_key_exists($row->status, $salespersonStatusCounts[$sp]['counts'])) {
                $salespersonStatusCounts[$sp]['counts'][$row->status] = $row->total;
            } else {
                // Unknown status
                $salespersonStatusCounts[$sp]['counts'][$row->status] = $row->total;
                if (!in_array($row->status, $allStatuses)) {
                    $allStatuses[] = $row->status;
                }
            }
        }

        return view('dashboard', compact(
            'totalInquiries',
            'totalCustomers',
            'totalUsers',
            'overdueFollowupsCount',
            'todaysFollowupsCount',
            'upcomingFollowupsCount',
            'latestFollowups',
            'inquiryStatusCounts',
            'userPerformance',
            'salespersonStatusCounts',
            'allStatuses'
        ));
    }
}
