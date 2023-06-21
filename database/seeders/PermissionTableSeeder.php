<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'product-list',
            'product-create',
            'product-edit',
            'product-delete',

            'category-list',
            'category-create',
            'category-edit',
            'category-delete',

            'employee-list',
            'employee-create',
            'employee-edit',
            'employee-delete',

            'sale-list',
            'sale-create',
            'sale-edit',
            'sale-delete',

            'provider-list',
            'provider-create',
            'provider-edit',
            'provider-delete',

            'proofofpayment-list',
            'proofofpayment-create',
            'proofofpayment-edit',
            'proofofpayment-delete',

            'purchas-list',
            'purchas-create',
            'purchas-edit',
            'purchas-delete',

            'usuario-list',
            'usuario-create',
            'usuario-edit',
            'usuario-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
