<?php

use Illuminate\Database\Seeder;

class ChannelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('channels')->insert([
    			'name' => 'Shopify',
    			'sync' => 'https://3155a4a7f64a0ce0f7cf95f93b852182:fcd5194e83e316e7d1b3f2d915d92b06@stitchlite-jimhoyd.myshopify.com/admin/products.json'
    	]);
    	DB::table('channels')->insert([
    			'name' => 'Vend',
    			'sync' => 'https://vendjimhoyd.vendhq.com/api/products?active=1&access_token=2cm49piD4flvIAmaZGUpKYZAF1gOCkKKzjpatR8z'
    	]);    	
    }
}
