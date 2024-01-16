<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        	[
        		'id' => 1,
        		'name' => 'Рост',
        	],
        	[
        		'id' => 2,
        		'name' => 'Пол',
        	],
        	[
        		'id' => 3,
        		'name' => 'Цвет глаз',
        	],
        	[
        		'id' => 4,
        		'name' => 'Цвет волос',
        	],
        ];

        foreach ($data as $key => $item) {
        	if (!Property::find($item['id'])) Property::create($item);
        }
    }
}
