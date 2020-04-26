<?php

use Illuminate\Database\Seeder;
use App\Plan;
use App\PlanItem;
use App\Plan_PlanItem;

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
        $this->command->info('"plans" table seeded!');
        $this->call('PlanItemTableSeeder');
        $this->command->info('"planitems" table seeded!');
        $this->call('Plan_PlanItemTableSeeder');
        $this->command->info('"plans_planitems" table seeded!');
    }
    
}

class PlanTableSeeder extends Seeder {

    public function run()
    {
        DB::table('plans')->delete();
        Plan::create(array(
            'uuid' => '0a764ce4-7023-452d-a3b7-423bdc1ab22c', 
            'name' => 'Free', 
            'price' => 0.00
        ));
        Plan::create(array(
            'uuid' => 'fd2fdaa4-bc05-41ec-872b-285ef230dc09', 
            'name' => 'Starter', 
            'price' => 11.99
        ));
        Plan::create(array(
            'uuid' => '8adfde67-01b3-4a09-988a-7b4457df30d6', 
            'name' => 'Premium', 
            'price' => 15.99
        ));
    }

}

class PlanItemTableSeeder extends Seeder {

    public function run()
    {
        DB::table('planitems')->delete();
        PlanItem::create(array(
            'uuid' => '2792cfa1-5433-45b2-a969-836f913f1209', 
            'description' => 'Number of contacts' 
        ));
        PlanItem::create(array(
            'uuid' => '7845328b-de6f-4410-b70b-b099dded02d8', 
            'description' => 'Number of bots' 
        ));
        PlanItem::create(array(
            'uuid' => '5ee04ff1-b84d-45ec-bae1-4e1aa83fa01c', 
            'description' => 'Number of operators' 
        ));
    }

}

class Plan_PlanItemTableSeeder extends Seeder {

    public function run()
    {
        DB::table('plan__planitems')->delete();

        /* Plan Free */
        Plan_PlanItem::create(array(
            'uuid' => 'fbefb6f9-e01d-40eb-b497-7da7e7bdc3bc', 
            'plan_id' => 1,
            'planitem_id' => 1, // Number of contacts
            'value' => 10 
        ));
        Plan_PlanItem::create(array(
            'uuid' => 'f63d33ba-6849-471c-a63a-7a1cd7cd86bb', 
            'plan_id' => 1,
            'planitem_id' => 2, // Number of bots
            'value' => 1
        ));
        Plan_PlanItem::create(array(
            'uuid' => 'e48a38e3-7dea-4f46-97b9-daaac14e341f', 
            'plan_id' => 1,
            'planitem_id' => 3, // Number of operators
            'value' => 3
        ));
        
        /* Plan Starter */
        Plan_PlanItem::create(array(
            'uuid' => '7d505ddc-911e-490f-a061-7b9c6e627879', 
            'plan_id' => 2,
            'planitem_id' => 1,
            'value' => 50 
        ));
        Plan_PlanItem::create(array(
            'uuid' => '1ef3a9e6-ad27-4800-9bf4-5ceb52a575af', 
            'plan_id' => 2,
            'planitem_id' => 2,
            'value' => 2
        ));
        Plan_PlanItem::create(array(
            'uuid' => '245c6a0f-a690-4177-9adb-35b89d7b8552', 
            'plan_id' => 2,
            'planitem_id' => 3,
            'value' => 3
        ));
        
        /* Plan Premium */
        Plan_PlanItem::create(array(
            'uuid' => '3d4568b4-0b48-44c7-aede-8080b64f77c2', 
            'plan_id' => 3,
            'planitem_id' => 1,
            'value' => 200
        ));
        Plan_PlanItem::create(array(
            'uuid' => 'edc46602-2d09-498d-b34a-022f9c145153', 
            'plan_id' => 3,
            'planitem_id' => 2,
            'value' => 5
        ));
        Plan_PlanItem::create(array(
            'uuid' => '4bf812ba-b37e-4673-8ebe-3c893a5867dc', 
            'plan_id' => 3,
            'planitem_id' => 3,
            'value' => 6 
        ));

       

       
    }

}