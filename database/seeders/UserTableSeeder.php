<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users')->delete();

        \DB::table('users')->insert(array (
            0 =>
            array (
                'id' => 1,
                'username' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'contact_number' => '09384515622',
                'address' => NULL,
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678'),
                'country_id' => 231,
                'state_id' => 3924,
                'city_id' => 42865,
                'email_verified_at' => NULL,
                'user_type' => 'admin',
                'player_id' => NULL,
                'provider_id' => NULL,
                'is_featured' => 0,
                'display_name' => 'Admin Admin',
                'providertype_id' => NULL,
                'remember_token' => NULL,
                'last_notification_seen' => NULL,
                'status' => 1,
                'time_zone' => 'UTC',
                'created_at' => '2021-05-28 15:59:15',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'username' => 'manager',
                'first_name' => 'Demo',
                'last_name' => 'Admin',
                'contact_number' => '09384515612',
                'address' => NULL,
                'email' => 'demo@admin.com',
                'password' => bcrypt('12345678'),
                'country_id' => 231,
                'state_id' => 3924,
                'city_id' => 42865,
                'email_verified_at' => NULL,
                'user_type' => 'manager',
                'player_id' => NULL,
                'provider_id' => NULL,
                'is_featured' => 0,
                'display_name' => 'Manager',
                'providertype_id' => NULL,
                'remember_token' => NULL,
                'last_notification_seen' => NULL,
                'status' => 1,
                'time_zone' => 'UTC',
                'created_at' => '2021-05-29 05:40:38',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'username' => 'user',
                'first_name' => 'User',
                'last_name' => 'Demo',
                'contact_number' => '09384515652',
                'address' => NULL,
                'email' => 'demo@user.com',
                'password' => bcrypt('12345678'),
                'country_id' => 231,
                'state_id' => 3924,
                'city_id' => 42865,
                'email_verified_at' => NULL,
                'user_type' => 'user',
                'player_id' => NULL,
                'provider_id' => NULL,
                'is_featured' => 0,
                'display_name' => 'Demo User',
                'providertype_id' => NULL,
                'remember_token' => NULL,
                'last_notification_seen' => NULL,
                'status' => 1,
                'time_zone' => 'UTC',
                'created_at' => '2021-05-28 12:36:58',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'username' => 'provider',
                'first_name' => 'Provider',
                'last_name' => 'Demo',
                'contact_number' => '09384515642',
                'address' => NULL,
                'email' => 'demo@provider.com',
                'password' => bcrypt('12345678'),
                'country_id' => 231,
                'state_id' => 3924,
                'city_id' => 42865,
                'email_verified_at' => NULL,
                'user_type' => 'provider',
                'player_id' => NULL,
                'provider_id' => NULL,
                'is_featured' => 1,
                'display_name' => 'Provider Demo',
                'providertype_id' => 1,
                'remember_token' => NULL,
                'last_notification_seen' => NULL,
                'status' => 1,
                'time_zone' => 'UTC',
                'created_at' => '2021-05-29 05:43:47',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'username' => 'handyman',
                'first_name' => 'Handyman',
                'last_name' => 'Demo',
                'contact_number' => '09384515632',
                'address' => NULL,
                'email' => 'demo@handyman.com',
                'password' => bcrypt('12345678'),
                'country_id' => 181,
                'state_id' => 3924,
                'city_id' => 42865,
                'email_verified_at' => NULL,
                'user_type' => 'handyman',
                'player_id' => NULL,
                'provider_id' => 4,
                'is_featured' => 0,
                'display_name' => 'Handyman Demo',
                'providertype_id' => NULL,
                'remember_token' => NULL,
                'last_notification_seen' => NULL,
                'status' => 1,
                'time_zone' => 'UTC',
                'created_at' => '2021-05-29 05:43:24',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));


    }
}
