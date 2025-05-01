<?php

namespace App\Services\Admin;

use App\Interfaces\AdminRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AdminService
{
    protected AdminRepositoryInterface $repo;

    public function __construct(AdminRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAllAdmin()
    {
        return $this->repo->all();
    }

    public function getAdminById($id)
    {
        return $this->repo->find($id);
    }

    public function storeAdmin(array $data, $zip_code_id)
    {
        $data['zip_code_id'] = $zip_code_id;
        $data['user_privileges'] = !empty($data['user_privileges']) ? json_encode($data['user_privileges']) : '';

        // Store the user and get its ID
        $this->repo->store($data);
        return  DB::getPdo()->lastInsertId();

    }

    public function updateAdmin($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function deleteAdmin($id)
    {
        return $this->repo->delete($id);
    }
}
