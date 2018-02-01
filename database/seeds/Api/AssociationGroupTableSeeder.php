<?php

use Illuminate\Database\Seeder;
use GetCandy\Api\Associations\Models\AssociationGroup;

class AssociationGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssociationGroup::forceCreate([
            'name' => 'Upsell',
            'handle' => 'upsell',
        ]);
        AssociationGroup::forceCreate([
            'name' => 'Cross-sell',
            'handle' => 'cross-sell'
        ]);
        AssociationGroup::forceCreate([
            'name' => 'Alternate',
            'handle' => 'alternate'
        ]);
    }
}
