<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'user_id' => 1,
            'first_name' => 'Basel',
            'last_name' => 'Bashir',
            'phone' => '+963993995965',
            'status' => \App\Enums\CustomerStatus::Active->value,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1
        ]);
    }
}
