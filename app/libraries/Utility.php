<?php

namespace App\libraries;

class Utility
{
    public static function filter ($request, $flight)
    {
        if ( !isset($request['stops']) ) return true;

        if ( $request['stops'] < count($flight->flights) ) return false;

        $duration = explode(',', $request['duration']);
        $timeDifference = strtotime($flight->flights[ count($flight->flights) - 1 ]->arrival_date_time) -
            strtotime($flight->flights[0]->departure_date_time);

        $flightDuration = explode(':', date('H:i', $timeDifference));

        if ( $duration[0] > $flightDuration[0] || $duration[1] <= $flightDuration[0] ) return false;

        $departure = explode(',', $request['departure']);

        $flightTime = explode(':', date('H:i', strtotime($flight->flights[0]->departure_date_time)));

        if ( $departure[0] > $flightTime[0] || $departure[1] <= $flightTime[0] ) return false;

        return true;
    }

    public static function getFlightData ($request)
    {
        $file = fopen('final.json', 'r');
        $fileData = json_decode(fread($file, filesize('final.json')));

        $flightData = [];
        foreach ( $fileData->data->data->travel->data->result->flight_data->flights->fullDay->from as $flight ) {
            if ( !Utility::filter($request, $flight) )
                continue;

//            return Utility::filter($request, $flight);
            $data['price'] = $flight->sale_price;
            $data['stops'] = count($flight->flights)-1;
            $data['departure'] = $flight->flights[0]->departure_date_time;
            $data['arrival'] = $flight->flights[ $data['stops']]->arrival_date_time;
            $data['name'] = $flight->flights[0]->carrier_name;
            $data['code'] = $flight->flights[0]->carrier_id;
            $data['from'] = $flight->flights[0]->from;
            $data['to'] = $flight->flights[ $data['stops']]->to;
            $timeDifference = strtotime($data['arrival']) - strtotime($data['departure']);

            $data['duration'] = date('h:i', $timeDifference);
            $flightData[] = $data;
        }

        return $flightData;
    }
}
