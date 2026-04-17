<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Itstructure\LaRbac\Models\{Role, Permission};

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Custom permissions
        $permissions = [
            ['slug' => Permission::ADMINISTRATE_PERMISSION, 'name' => 'Administrate', 'description' => 'Izin untuk mengatur sistem'],

            ['slug' => 'create-users', 'name' => 'Create Users', 'description' => 'Izin untuk membuat pengguna'],

            ['slug' => 'manage-categories', 'name' => 'Manage Categories', 'description' => 'Izin untuk mengelola kategori'],

            ['slug' => 'view-documents', 'name' => 'View Documents', 'description' => 'Izin untuk melihat dokumen'],
            ['slug' => 'create-documents', 'name' => 'Create Documents', 'description' => 'Izin untuk membuat dokumen'],
            ['slug' => 'edit-documents', 'name' => 'Edit Documents', 'description' => 'Izin untuk mengedit dokumen'],
            ['slug' => 'delete-documents', 'name' => 'Delete Documents', 'description' => 'Izin untuk menghapus dokumen'],

            ['slug' => 'view-revisions', 'name' => 'View Revisions', 'description' => 'Izin untuk melihat revisi dokumen'],
            ['slug' => 'edit-revisions', 'name' => 'Edit Revisions', 'description' => 'Izin untuk mengedit revisi dokumen'],
            ['slug' => 'create-revisions', 'name' => 'Create Revisions', 'description' => 'Izin untuk membuat revisi dokumen'],

            ['slug' => 'view-histories', 'name' => 'View Histories', 'description' => 'Izin untuk melihat riwayat dokumen'],

            ['slug' => 'active-document', 'name' => 'Active Document', 'description' => 'Izin untuk mengakses dokumen aktif'],
            ['slug' => 'view-approval', 'name' => 'View Approval', 'description' => 'Izin untuk melihat approval dokumen'],
            ['slug' => 'edit-approval', 'name' => 'Edit Approval', 'description' => 'Izin untuk mengedit approval dokumen'],
            ['slug' => 'view-tasks', 'name' => 'View Tasks', 'description' => 'Izin untuk melihat penugasan'],
            ['slug' => 'create-tasks', 'name' => 'Create Tasks', 'description' => 'Izin untuk membuat penugasan'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }

        // Custom roles
        $roles = [
            'administrator' => [
                'name' => 'Administrator',
                'permissions' => [
                    Permission::ADMINISTRATE_PERMISSION,
                    'create-users',
                    'manage-categories',
                    'view-documents',
                    'create-documents',
                    'edit-documents',
                    'delete-documents',
                    'view-revisions',
                    'create-revisions',
                    'edit-revisions',
                    'view-histories',
                    'active-document',
                    'view-approval',
                    'edit-approval',
                    'view-tasks',
                    'create-tasks',
                ],
            ],
            'pengendali-dokumen' => [
                'name' => 'Pengendali Dokumen',
                'permissions' => [
                    'view-documents',
                    'active-document',
                    'view-approval',
                    'edit-approval',
                    'view-histories',
                    'view-tasks'
                ],
            ],
            'bagian-mutu' => [
                'name' => 'Bagian Mutu',
                'permissions' => [
                    'view-documents',
                    'active-document',
                    'view-approval',
                    'edit-approval',
                    'view-histories',
                    'view-tasks'
                ],
            ],
            'kepala-puskesmas' => [
                'name' => 'Kepala Puskesmas',
                'permissions' => [
                    'view-documents',
                    'active-document',
                    'view-approval',
                    'edit-approval',
                    'view-histories',
                    'view-tasks',
                    'create-tasks',
                ],
            ],
            'pj-program' => [
                'name' => 'PJ Program',
                'permissions' => [
                    'view-documents',
                    'active-document',
                    'view-revisions',
                    'create-documents',
                    'edit-documents',
                    'create-revisions',
                    'edit-revisions',
                    'view-histories',
                    'view-tasks'
                ],
            ],
            'staff' => [
                'name' => 'Staff',
                'permissions' => [
                    'view-documents',
                    'active-document',
                    'view-tasks'
                ],
            ],
        ];

        foreach ($roles as $slug => $data) {
            $this->createRole($slug, $data['name'], $data['permissions']);
        }
    }

    /**
     * Create a role and attach permissions.
     *
     * @param string $slug
     * @param string $name
     * @param array $permissionSlugs
     * @return void
     */
    private function createRole(string $slug, string $name, array $permissionSlugs): void
    {
        $role = Role::updateOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'description' => "{$name} role"]
        );

        $permissionIds = Permission::whereIn('slug', $permissionSlugs)->pluck('id');
        $role->permissions()->sync($permissionIds);
    }

}
