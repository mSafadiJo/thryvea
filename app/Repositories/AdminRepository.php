<?php
namespace App\Repositories;

use App\User;
use App\Interfaces\AdminRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AdminRepository implements AdminRepositoryInterface
{
    public function all()
    {
        return  User::with([
            'zipCode.city',           // Get city via zip_codes
            'zipCode.zipCodeList'     // Get zip_codes_lists via zip_codes.zip_code
        ])
            ->whereIn('role_id', [1, 2])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function find(int $id)
    {
        return User::findOrFail($id);
    }

    public function store(array $data)
    {
        return User::create([
            'user_first_name' => $data['firstname'],
            'user_last_name' => $data['lastname'],
            'username' => ucwords($data['firstname'] . " " . $data['lastname']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_owner' => $data['owner'],
            'zip_code_id' => $data['zip_code_id'],
            'user_business_name' => $data['businessname'],
            'user_phone_number' => $data['phonenumber'],
            'user_mobile_number' => $data['mobilenumber'],
            'role_id' => 2,
            'user_type' => 2,
            'account_type' => $data['user_account_type'],
            'num_of_login' => 1,
            'last_login' =>  date('Y-m-d H:i:s'),
            'permission_users' => $data['user_privileges'],
            'email_verified_at' =>  date('Y-m-d H:i:s')
        ]);
    }

    public function update(int $id, array $data)
    {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }

    public function delete(int $id)
    {
        $user = $this->find($id);
        return $user->delete();
    }
}

