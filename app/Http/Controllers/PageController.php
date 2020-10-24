<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DirectionsRequest;
use Illuminate\Support\Facades\Http;

class PageController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function submit(DirectionsRequest $request)
    {
        $origin = $request->input('start');
        $destination = $request->input('destination');

        $maxiumumDrivingMinutes = (60 * $request->input('hours')) + $request->input('minutes');

        $params = [
            'key' => config('services.google-maps.key'),
            'origin' => $origin,
            'destination' => $destination,
            'units' => 'imperial',
        ];

        $response = Http::get('https://maps.googleapis.com/maps/api/directions/json?' . http_build_query($params))->json();

        $journeyTimeInMinutes = ($response['routes'][0]['legs'][0]['duration']['value'] / 60);

        if ($journeyTimeInMinutes > $maxiumumDrivingMinutes) {
            $breaksNeeded = ceil($journeyTimeInMinutes / $maxiumumDrivingMinutes);
            $breakEveryMinutes = ceil($journeyTimeInMinutes / $breaksNeeded);
        } else {
            $breaksNeed = 0;
            $breakEveryMinutes = $journeyTimeInMinutes;
        }
        // dd($request->all(), $maxiumumDrivingMinutes, $journeyTimeInMinutes, $breaksNeeded, $breakEveryMinutes);

        return view('map', [
            'start' => $response['routes'][0]['legs']['start_address'],
            'end' => $response['routes'][0]['legs']['end_address'],
            'journeyTime' => $journeyTimeInMinutes,
            'breaksEvery' => $breakEveryMinutes,
            'breaksNeeded' => $breaksNeeded,
        ]);
    }
}
