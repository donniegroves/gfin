<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payee;
use Illuminate\Support\Facades\Auth;

class PayeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Payee::where('user_id', Auth::user()->id)
            ->orderBy('name', 'ASC')
            ->get();
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
        $new_payee = new Payee;
        $new_payee->user_id = Auth::user()->id;
        $new_payee->name = $request->payee["name"];
        $new_payee->save();

        return $new_payee;
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
        $existing_payee = Payee::find($id);
        if ($existing_payee->user_id !== Auth::user()->id){
            return 'Permissions problem.';
        }

        if ($existing_payee){
            $existing_payee->name = !empty($request->payee['name']) ? $request->payee['name'] : $existing_payee->name;
            $existing_payee->save();

            return $existing_payee;
        }

        return 'Payee not found.';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existing_payee = Payee::find($id);
        if ($existing_payee->user_id !== Auth::user()->id){
            return 'Permissions problem.';
        }

        if ($existing_payee){
            $existing_payee->delete();
            return 'Payee deleted.';
        }

        return 'Payee not found.';
    }
}
