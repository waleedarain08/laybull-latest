<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category;
use App\slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = slider::latest()->get();
        return view('admin.slider.slider',get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = \App\Category::all();
        return view('admin.slider.create_slider',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slider = new slider();
        $slider->title = $request->title ;
        $slider->description = $request->description ;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $sliderImg = Str::random(20). '.' . $file->getClientOriginalExtension();
            Storage::disk('public_slider')->put($sliderImg, \File::get($file));
            $imgeurl = url('media/slider/'.$sliderImg);
        }
        $slider->img_url = $imgeurl;
        $slider->category_to_redirect = $request->subject;
        $slider->save();
        return redirect()->route('slider.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, slider $slider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(slider $slider)
    {
        $slider->delete();
        return redirect()->route('slider.index');

    }
    public function apiSlider(){
        $sliders = slider::latest()->get();
        return $this->formatResponse('success','slider get',$sliders);
    }
}
