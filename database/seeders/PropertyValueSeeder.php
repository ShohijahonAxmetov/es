<?php

namespace Database\Seeders;

use App\Models\PropertyValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        	[
        		'property_id' => 2,
        		'data' => [
        			[
        				'id' => 1,
        				'name' => 'Женский пол',
        			],
        			[
        				'id' => 2,
        				'name' => 'Мужской пол',	
        			]
        		]
        	],
        	[
        		'property_id' => 1,
        		'data' => [
        			[
        				'id' => 3,
        				'name' => '1.8',
        			],
        			[
        				'id' => 4,
        				'name' => '1.92',	
        			],
        			[
        				'id' => 5,
        				'name' => '1.68',	
        			],
        		]
        	],
        	[
        		'property_id' => 3,
        		'data' => [
        			[
        				'id' => 6,
        				'name' => 'Черный',
        			],
        			[
        				'id' => 7,
        				'name' => 'Голубой',	
        			],
        		]
        	],
        	[
        		'property_id' => 4,
        		'data' => [
        			[
        				'id' => 8,
        				'name' => 'Желтый',
        			],
        			[
        				'id' => 9,
        				'name' => 'Черный',	
        			],
        		]
        	],
        ];

        foreach ($data as $key => $item) {
        	foreach ($item['data'] as $value) {
        		$value['property_id'] = $item['property_id'];
        		if (!PropertyValue::find($value['id'])) PropertyValue::create($value);
        	}
        }
    }
}
