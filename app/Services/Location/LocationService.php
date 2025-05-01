<?php

namespace App\Services\Location;

use App\City;
use App\Zip_code;
use Illuminate\Support\Facades\Cache;
use App\State;
use Illuminate\Support\Facades\DB;

class LocationService
{
    public function getStates()
    {
        if(Cache::has('AllState')){
           return $states = collect(Cache::get("AllState"))->map(function ($item) {
                return (array) $item;
            })->toArray();
        } else {
            return State::All();
        }
    }

    public function getCities()
    {
        return Cache::rememberForever('AllCities', function () {
            return City::All();
        });
    }

    public function getZipCodeData(int $zip_code_id): array
    {
        $zip = Zip_code::where('zip_code_id', $zip_code_id)->first();

        return [
            'zip_code' => $zip?->zip_code ?? '',
            'street' => $zip?->street_name ?? '',
            'city_id' => $zip?->city_id ?? '',
        ];
    }


    public function getUserLocationData(): array
    {
        $zip_code_id = auth()->user()->zip_code_id;
        $zipData = $this->getZipCodeData($zip_code_id);

        $state_id = null;
        if (!empty($zipData['city_id'])) {
            $city = City::where('city_id', $zipData['city_id'])->first();
            $state_id = $city?->state_id ?? null;
        }

        return [
            'state_id' => $state_id,
            'city_id' => $zipData['city_id'],
            'zip_code' => $zipData['zip_code'],
            'street' => $zipData['street']
        ];
    }


    public function updateAddress(array $data)
    {
        return Zip_code::where('zip_code_id', $data['zip_code_id'])
            ->update([
                'zip_code' => $data['zipcode'],
                'street_name' => $data['streetname'],
                'city_id' => $data['city']
            ]);
    }


    public function storeAddress(array $data)
    {
        //Save ZipCode
        $zip_code = new Zip_code();
        $zip_code->city_id = $data['city'];
        $zip_code->zip_code = $data['zipcode'];
        $zip_code->street_name = $data['streetname'];
        $zip_code->state_id = $data['state'];
        $zip_code->save();
        return DB::getPdo()->lastInsertId();
    }
}
