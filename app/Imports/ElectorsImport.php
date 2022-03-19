<?php

namespace App\Imports;

use App\Models\Electors;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ElectorsImport implements ToModel, WithHeadingRow, WithStartRow
{
   
    public function model(array $row)
    {

        return new Electors([
            'firstname' => $row['firstname'] ? $row['firstname'] : '',
            'lastname' => $row['lastname'] ? $row['lastname'] : '',
            'fathername' => $row['fathername'] ? $row['fathername'] : '',
            'mothername' => $row['mothername'] ? $row['mothername'] : '',
            'date_of_birth' => $row['date_of_birth'] ? $row['date_of_birth'] : '',
            'sex' => $row['sex'] ? $row['sex'] : '',
            'doctrine' => $row['doctrine'] ? $row['doctrine'] : '',
            'log' => $row['log'] ? $row['log'] : '',
            'log_doctrine' => $row['log_doctrine'] ? $row['log_doctrine'] : '',
            'district' => $row['district'] ? $row['district'] : '',
            'zone' => $row['zone'] ? $row['zone'] : '',
            'state' => $row['state'] ? $row['state'] : '',
            'election_zone' => $row['election_zone'] ? $row['election_zone'] : '',
            'election_country' => $row['election_country'] ? $row['election_country'] : '',
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
