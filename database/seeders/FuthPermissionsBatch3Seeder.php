<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FuthPermissionsBatch3Seeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Syncing FUTH Lafia EMR permissions batch 3...');

        $newResources = [
            'storeCategory', 'storeLocation', 'storeItem', 'storeStock',
            'storeMovement', 'storeRequisition', 'stockTake',
            'bloodDonor', 'bloodDonation', 'bloodUnit',
            'bloodRequest', 'bloodCrossmatch', 'bloodTransfusion',
            'serviceCategory', 'procedureCategory', 'procedure',
            'procedureBooking', 'procedureSession',
            'goodsReceivedNote', 'drugStockLevel',
        ];

        $actions = ['view', 'view_any', 'create', 'update', 'restore', 'restore_any',
                    'replicate', 'reorder', 'delete', 'delete_any',
                    'force_delete', 'force_delete_any'];

        foreach ($newResources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => $action . '_' . $resource,
                    'guard_name' => 'web',
                ]);
            }
        }

        $rolePerms = [
            'Super Admin' => ['*'],

            'Medical Administrator' => [
                'view_any_storeCategory', 'view_storeCategory', 'create_storeCategory', 'update_storeCategory',
                'view_any_storeLocation', 'view_storeLocation', 'create_storeLocation', 'update_storeLocation',
                'view_any_storeItem', 'view_storeItem', 'create_storeItem', 'update_storeItem',
                'view_any_storeStock', 'view_storeStock', 'create_storeStock', 'update_storeStock',
                'view_any_storeMovement', 'view_storeMovement', 'create_storeMovement',
                'view_any_storeRequisition', 'view_storeRequisition', 'create_storeRequisition', 'update_storeRequisition',
                'view_any_stockTake', 'view_stockTake', 'create_stockTake', 'update_stockTake',
                'view_any_bloodDonor', 'view_bloodDonor', 'create_bloodDonor', 'update_bloodDonor',
                'view_any_bloodDonation', 'view_bloodDonation', 'create_bloodDonation', 'update_bloodDonation',
                'view_any_bloodUnit', 'view_bloodUnit', 'create_bloodUnit', 'update_bloodUnit',
                'view_any_bloodRequest', 'view_bloodRequest', 'create_bloodRequest', 'update_bloodRequest',
                'view_any_bloodCrossmatch', 'view_bloodCrossmatch', 'create_bloodCrossmatch', 'update_bloodCrossmatch',
                'view_any_bloodTransfusion', 'view_bloodTransfusion', 'create_bloodTransfusion', 'update_bloodTransfusion',
                'view_any_serviceCategory', 'view_serviceCategory', 'create_serviceCategory', 'update_serviceCategory',
                'view_any_procedureCategory', 'view_procedureCategory', 'create_procedureCategory', 'update_procedureCategory',
                'view_any_procedure', 'view_procedure', 'create_procedure', 'update_procedure',
                'view_any_procedureBooking', 'view_procedureBooking', 'create_procedureBooking', 'update_procedureBooking',
                'view_any_procedureSession', 'view_procedureSession', 'create_procedureSession', 'update_procedureSession',
                'view_any_goodsReceivedNote', 'view_goodsReceivedNote', 'create_goodsReceivedNote', 'update_goodsReceivedNote',
                'view_any_drugStockLevel', 'view_drugStockLevel', 'create_drugStockLevel', 'update_drugStockLevel',
            ],

            'Doctor' => [
                'view_any_storeItem', 'view_storeItem',
                'view_any_storeStock', 'view_storeStock',
                'view_any_storeRequisition', 'view_storeRequisition', 'create_storeRequisition',
                'view_any_bloodDonor', 'view_bloodDonor',
                'view_any_bloodDonation', 'view_bloodDonation',
                'view_any_bloodUnit', 'view_bloodUnit',
                'view_any_bloodRequest', 'view_bloodRequest', 'create_bloodRequest', 'update_bloodRequest',
                'view_any_bloodCrossmatch', 'view_bloodCrossmatch', 'create_bloodCrossmatch',
                'view_any_bloodTransfusion', 'view_bloodTransfusion', 'create_bloodTransfusion',
                'view_any_procedureCategory', 'view_procedureCategory',
                'view_any_procedure', 'view_procedure',
                'view_any_procedureBooking', 'view_procedureBooking', 'create_procedureBooking', 'update_procedureBooking',
                'view_any_procedureSession', 'view_procedureSession', 'create_procedureSession', 'update_procedureSession',
                'view_any_serviceCategory', 'view_serviceCategory',
            ],

            'Nurse' => [
                'view_any_storeItem', 'view_storeItem',
                'view_any_storeStock', 'view_storeStock',
                'view_any_storeRequisition', 'view_storeRequisition', 'create_storeRequisition',
                'view_any_bloodDonor', 'view_bloodDonor',
                'view_any_bloodUnit', 'view_bloodUnit',
                'view_any_bloodRequest', 'view_bloodRequest',
                'view_any_bloodTransfusion', 'view_bloodTransfusion', 'update_bloodTransfusion',
                'view_any_procedureBooking', 'view_procedureBooking', 'update_procedureBooking',
                'view_any_procedureSession', 'view_procedureSession', 'update_procedureSession',
            ],

            'Pharmacist' => [
                'view_any_storeItem', 'view_storeItem',
                'view_any_storeStock', 'view_storeStock', 'create_storeStock', 'update_storeStock',
                'view_any_storeMovement', 'view_storeMovement', 'create_storeMovement',
                'view_any_goodsReceivedNote', 'view_goodsReceivedNote', 'create_goodsReceivedNote', 'update_goodsReceivedNote',
                'view_any_drugStockLevel', 'view_drugStockLevel', 'create_drugStockLevel', 'update_drugStockLevel',
                'view_any_storeRequisition', 'view_storeRequisition',
            ],

            'Laboratory Scientist' => [
                'view_any_bloodDonor', 'view_bloodDonor', 'create_bloodDonor', 'update_bloodDonor',
                'view_any_bloodDonation', 'view_bloodDonation', 'create_bloodDonation', 'update_bloodDonation',
                'view_any_bloodUnit', 'view_bloodUnit', 'create_bloodUnit', 'update_bloodUnit',
                'view_any_bloodRequest', 'view_bloodRequest',
                'view_any_bloodCrossmatch', 'view_bloodCrossmatch', 'create_bloodCrossmatch',
                'view_any_bloodTransfusion', 'view_bloodTransfusion',
                'view_any_storeItem', 'view_storeItem',
                'view_any_storeStock', 'view_storeStock',
                'view_any_storeRequisition', 'view_storeRequisition', 'create_storeRequisition',
            ],

            'Medical Records Officer' => [
                'view_any_storeItem', 'view_storeItem',
                'view_any_bloodDonor', 'view_bloodDonor',
                'view_any_bloodRequest', 'view_bloodRequest',
                'view_any_bloodTransfusion', 'view_bloodTransfusion',
                'view_any_procedureBooking', 'view_procedureBooking',
                'view_any_procedureSession', 'view_procedureSession',
                'view_any_serviceCategory', 'view_serviceCategory',
            ],

            'Accountant' => [
                'view_any_serviceCategory', 'view_serviceCategory', 'create_serviceCategory', 'update_serviceCategory',
                'view_any_procedureCategory', 'view_procedureCategory',
                'view_any_procedure', 'view_procedure',
                'view_any_procedureBooking', 'view_procedureBooking',
                'view_any_storeItem', 'view_storeItem',
                'view_any_storeMovement', 'view_storeMovement',
                'view_any_goodsReceivedNote', 'view_goodsReceivedNote',
                'view_any_drugStockLevel', 'view_drugStockLevel',
            ],

            'Insurance Officer' => [
                'view_any_procedure', 'view_procedure',
                'view_any_procedureBooking', 'view_procedureBooking',
                'view_any_bloodRequest', 'view_bloodRequest',
                'view_any_bloodTransfusion', 'view_bloodTransfusion',
                'view_any_serviceCategory', 'view_serviceCategory',
            ],
        ];

        foreach ($rolePerms as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if ($perms === ['*']) {
                $role->syncPermissions(Permission::all());
            } else {
                foreach ($perms as $p) {
                    $role->givePermissionTo($p);
                }
            }
        }

        $this->command->info('Permissions batch 3 synced.');
    }
}
