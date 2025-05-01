<?php

namespace App\Imports;

use App\Models\Ourpartner;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProjectsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (!(Ourpartner::where('partner', $row)->exists())){
            $partner = new Ourpartner([
                'partner' => $row['partner'],
            ]);
            return $partner;
        }
    }
}
