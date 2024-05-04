<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getDataFirst()
    {
        $jsonUrl = Storage::url('json1.json');
        $jsonData = file_get_contents(public_path($jsonUrl));
        $data = json_decode($jsonData, true);
        return response()->json($data);
    }

    public function getDataSecond()
    {
        $jsonUrl = Storage::url('json2.json');
        $jsonData = file_get_contents(public_path($jsonUrl));
        $data = json_decode($jsonData, true);
        return response()->json($data);
    }
    public function getSampleFirst()
    {
        $jsonUrl = Storage::url('json3.json');
        $jsonData = file_get_contents(public_path($jsonUrl));
        $data = json_decode($jsonData, true);
        return response()->json($data);
    }
    public function getSampleSecond()
    {
        $jsonUrl = Storage::url('json4.json');
        $jsonData = file_get_contents(public_path($jsonUrl));
        $data = json_decode($jsonData, true);
        return response()->json($data);
    }


    public function manipulateJson()
    {
        $jsonUrl1 = Storage::url('json1.json');
        $jsonData1 = file_get_contents(public_path($jsonUrl1));

        $jsonUrl2 = Storage::url('json2.json');
        $jsonData2 = file_get_contents(public_path($jsonUrl2));

        $data1 = json_decode($jsonData1, true);
        $data2 = json_decode($jsonData2, true);

        $mergedData = [];
        foreach ($data1['data'] as $booking) {
            $recordData = [
                'name' => $booking['name'],
                'email' => $booking['email'],
                'booking_number' => $booking['booking']['booking_number'],
                'book_date' => $booking['booking']['book_date'],
            ];

            $ahassFound = false;
            foreach ($data2['data'] as $ahass) {
                if ($booking['booking']['workshop']['code'] === $ahass['code']) {
                    $recordData['ahass_code'] = $ahass['code'];
                    $recordData['ahass_name'] = $ahass['name'];
                    $recordData['ahass_address'] = $ahass['address'];
                    $recordData['ahass_contact'] = $ahass['phone_number'];
                    $recordData['ahass_distance'] = $ahass['distance'];
                    $ahassFound = true;
                    break;
                }
            }

            if (!$ahassFound) {
                $recordData += [
                    'ahass_code' => null,
                    'ahass_name' => '',
                    'ahass_address' => '',
                    'ahass_contact' => '',
                    'ahass_distance' => 0,
                ];
            }

            $recordData['motorcycle_ut_code'] = $booking['booking']['motorcycle']['ut_code'];
            $recordData['motorcycle'] = $booking['booking']['motorcycle']['name'];

            $mergedData[] = $recordData;
        }

        $result = [
            'status' => 1,
            'message' => 'Data Successfully Retrieved.',
            'data' => $mergedData,
        ];

        $jsonResult = json_encode($result);

        return $jsonResult;
    }

    public function sortDataByDistance($jsonResult)
    {
        $result = json_decode($jsonResult, true);
        $data = $result['data'];

        usort($data, function ($a, $b) {
            return $a['ahass_distance'] <=> $b['ahass_distance'];
        });

        $result['data'] = $data;
        $sortedJsonResult = json_encode($result);
        return $sortedJsonResult;
    }

    public function sortedJsonByDistance()
    {
        $jsonResult = $this->manipulateJson();
        $sortedJsonResult = $this->sortDataByDistance($jsonResult);
        return $sortedJsonResult;
    }
}
