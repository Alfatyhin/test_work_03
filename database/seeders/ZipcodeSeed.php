<?php

namespace Database\Seeders;

use App\Models\Zipcode;
use Illuminate\Database\Seeder;

class ZipcodeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dir = __DIR__;
        $filename = "$dir/uszips.csv";

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

        $start = 0;
        $len = 10000;
        $allRes = 0;
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
            $start = $start + $len;
        }

    }
}
