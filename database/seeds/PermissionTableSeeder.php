<?php


use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder

{

    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run()

    {

       $permissions = [

            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'employee-list',
            'employee-create',
            'employee-edit',
            'employee-delete',
            'project-list',
            'project-create',
            'project-edit',
            'project-delete',
            'supplier-list',
            'supplier-create',
            'supplier-edit',
            'supplier-delete',
            'purchase-list',
            'purchase-create',
            'purchase-edit',
            'purchase-delete',
            'salaryPayment-list',
            'salaryPayment-create',
            'salaryPayment-edit',
            'salaryPayment-delete',
            'nonpurchase-list',
            'nonpurchase-create',
            'nonpurchase-edit',
            'nonpurchase-delete',
            'pettyCash-list',
            'pettyCash-create',
            'pettyCash-edit',
            'pettyCash-delete',
            'payment-list',
            'payment-create',
            'payment-edit',
            'payment-delete',
        ];


        foreach ($permissions as $permission) {

             Permission::create(['name' => $permission]);

        }

    }

}
