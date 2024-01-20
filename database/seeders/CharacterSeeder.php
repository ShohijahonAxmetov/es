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

        $realData = [
        	[
        		'id' => 1,
        		'name' => 'Перегрев двигателя',
        	],
        	[
        		'id' => 2,
        		'name' => 'Утечка масла или гидравлической жидкости',
        	],
        	[
        		'id' => 3,
        		'name' => 'Запуск двигателя с трудностями',
        	],
        	[
        		'id' => 4,
        		'name' => 'Повышенный уровень шума и вибраций',
        	],
        	[
        		'id' => 5,
        		'name' => 'Отказ гидравлической системы',
        	],
        	[
        		'id' => 6,
        		'name' => 'Проблемы с электрикой',
        	],
        	[
        		'id' => 7,
        		'name' => 'Затрудненное движение или поломка ходовой части',
        	],
        ];

        foreach ($data as $key => $item) {
        	if (!Character::find($item['id'])) Character::create($item);
        }
    }
}
