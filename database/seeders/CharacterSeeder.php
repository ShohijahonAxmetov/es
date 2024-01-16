<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        	[
        		'id' => 1,
        		'name' => 'Alisa',
        	],
        	[
        		'id' => 2,
        		'name' => 'Jamol',
        	],
        	[
        		'id' => 3,
        		'name' => 'Jon',
        	],
        ];

        foreach ($data as $key => $item) {
        	if (!Character::find($item['id'])) Character::create($item);
        }
    }
}
