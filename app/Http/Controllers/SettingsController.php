<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    const PERMITTED_SETTINGS_ARR = [
        'primary_sms',
        'secondary_sms',
        'send_daily_sms',
        'include_deps_in_notifs',
        'daily_exp_budget'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stg_rows = Settings::where('user_id', Auth::user()->id)->get()->toArray();

        $return_arr = [];
        foreach($stg_rows as $row){
            $return_arr[$row['stg_name']] = $row['stg_val'];
        }
        return $return_arr;
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
        foreach (self::PERMITTED_SETTINGS_ARR AS $one_stg){
            if (!is_null($request->$one_stg)){
                Settings::updateOrCreate(
                    [
                        'user_id'   => Auth::user()->id,
                        'stg_name'  => $one_stg
                    ],
                    [
                        'stg_val'   => $request->$one_stg
                    ]
                );
            }
        }

        return 'Saved settings.';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
