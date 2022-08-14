<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryPattern;
use Illuminate\Support\Facades\Auth;

class CategoryPatternController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryPattern::where('user_id', Auth::user()->id)
            ->orderBy('updated_at', 'ASC')
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
        $pattern = new CategoryPattern;
        $pattern->user_id = Auth::user()->id;
        $pattern->pattern = $request->pattern["pattern"];
        $pattern->category_id = $request->pattern["category_id"];
        $pattern->save();

        return $pattern;
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
        $existing_pattern = CategoryPattern::find($id);
        if ($existing_pattern->user_id !== Auth::user()->id){
            return 'Permissions problem.';
        }

        if ($existing_pattern){
            $existing_pattern->pattern = !empty($request->pattern['pattern']) ? $request->pattern['pattern'] : $existing_pattern->pattern;
            $existing_pattern->save();

            return $existing_pattern;
        }

        return 'Pattern not found.';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existing_pattern = CategoryPattern::find($id);
        if ($existing_pattern->user_id !== Auth::user()->id){
            return 'Permissions problem.';
        }

        if ($existing_pattern){
            $existing_pattern->delete();
            return 'Pattern deleted.';
        }

        return 'Pattern not found.';
    }
}
