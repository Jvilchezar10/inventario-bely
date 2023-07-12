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
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',

            'category-list',
            'category-create',
            'category-edit',
            'category-delete',

            'provider-list',
            'provider-create',
            'provider-edit',
            'provider-delete',

            'purchas-list',
            'purchas-create',
            'purchas-edit',
            'purchas-delete',

            'purchasesDetail-list',
            'purchasesDetail-create',
            'purchasesDetail-edit',
            'purchasesDetail-delete',

            'client-list',
            'client-create',
            'client-edit',
            'client-delete',

            'sale-list',
            'sale-create',
            'sale-edit',
            'sale-delete',

            'salesDetail-list',
            'salesDetail-create',
            'salesDetail-edit',
            'salesDetail-delete',

            'proofofpayment-list',
            'proofofpayment-create',
            'proofofpayment-edit',
            'proofofpayment-delete',

            'employee-list',
            'employee-create',
            'employee-edit',
            'employee-delete',

            'usuario-list',
            'usuario-create',
            'usuario-edit',
            'usuario-delete',

            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
