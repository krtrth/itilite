<?php

namespace App\Http\Controllers;

use App\libraries\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FlightController extends Controller
{
    public function listFlights (Request $request)
    {
        $flightData = Utility::getFlightData($request->all());

        return view('listing', ['data' => $flightData]);
    }

    public function fileData (Request $request)
    {
        $file = fopen('final.json', 'r');
        $fileData = json_decode(fread($file, filesize('final.json')));

        $flightData = [];
        foreach ( $fileData->data->data->travel->data->result->flight_data->flights->fullDay->from as $flight ) {
            $data['price'] = $flight->sale_price;
            $data['stops'] = count($flight->flights);
            $data['departure'] = $flight->flights[0]->departure_date_time;
            $data['arrival'] = $flight->flights[ $data['stops'] - 1 ]->arrival_date_time;
            $data['name'] = $flight->flights[0]->carrier_name;
            $data['code'] = $flight->flights[0]->carrier_id;
            $data['from'] = $flight->flights[0]->from;
            $data['to'] = $flight->flights[ $data['stops'] - 1 ]->to;
            $timeDifference = strtotime($data['arrival']) - strtotime($data['departure']);

            $data['duration'] = date('h:i', $timeDifference);
            $data['time'] = date('H:i', strtotime($flight->flights[0]->departure_date_time));
            $flightData[] = $data;
        }

        return Response::json(['success' => false, 'response' => $flightData]);
    }

    public function filterData (Request $request)
    {
        $flightData = Utility::getFlightData($request->all());
        $html = '';
        foreach ( $flightData as $flight ) {
            $html .= '<tr>';
            $html .= '<td>' . $flight['name'] . '<br> <small>(' . $flight['code'] . ')</small></td>';
            $html .= '<td>' . $flight['departure'] . '<br><small>' . $flight['from'] . '</small></td>';
            $html .= '<td>' . $flight['duration'] . '<br>(' . $flight['stops'] . 'stop)</td>';
            $html .= '<td>' . $flight['arrival'] . '<br><small>' . $flight['to'] . '</small></td>';
            $html .= '<td>' . $flight['price'] . '<br><a>Flight Details</a></td>';
            $html .= '</tr>';
        }

        return response()->json(['data' => $html]);
    }
}
