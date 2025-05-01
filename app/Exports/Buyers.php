<?php
namespace App\Exports;
use App\Transaction;
use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Buyers implements FromQuery,WithHeadings
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
            'Current Balance',
            'Total Spent',
            'Total Funded',
            'Last Transaction'
        ];
    }
    public function query()
    {
        return User::query()->join('zip_codes', 'zip_codes.zip_code_id', '=', 'users.zip_code_id')
            ->join('cities', 'cities.city_id', '=', 'zip_codes.city_id')
            ->leftJoin('total_amounts', 'total_amounts.user_id', '=', 'users.id')
            ->where('users.user_visibility', '<>', 4)
            ->whereIn('users.role_id', ['3', '4', '5', '6'])
            ->orderBy('users.created_at', 'desc')
            ->groupBy('users.id')
            ->select([
                'users.id', 'users.username', 'users.email', 'users.user_owner',
                'users.user_business_name', 'users.user_phone_number', 'users.user_mobile_number',
                'cities.city_name',
                DB::raw('(CASE WHEN users.role_id = 4 THEN "Aggregator" WHEN users.role_id = 5 THEN "Seller" WHEN users.role_id = 6 THEN "Enterprise" WHEN users.role_id = 7 THEN "RevShare Seller" ELSE "Buyer" END) AS user_type'),
                'users.created_at', 'total_amounts.total_amounts_value',
                DB::raw("(SELECT SUM(transactions.transactions_value) FROM transactions
                                WHERE transactions.user_id = users.id
                                AND transactions.transaction_status = 1
                                AND transactions.accept = 1
                                AND transactions.transactions_comments IN ('Credit Accumulation', 'Auto Credit Accumulation', 'eCheck', 'PayPal', 'ACH Credit', 'Add Credit')
                                GROUP BY transactions.user_id) AS total_bid"),
                DB::raw("(SELECT SUM(campaigns_leads_users.campaigns_leads_users_bid) FROM campaigns_leads_users
                                WHERE campaigns_leads_users.user_id = users.id
                                AND campaigns_leads_users.is_returned = 0
                                GROUP BY campaigns_leads_users.user_id) AS total_spend"),
                DB::raw("(SELECT campaigns_leads_users2.date FROM campaigns_leads_users AS campaigns_leads_users2
                                WHERE campaigns_leads_users2.user_id = users.id
                                AND campaigns_leads_users2.is_returned = 0
                                ORDER BY campaigns_leads_users2.date DESC LIMIT 1) AS lead_date")
            ]);
        /*you can use condition in query to get required result
         return Bulk::query()->whereRaw('id > 5');*/
    }
    public function map($users): array
    {
        return [
            $users->id,
            $users->username,
            $users->email,
            $users->user_owner,
            $users->user_business_name,
            $users->user_phone_number,
            $users->user_mobile_number,
            $users->city_name,
            $users->user_type,
            Date::dateTimeToExcel($users->created_at),
            $users->total_amounts_value,
            $users->total_bid,
            $users->total_spend,
            Date::dateTimeToExcel($users->lead_date)
        ];
    }

}
