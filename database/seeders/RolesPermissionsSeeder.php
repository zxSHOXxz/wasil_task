<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // الصلاحيات الأساسية
        $abilities = [
            'read',
            'create',
            'update',
            'delete',
        ];

        // تصنيف الصلاحيات حسب المجال
        $permissions_by_role = [
            'admin' => [
                'properties',
                'bookings',
                'users',
                'reports',
                'notifications',
            ],
        ];

        // إنشاء الصلاحيات مع تجنب التكرار
        $allPermissions = [];
        foreach ($permissions_by_role['admin'] as $permission) {
            foreach ($abilities as $ability) {
                $permissionName = $ability . ' ' . $permission;
                if (!Permission::where('name', $permissionName)->exists()) {
                    Permission::create(['name' => $permissionName]);
                }
                $allPermissions[] = $permissionName;
            }
        }

        // إنشاء دور الأدمن
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($allPermissions);

        // إنشاء دور المستخدم مع صلاحيات محددة
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userPermissions = [
            'read properties',
            'read bookings',
            'create bookings',
            'update bookings',
        ];
        $userRole->syncPermissions($userPermissions);

        // تعيين الأدوار للمستخدمين
        if($admin = User::find(1)) {
            $admin->assignRole('admin');
        }

        if($user = User::find(2)) {
            $user->assignRole('user');
        }
    }
}
