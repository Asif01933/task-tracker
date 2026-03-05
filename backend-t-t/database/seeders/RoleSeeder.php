<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Default guard for API/Sanctum usage.
     * Use 'web' for consistency with Laravel's default auth guard.
     */
    private const GUARD_NAME = 'web';

    /**
     * Default role names for team-based RBAC.
     */
    public const ROLES = ['owner', 'admin', 'member', 'viewer'];

    /**
     * Run the database seeds.
     * Creates global roles (team_id = null) so the same role names
     * are reused across all teams in model_has_roles.
     */
    public function run(): void
    {
        foreach (self::ROLES as $roleName) {
            Role::firstOrCreate(
                [
                    'name' => $roleName,
                    'guard_name' => self::GUARD_NAME,
                ],
                [
                    'name' => $roleName,
                    'guard_name' => self::GUARD_NAME,
                ]
            );
        }
    }
}
