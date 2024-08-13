<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityLogController extends Controller{
    
    public function __construct(){
        $this->middleware('permission:view_activities')->only('index');
    }

    public function index(Request $request){

        $query = ActivityLog::query();
        $activities = null;
        $day = $request->input('date');
        $type = $request->input('type');

        if ($day) {
            $dayStart = Carbon::parse($day)->startOfDay();
            $dayEnd = Carbon::parse($day)->endOfDay();
            $query->whereBetween('created_at', [$dayStart, $dayEnd]);
        } 
        if ($type) {
            $query->where('type', $type);
        }
        if($day || $type){
            $activities = $query->orderBy('created_at', 'desc')->get()->take(200);
        }

        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $yesterdayStart = Carbon::yesterday()->startOfDay();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();

        $todayActivities = ActivityLog::whereBetween('created_at', [$todayStart, $todayEnd])->orderBy('created_at', 'desc')->get();
        $yesterdayActivities = ActivityLog::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->orderBy('created_at', 'desc')->get();
        
        $activities_colors = [
            'الوردية' => 'danger',
            'العملاء' => 'warning',
            'الموردين' => 'dark',
            'الموظفين' => 'info',
            'الفواتير' => 'success',
            'الاعدادات' => 'secondary',
            'المنتجات' => 'primary',
            'المصادقة' => 'purple',
        ];
        return view('pages.activities', compact('todayActivities', 'yesterdayActivities', 'activities_colors', 'activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivityLog $activityLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityLog $activityLog)
    {
        //
    }
}