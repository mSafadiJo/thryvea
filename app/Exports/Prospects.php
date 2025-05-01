<?php
namespace App\Exports;
use App\ProspectUsers;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Prospects implements FromQuery,WithHeadings
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
            'User Owner',
            'Business Name',
            'Phone',
            'Mobile',
            'City',
            'type',
            'CreatedAt',
        ];
    }
    public function query()
    {
        return ProspectUsers::query()->join('zip_codes', 'zip_codes.zip_code_id', '=', 'prospect_users.zip_code_id')
            ->join('cities', 'cities.city_id', '=', 'zip_codes.city_id')
            ->where('prospect_users.user_visibility', '<>', 4)
            ->select([
                'prospect_users.id', 'prospect_users.username', 'prospect_users.email', 'prospect_users.user_owner',
                'prospect_users.user_business_name', 'prospect_users.user_phone_number', 'prospect_users.user_mobile_number',
                'cities.city_name',
                DB::raw('(CASE WHEN prospect_users.user_type = 4 THEN "Aggregator" ELSE "Buyer" END) AS prospect_type'),
                'prospect_users.created_at'
            ]);
        /*you can use condition in query to get required result
         return Bulk::query()->whereRaw('id > 5');*/
    }
    public function map($prospects): array
    {
        return [
            $prospects->id,
            $prospects->username,
            $prospects->email,
            $prospects->user_owner,
            $prospects->user_business_name,
            $prospects->user_phone_number,
            $prospects->user_mobile_number,
            $prospects->city_name,
            $prospects->prospect_type,
            Date::dateTimeToExcel($prospects->created_at),
        ];
    }

}
