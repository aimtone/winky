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
            'uuid' => '96f749f5-88d8-11ea-9e87-9828a60067ab',
            'name' => 'Free', 
            'price' => 0.00
        ));
        Plan::create(array(
            'uuid' => 'eada5a8e-88da-11ea-9e87-9828a60067ab',
            'name' => 'Starter', 
            'recommended' => 1,
            'price' => 11.99
        ));
        Plan::create(array(
            'uuid' => 'eadc5cda-88da-11ea-9e87-9828a60067ab',
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
            'uuid' => 'd32a8e35-88d9-11ea-9e87-9828a60067ab',
            'description' => 'Number of contacts' 
        ));
        PlanItem::create(array(
            'uuid' => 'd32cc116-88d9-11ea-9e87-9828a60067ab',
            'description' => 'Number of bots' 
        ));
        PlanItem::create(array(
            'uuid' => 'd330c9a1-88d9-11ea-9e87-9828a60067ab', 
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
            'plan_uuid' => '96f749f5-88d8-11ea-9e87-9828a60067ab',
            'planitem_uuid' => 'd32a8e35-88d9-11ea-9e87-9828a60067ab', // Number of contacts
            'value' => 10 
        ));
        Plan_PlanItem::create(array(
            'plan_uuid' => '96f749f5-88d8-11ea-9e87-9828a60067ab',
            'planitem_uuid' => 'd32cc116-88d9-11ea-9e87-9828a60067ab', // Number of bots
            'value' => 1
        ));
        Plan_PlanItem::create(array(
            'plan_uuid' => '96f749f5-88d8-11ea-9e87-9828a60067ab',
            'planitem_uuid' => 'd330c9a1-88d9-11ea-9e87-9828a60067ab', // Number of operators
            'value' => 3
        ));
        
        /* Plan Starter */
        Plan_PlanItem::create(array(
            'plan_uuid' => 'eada5a8e-88da-11ea-9e87-9828a60067ab',
            'planitem_uuid' => 'd32a8e35-88d9-11ea-9e87-9828a60067ab',
            'value' => 50 
        ));
        Plan_PlanItem::create(array(
            'plan_uuid' =>'eada5a8e-88da-11ea-9e87-9828a60067ab',
            'planitem_uuid' => 'd32cc116-88d9-11ea-9e87-9828a60067ab',
            'value' => 2
        ));
        Plan_PlanItem::create(array(
            'plan_uuid' => 'eada5a8e-88da-11ea-9e87-9828a60067ab',
            'planitem_uuid' => 'd330c9a1-88d9-11ea-9e87-9828a60067ab',
            'value' => 3
        ));
        
        /* Plan Premium */
        Plan_PlanItem::create(array(
            'plan_uuid' => 'eadc5cda-88da-11ea-9e87-9828a60067ab',
            'planitem_uuid' => 'd32a8e35-88d9-11ea-9e87-9828a60067ab',
            'value' => 200
        ));
        Plan_PlanItem::create(array(
            'plan_uuid' => 'eadc5cda-88da-11ea-9e87-9828a60067ab',
            'planitem_uuid' => 'd32cc116-88d9-11ea-9e87-9828a60067ab',
            'value' => 5
        ));
        Plan_PlanItem::create(array(
            'plan_uuid' => 'eadc5cda-88da-11ea-9e87-9828a60067ab',
            'planitem_uuid' => 'd330c9a1-88d9-11ea-9e87-9828a60067ab',
            'value' => 6 
        ));

       

       
    }

}