<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $data = [
            [
                'event' => 'Online Process Enrolment',
                'academic_year_id' => 1,
                'content' => 'Greetings from the Light of the World! ?
                    Please be informed that ALL First and Fourth Year students are REQUIRED to attend the Monthly Mass and President\'s Time on September 15, 2023, (3:00 - 5:00 PM), at People\'s Gymnasium.
                    Note:
                    1. For Non- Catho',
                'event_datetime' => '2023-09-22 15:00:00',
                'img_path' => 'gbeDdXVEl7nljb93hFvE4vVc2pA8jhzqH9ChDqHe.jpg'
            ],



        ];

        \App\Models\Event::insertOrIgnore($data);

    }
}
