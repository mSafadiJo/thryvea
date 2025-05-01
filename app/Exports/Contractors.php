<?php
namespace App\Exports;
use App\JoinAsaPro;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Contractors implements FromQuery,WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // use Exportable;

    public function headings(): array
    {
        return [
            'Id',
            'Name',
            'Email',
            'Phone',
            'Business Name',
            'ZipCodes',
            'city',
            'Source',
            'IP Address',
            'TS',
            'G',
            'C',
            'K',
            'Note',
            'CreatedAt',
        ];
    }
    public function query()
    {
        return JoinAsaPro::query()->select([
            'id', 'full_name', 'email', 'phone_number', 'business_name',
            'zip_code', 'city', 'resource', 'ip_address',
            'google_ts', 'google_g', 'google_c', 'google_k', 'note', 'created_at'
        ]);
        /*you can use condition in query to get required result
         return Bulk::query()->whereRaw('id > 5');*/
    }
    public function map($JoinAsaPro): array
    {
        return [
            $JoinAsaPro->id,
            $JoinAsaPro->full_name,
            $JoinAsaPro->email,
            $JoinAsaPro->phone_number,
            $JoinAsaPro->business_name,
            $JoinAsaPro->zip_code,
            $JoinAsaPro->city,
            $JoinAsaPro->resource,
            $JoinAsaPro->ip_address,
            $JoinAsaPro->google_ts,
            $JoinAsaPro->google_g,
            $JoinAsaPro->google_c,
            $JoinAsaPro->google_k,
            $JoinAsaPro->note,
            Date::dateTimeToExcel($JoinAsaPro->created_at),
        ];
    }

}
