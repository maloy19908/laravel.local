<?php

namespace App\Http\Controllers;

use App\Models\shortcode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShortcodesController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(shortcode $shortcode) {
        return view('shortcodes.create', $shortcode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, shortcode $shortcode) {
        $client_id = $request->client_id;
        $request->validate([
            'name' => [
                'required',
                Rule::unique('shortcodes', 'name')->where(function ($query) use($client_id){
                    return $query->where('client_id',$client_id);
                })
            ],
            'mask' => 'required|string',
            'description' => 'required',
            'client_id' => 'required|integer|exists:clients,id',
        ]);
        $shortcode->create($request->all());
        return back()->with('success', 'успех');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\shortcode  $shortcode
     * @return \Illuminate\Http\Response
     */
    public function show(shortcode $shortcode) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\shortcode  $shortcode
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, shortcode $shortcode) {
        return view('shortcodes.edit', compact('shortcode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\shortcode  $shortcode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, shortcode $shortcode) {
        $request->validate([
            'shortcode_name' => 'required|string',
            'name' => 'required|string',
            'mask' => 'required|string',
            'description' => 'required',
        ]);
        $shortcode->update($request->all());
        return back()->with('success', 'успех');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\shortcode  $shortcode
     * @return \Illuminate\Http\Response
     */
    public function destroy(shortcode $shortcode) {
        $shortcode->delete();
        return back()->with('success', 'успех');
    }
}
