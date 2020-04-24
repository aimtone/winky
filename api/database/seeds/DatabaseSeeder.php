<?php

use Illuminate\Database\Seeder;
use App\Plan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('PlanTableSeeder');
        $this->command->info('Plan table seeded!');
    }
    
}

class PlanTableSeeder extends Seeder {

    public function run()
    {
        DB::table('plans')->delete();
        Plan::create(array(
            'uuid' => 'b935b8a0-866b-11ea-a57b-11ba3e8eb881 ', 
            'name' => 'Free', 
            'price' => 39.99
        ));
    }

}