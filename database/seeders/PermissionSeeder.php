<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'drug-list',
            'drug-create',
            'drug-edit',
            'drug-delete',
            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',
            'supplier-list',
            'supplier-create',
            'supplier-edit',
            'supplier-delete',
            'stock-list',
            'stock-create',
            'stock-edit',
            'stock-delete',
            'voucher-list',
            'voucher-create',
            'voucher-edit',
            'voucher-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'invoice-list',
            'invoice-create',
            'invoice-edit',
            'invoice-delete',
            'purchase-list',
            'purchase-create',
            'purchase-edit',
            'purchase-delete',
            'employee-list',
            'employee-create',
            'employee-edit',
            'employee-delete',
        ];

        foreach ($permissions as $permission)
        {
            Permission::create(['name' => $permission]);
        }
    }
}
