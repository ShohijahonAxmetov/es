<?php

namespace Database\Seeders;

use App\Models\Answer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        	[
        		'id' => 1,
        		'name' => 'Да',
        	],
        	[
        		'id' => 2,
        		'name' => 'Нет',
        	],
        ];

        foreach ($data as $key => $item) {
        	if (!Answer::find($item['id'])) Answer::create($item);
        }
    }
}
