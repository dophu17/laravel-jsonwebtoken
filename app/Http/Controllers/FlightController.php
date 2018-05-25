<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/22/2018
 * Time: 10:42 AM
 */

namespace App\Http\Controllers;

use App\Flight;
use JWTAuth;

class FlightController extends Controller
{
    function index() {
        echo 'flight index <br>';
        $flights = Flight::all();
        foreach ($flights as $flight) {
            echo $flight->id . ' --- ' . $flight->name . '<br>';
        }
        echo '<pre>';print_r($flights);
    }

    function indexAPI() {
        $flights = Flight::all();
        return response()->json($flights, 200);
    }
}