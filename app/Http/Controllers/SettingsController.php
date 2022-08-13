<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            "enable_sms_notifs" => false,
            "primary_sms" => '4075551234',
            "secondary_sms" => '9415554321'
        ];
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
        Settings::updateOrCreate(
            [
                'user_id'   => Auth::user()->id,
                'stg_name'  => 'enable_sms_notifs'
            ],
            [
                'stg_val'   => $request->enable_sms_notifs
            ]
        );

        Settings::updateOrCreate(
            [
                'user_id'   => Auth::user()->id,
                'stg_name'  => 'primary_sms'
            ],
            [
                'stg_val'   => $request->primary_sms
            ]
        );

        Settings::updateOrCreate(
            [
                'user_id'   => Auth::user()->id,
                'stg_name'  => 'secondary_sms'
            ],
            [
                'stg_val'   => $request->secondary_sms
            ]
        );

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
