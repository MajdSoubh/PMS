<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'Admin',
            'Inventory Manager',
            'Sales Manager',
            'Purchases Manager',
            'Voucher Manager'
        ];
        $permissions = [
            [
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
            ],
            [
                'drug-list',
                'drug-create',
                'drug-edit',
                'drug-delete',
                'stock-list',
                'stock-create',
                'stock-edit',
                'stock-delete',
            ],
            [
                'invoice-list',
                'invoice-create',
                'invoice-edit',
                'invoice-delete',
                'customer-list',
                'customer-create',
                'customer-edit',
                'customer-delete',
            ],
            [
                'purchase-list',
                'purchase-create',
                'purchase-edit',
                'purchase-delete',
                'supplier-list',
                'supplier-create',
                'supplier-edit',
                'supplier-delete',
            ],
            [
                'voucher-list',
                'voucher-create',
                'voucher-edit',
                'voucher-delete',

            ]
        ];
        for ($i = 0; $i < count($roles); $i++)
        {

            Role::create(["name" => $roles[$i]])->syncPermissions($permissions[$i]);
        }
    }
}
