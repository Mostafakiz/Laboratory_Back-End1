<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role=Role::create([
            'uuid'=>123,
            'name'=>'admin',
        ]);
        $role->users()->attach(User::where('id',1)->first());
        $role=Role::create([
            'uuid'=>124,
            'name'=>'nurse',
        ]);
        // $role->users()->attach(User::where('id',1)->first());
    }
}
