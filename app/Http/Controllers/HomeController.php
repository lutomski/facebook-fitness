<?php

namespace App\Http\Controllers;

use App;
use App\FitnessActivity;
use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isFacebookConnected()) {
            $best_efficiency = App::call('App\Http\Controllers\FitnessController@getBestEfficiency');
            $fitness_activities = FitnessActivity::where('user_id', Auth::user()->id)
                ->orderBy('start_time', 'desc')
                ->get();

            return view('home')
                ->with('best_efficiency', $best_efficiency)
                ->with('fitness_activities', $fitness_activities);
        } else {
            return view('home');
        }
    }
}
