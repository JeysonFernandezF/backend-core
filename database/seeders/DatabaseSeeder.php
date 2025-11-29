<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'test@example.com',
            'password' =>bcrypt('12345678'),
        ]);

        $user2 = User::factory()->create([
            'name' => 'Test Observador',
            'email' => 'observador@example.com',
            'password' =>bcrypt('12345678'),
        ]);

        $user3 = User::factory()->create([
            'name' => 'Test Analista',
            'email' => 'analista@example.com',
            'password' =>bcrypt('12345678'),
        ]);


        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            CatalogSeeder::class,
            // TemplateFormsSeeder::class,
            // ProgramDetailsSeeder::class,
            // FormsSeeder::class,
        ]);

        // $this->call(ObservationWithAnswersSeeder::class);
        $user1->assignRole('admin');
        $user2->assignRole('observador');
        $user3->assignRole('analista');
    }
}
