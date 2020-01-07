<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Models\CalendarSchool;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class CalendarSchoolController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'schools_id.exists'    => 'Debe de seleccionar la menos una escuela.',
            'date.after_or_equal'    => 'La fecha tiene que ser igual o mayor a '.date('d/m/Y').'.',
        ];

        $rules = [
            'title' => 'required|string|max:50',
            'date' => 'required|date|after_or_equal:'.date('Y-m-d'),
            'schools_id' => 'required|integer|exists:schools,id',
        ];
        
        $this->validate($request, $rules, $messages);

        if(!is_null(CalendarSchool::where('date',date('Y-m-d',strtotime($request->date)))->where('schools_id',$request->schools_id)->first()))
            return $this->errorResponse('Ya ingreso un registro con estos datos.', 422);

        $calendar_school = new CalendarSchool();
        $calendar_school->title = $request->title;
        $calendar_school->date = date('Y-m-d',strtotime($request->date));
        $calendar_school->schools_id = $request->schools_id;
        $calendar_school->people_id = Auth::user()->people_id;  
        $calendar_school->save();

        return $this->showOne($calendar_school,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CalendarSchool  $calendarSchool
     * @return \Illuminate\Http\Response
     */
    public function show($calendar_school)
    {
        $calendars_school = CalendarSchool::with('person')->where('schools_id',$calendar_school)->whereYear('date',date('Y'))->orderBy('date','desc')->get();
        return $this->showAll($calendars_school,201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CalendarSchool  $calendarSchool
     * @return \Illuminate\Http\Response
     */
    public function edit(CalendarSchool $calendarSchool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CalendarSchool  $calendarSchool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CalendarSchool $calendarSchool)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CalendarSchool  $calendarSchool
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalendarSchool $calendar_school)
    {
        $calendar_school->delete();
        return $this->showOne($calendar_school,201);
    }
}
