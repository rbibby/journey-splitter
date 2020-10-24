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

    public function map(DirectionsRequest $request)
    {
        $origin = $request->input('start');
        $destination = $request->input('destination');

        $maximumDrivingMinutes = (60 * $request->input('hours')) + $request->input('minutes');

        $params = [
            'key' => config('services.google-maps.key'),
            'origin' => $origin,
            'destination' => $destination,
            'units' => 'imperial',
        ];

        $response = Http::get('https://maps.googleapis.com/maps/api/directions/json?' . http_build_query($params))->json();

        if ($response['geocoded_waypoints'][0]['geocoder_status'] == 'ZERO_RESULTS' || $response['geocoded_waypoints'][1]['geocoder_status'] == 'ZERO_RESULTS') {
            $errors = [];

            if ($response['geocoded_waypoints'][0]['geocoder_status'] == 'ZERO_RESULTS') {
                $errors[] = 'start';
            }

            if ($response['geocoded_waypoints'][1]['geocoder_status'] == 'ZERO_RESULTS') {
                $errors[] = 'end';
            }

            return redirect('/?errors=' . implode(',', $errors))->withInput();
        }

        if ($response['status'] == 'ZERO_RESULTS') {
            return redirect('/?no_results')->withInput();
        }

        $journeyTimeInMinutes = ($response['routes'][0]['legs'][0]['duration']['value'] / 60);

        if ($journeyTimeInMinutes > $maximumDrivingMinutes) {
            $legsNeeded = ceil($journeyTimeInMinutes / $maximumDrivingMinutes);
            $breakEveryMinutes = ceil($journeyTimeInMinutes / $legsNeeded);

            $breaksNeeded = $legsNeeded - 1;
        } else {
            $breaksNeeded = 0;
            $breakEveryMinutes = $journeyTimeInMinutes;
        }

        $startAddress = $response['routes'][0]['legs'][0]['start_address'];
        $endAddress = $response['routes'][0]['legs'][0]['end_address'];

        $embedParams = $params;
        $embedParams['origin'] = $startAddress;
        $embedParams['destination'] = $endAddress;
        $embedUrl = 'https://www.google.com/maps/embed/v1/directions?' . http_build_query($embedParams);

        $steps = $response['routes'][0]['legs'][0]['steps'];

        $breaks = [];
        $time = 0;
        for ($i = 0; $i < $breaksNeeded; $i++) {
            $time += $breakEveryMinutes;
            $break = [
                'time' => $time,
            ];

            $timeElapsed = 0;
            foreach ($steps as $key => $step) {
                $timeElapsed += ($step['duration']['value'] / 60);
                if ($timeElapsed > $time) {
                    $previousStepTime = $timeElapsed - ($steps[$key]['duration']['value'] / 60);

                    $break['location'] = $step['end_location'];
                    break;
                }
            }

            $breaks[] = $break;
        }

        // go back over each break and assign it a town
        foreach ($breaks as &$break) {
            $params = [
                'key' => config('services.google-maps.key'),
                'latlng' => $break['location']['lat'] . ',' . $break['location']['lng'],
                'result_type' => 'locality',
            ];
            $reverseGeocode = Http::get('https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query($params))->json();

            $plusCode = $reverseGeocode['plus_code']['compound_code'];
            list($plusCode, $locality) = explode(' ', $plusCode);
            
            $break['plus_code'] = $plusCode;

            if (count($reverseGeocode['results']) == 0) {
                $break['town'] = explode(',', $locality)[0];
            } else {
                $break['town'] = $reverseGeocode['results'][0]['address_components'][0]['long_name'];
            }
        }

        return view('map', [
            'start' => $startAddress,
            'end' => $endAddress,
            'journeyTime' => $journeyTimeInMinutes,
            'breaksEvery' => $breakEveryMinutes,
            'breaksNeeded' => $breaksNeeded,
            'breaksEveryHour' => $request->input('hours'),
            'breaksEveryMinute' => $request->input('minutes'),
            'embedUrl' => $embedUrl,
            'breaks' => $breaks,
        ]);
    }

    public function place($place, $lat, $long)
    {
        return $place;
    }
}
