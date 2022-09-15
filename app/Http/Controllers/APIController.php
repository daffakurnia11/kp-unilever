<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Current;
use App\Models\Sensor;
use App\Models\Temperature;
use App\Models\Vibration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class APIController extends Controller
{
    public function show(Sensor $sensor)
    {
        $data = Sensor::where('plant_name', $sensor->plant_name)->get();

        if (!$data) {
            return ApiFormatter::createApi(400, 'Failed fetching data');
        }
        return ApiFormatter::createApi(200, 'Success fetching data', $data);
    }

    public function set_point(Sensor $sensor)
    {
        $data = Sensor::where('plant_name', $sensor->plant_name)->with('setPoint')->get();

        if (!$data) {
            return ApiFormatter::createApi(400, 'Failed fetching data');
        }
        return ApiFormatter::createApi(200, 'Success fetching data', $data);
    }

    public function get_temperature(Sensor $sensor, Request $request)
    {
        if ($sensor->plant_type == 'Motor') {
            if (isset($request->filter) && isset($request->unit)) {
                if ($request->unit == 'minutes') {
                    $data = Sensor::where('plant_name', $sensor->plant_name)->where('sensor', 'mlx')->with('temperature', function ($data) {
                        global $request;
                        $data->where('created_at', '<=', Carbon::now()->subMinutes($request->filter))->get();
                    })->get();
                } else {
                    $data = Sensor::where('plant_name', $sensor->plant_name)->where('sensor', 'mlx')->with('temperature', function ($data) {
                        global $request;
                        $data->where('created_at', '<=', Carbon::now()->subHours($request->filter))->get();
                    })->get();
                }
            } else {
                $data = Sensor::where('plant_name', $sensor->plant_name)->where('sensor', 'mlx')->with('temperature')->get();
            }
        } elseif ($sensor->plant_type == 'Panel') {
            if (isset($request->filter) && isset($request->unit)) {
                if ($request->unit == 'minutes') {
                    $data = Sensor::where('plant_name', $sensor->plant_name)->with('temperature', function ($data) {
                        global $request;
                        $data->where('created_at', '<=', Carbon::now()->subMinutes($request->filter))->get();
                    })->get();
                } else {
                    $data = Sensor::where('plant_name', $sensor->plant_name)->with('temperature', function ($data) {
                        global $request;
                        $data->where('created_at', '<=', Carbon::now()->subHours($request->filter))->get();
                    })->get();
                }
            } else {
                $data = Sensor::where('plant_name', $sensor->plant_name)->with('temperature')->get();
            }
        } else {
            return ApiFormatter::createApi(400, 'Failed fetching data');
        }
        return ApiFormatter::createApi(200, 'Success fetching data', $data);
    }

    public function get_vibration(Sensor $sensor, Request $request)
    {
        if ($sensor->plant_type == 'Motor') {
            if (isset($request->filter) && isset($request->unit)) {
                if ($request->unit == 'minutes') {
                    $data = Sensor::where('plant_name', $sensor->plant_name)->where('sensor', 'adxl')->with('vibration', function ($data) {
                        global $request;
                        $data->where('created_at', '<=', Carbon::now()->subMinutes($request->filter))->get();
                    })->get();
                } else {
                    $data = Sensor::where('plant_name', $sensor->plant_name)->where('sensor', 'adxl')->with('vibration', function ($data) {
                        global $request;
                        $data->where('created_at', '<=', Carbon::now()->subHours($request->filter))->get();
                    })->get();
                }
            } else {
                $data = Sensor::where('plant_name', $sensor->plant_name)->where('sensor', 'adxl')->with('vibration')->get();
            }
        } else {
            return ApiFormatter::createApi(400, 'Failed fetching data');
        }
        return ApiFormatter::createApi(200, 'Success fetching data', $data);
    }

    public function get_current(Sensor $sensor, Request $request)
    {
        if ($sensor->plant_type == 'Motor') {
            if (isset($request->filter) && isset($request->unit)) {
                if ($request->unit == 'minutes') {
                    $data = Sensor::where('plant_name', $sensor->plant_name)->where('sensor', 'pzem')->with('current', function ($data) {
                        global $request;
                        $data->where('created_at', '<=', Carbon::now()->subMinutes($request->filter))->get();
                    })->get();
                } else {
                    $data = Sensor::where('plant_name', $sensor->plant_name)->where('sensor', 'pzem')->with('current', function ($data) {
                        global $request;
                        $data->where('created_at', '<=', Carbon::now()->subHours($request->filter))->get();
                    })->get();
                }
            } else {
                $data = Sensor::where('plant_name', $sensor->plant_name)->where('sensor', 'pzem')->with('current')->get();
            }
        } else {
            return ApiFormatter::createApi(400, 'Failed fetching data');
        }
        return ApiFormatter::createApi(200, 'Success fetching data', $data);
    }

    public function post_temperature(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sensor_id'     => 'required',
            'temperature'   => 'required',
            'pressure'      => 'nullable',
            'ambient'       => 'nullable'
        ]);

        if ($validator->fails()) {
            return ApiFormatter::createApi(400, 'Bad Request', $validator->errors());
        }
        $validated = $validator->validated();
        Temperature::create($validated);
        return ApiFormatter::createApi(201, 'Data stored', $validated);
    }

    public function post_vibration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sensor_id' => 'required',
            'x_axis'    => 'required',
            'y_axis'    => 'required',
            'z_axis'    => 'required',
        ]);

        if ($validator->fails()) {
            return ApiFormatter::createApi(400, 'Bad Request', $validator->errors());
        }
        $validated = $validator->validated();
        Vibration::create($validated);
        return ApiFormatter::createApi(201, 'Data stored', $validated);
    }

    public function post_current(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sensor_id' => 'required',
            'volt'      => 'required',
            'ampere'    => 'required',
            'power'     => 'required'
        ]);

        if ($validator->fails()) {
            return ApiFormatter::createApi(400, 'Bad Request', $validator->errors());
        }
        $validated = $validator->validated();
        Current::create($validated);
        return ApiFormatter::createApi(201, 'Data stored', $validated);
    }
}
