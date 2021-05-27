<?php

namespace App\Http\Controllers;

use App\Models\Zipcode;
use Illuminate\Http\Request;

class ZipcodeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->zip_code) {
            $dataModel = Zipcode::where('zip', $request->zip_code)->first();
            if ($dataModel) {
                $data = $dataModel->data;
            } else {
                $data['error'] = "not found " . $request->zip_code;
            }

        } elseif ($request->search_city) {

            $cityName = $request->search_city;
            $testLen = 2;

            if (strlen($cityName) >= $testLen) {
                $dataModels = Zipcode::where('city', 'like', "%$cityName%")
                    ->select('data')
                    ->get()
                    ->toArray();

                if($dataModels) {
                    foreach ($dataModels as $k => $item) {
                        $data[] = json_decode($item['data'], true);
                    }
//                    $data = json_encode($data);
                } else {
                    $data['error'] = 'error get data to ' . $cityName;
                }

            } else {
                $data['error'] = "min size $testLen letter";
            }

        }  else {
            $data['error'] = 'error get data';
        }
        return $data;
    }

    public function store(Request $request)
    {

        $filename =$request->file;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {

            while (($row = fgetcsv($handle)) !== FALSE)
            {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        // обработка частями
        $sizeAll = sizeof($data);
        $info = "file size $sizeAll object \n";

        $start = 0;
        $len = 10000;
        $allRes = 0;
        $it = 0;
        while ($sizeAll > $allRes) {

            $dataItem = array_slice($data, $start, $len);
            $sizeData = sizeof($dataItem);

            foreach ($dataItem as $k => $item) {
                $dataZip[$k]['zip'] = $item['zip'];
                $dataZip[$k]['city'] = $item['city'];
                $dataZip[$k]['data'] = json_encode($item);
            }

            Zipcode::upsert($dataZip, ['zip'], ['city', 'data']);
            $allRes += $sizeData;
            $it++;
            $start = $start + $len;
        }


        $info .= "processed $allRes <br>";
        return $info;
    }

}
