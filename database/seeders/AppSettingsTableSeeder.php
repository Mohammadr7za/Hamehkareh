<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('app_settings')->delete();

        \DB::table('app_settings')->insert(array (
            0 =>
            array (
                'earning_type' => NULL,
                'facebook_url' => '',
                'helpline_number' => '',
                'id' => 1,
                'inquriy_email' => '',
                'instagram_url' => '',
                'language_option' => '["nl","fr","gu","it","pt","es","en", "fa"]',
                'linkedin_url' => '',
                'remember_token' => NULL,
                'site_copyright' => '© 2023 تمامی حقوق برای وب سایت همه کاره محفوظ می باشد',
                'site_description' => '',
                'site_email' => NULL,
                'site_favicon' => NULL,
                'site_logo' => '/tmp/phplwW9Vi',
                'site_name' => 'وب سایت همه کاره',
                'time_zone' => 'Asia/Tehran',
                'twitter_url' => '',
                'youtube_url' => '',
            ),
        ));


    }
}
