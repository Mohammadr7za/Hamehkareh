<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('settings')->delete();

        \DB::table('settings')->insert(array (
            0 =>
            array (
                'id' => 1,
                'key' => 'ADMOB_APP_ID',
                'type' => 'ADMOB',
                'value' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'key' => 'ADMOB_BANNER_ID',
                'type' => 'ADMOB',
                'value' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'key' => 'ADMOB_INTERSTITIAL_ID',
                'type' => 'ADMOB',
                'value' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'key' => 'CURRENCY_COUNTRY_ID',
                'type' => 'CURRENCY',
                'value' => '231',
            ),
            4 =>
            array (
                'id' => 5,
                'key' => 'CURRENCY_POSITION',
                'type' => 'CURRENCY',
                'value' => 'left',
            ),
            5 =>
            array (
                'id' => 6,
                'key' => 'ONESIGNAL_API_KEY',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'key' => 'ONESIGNAL_REST_API_KEY',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'key' => 'DISTANCE_TYPE',
                'type' => 'DISTANCE',
                'value' => 'km',
            ),
            8 =>
            array (
                'id' => 9,
                'key' => 'DISTANCE_RADIOUS',
                'type' => 'DISTANCE',
                'value' => '50',
            ),
            9 =>
            array (
                'id' => 10,
                'key' => 'dashboard_setting',
                'type' => 'dashboard_setting',
                'value' => '{"Top_Cards":"top_card","Monthly_Revenue_card":"monthly_revenue_card","Top_Services_card":"top_service_card","New_Provider_card":"new_provider_card","Upcoming_Booking_card":"upcoming_booking_card","New_Customer_card":"new_customer_card"}',
            ),
            10 =>
            array (
                'id' => 11,
                'key' => 'provider_dashboard_setting',
                'type' => 'provider_dashboard_setting',
                'value' => '{"Top_Cards":"top_card","Monthly_Revenue_card":"monthly_revenue_card","Top_Services_card":"top_service_card","New_Provider_card":"new_provider_card","Upcoming_Booking_card":"upcoming_booking_card","New_Customer_card":"new_customer_card"}',
            ),
            11 =>
            array (
                'id' => 12,
                'key' => 'handyman_dashboard_setting',
                'type' => 'handyman_dashboard_setting',
                'value' => '{"Top_Cards":"top_card","Schedule_Card":"schedule_card"}',
            ),
            12 =>
            array (
                'id' => 13,
                'key' => 'ONESIGNAL_APP_ID_PROVIDER',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            13 =>
            array (
                'id' => 14,
                'key' => 'ONESIGNAL_REST_API_KEY_PROVIDER',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            14 =>
            array (
                'id' => 15,
                'key' => 'GOOGLE_MAP_KEY',
                'type' => 'GOOGLE_MAP_KEY',
                'value' => NULL,
            ),
            15 =>
            array (
                'id' => 16,
                'key' => 'CHANNEL_ID',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            16 =>
            array (
                'id' => 17,
                'key' => 'ONESIGNAL_CHANNEL_ID_PROVIDER',
                'type' => 'ONESIGNAL',
                'value' => NULL,
            ),
            17 =>
            array (
                'id' => 18,
                'key' => 'terms_condition',
                'type' => 'terms_condition',
            'value' => '<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">By accessing products on this site and placing an order from our website, you confirm that you are in agreement with and bound by the terms and conditions presented and outlined here. These terms apply to the entire website and any email or other type of communication between you and Iqonic Design. The Iqonic Design team is not liable for any direct, indirect, incidental or consequential damages, including, but not limited to, loss of data or profit, arising out of the use the materials on this site.<br /><br style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000;" />Iqonic Design will not be responsible for any outcome that may occur during the course of usage of our resources. We reserve the rights to change prices and revise the resources usage policy in any moment.</p>
<h5 style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 0px; font-family: Conv_Hero-Regular, sans-serif; font-weight: var(--font-weight-h5); line-height: var(--font-line-height-h5); color: var(--wp--preset--color--title); font-size: var(--font-size-h5); font-stretch: condensed; clear: both; overflow-wrap: break-word; letter-spacing: var(--font-letter-spacing-h5); background-color: #ffffff;"><span style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; font-weight: bolder;">Products</span></h5>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">All products and services offered on this site are produced by Iqonic Design. You can access your download from your respective dashboard. We do not provide support for 3rd party software, plugins or libraries that you might have used with our products.</p>
<h5 style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 0px; font-family: Conv_Hero-Regular, sans-serif; font-weight: var(--font-weight-h5); line-height: var(--font-line-height-h5); color: var(--wp--preset--color--title); font-size: var(--font-size-h5); font-stretch: condensed; clear: both; overflow-wrap: break-word; letter-spacing: var(--font-letter-spacing-h5); background-color: #ffffff;">Security</h5>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">Iqonic Design does not process any order payments through the website. All payments are processed securely through RazorPay and Stripe, a third party online payment providers.</p>
<h5 style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 0px; font-family: Conv_Hero-Regular, sans-serif; font-weight: var(--font-weight-h5); line-height: var(--font-line-height-h5); color: var(--wp--preset--color--title); font-size: var(--font-size-h5); font-stretch: condensed; clear: both; overflow-wrap: break-word; letter-spacing: var(--font-letter-spacing-h5); background-color: #ffffff;">Cookie Policy</h5>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">A cookie is a file containing an identifier (a string of letters and numbers) that is sent by a web server to a web browser and is stored by the browser. The identifier is then sent back to the server each time the browser requests a page from the server. Our website uses cookies. By using our website and agreeing to this policy, you consent to our use of cookies in accordance with the terms of this policy.<br /><br style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000;" />We use session cookies to personalize the website for each user.<br /><br style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000;" />We use Google Analytics to analyze the use of our website. Our analytics service provider generates statistical and other information about website use by means of cookies. Deleting cookies will have a negative impact on the usability of the site. If you block cookies, you will not be able to use all the features on our website.<br /><br /></p>
<h5 style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 0px; font-family: Conv_Hero-Regular, sans-serif; font-weight: var(--font-weight-h5); line-height: var(--font-line-height-h5); color: var(--wp--preset--color--title); font-size: var(--font-size-h5); font-stretch: condensed; clear: both; overflow-wrap: break-word; letter-spacing: var(--font-letter-spacing-h5); background-color: #ffffff;">Refunds</h5>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">You can ask for refund against the item purchased under certain circumstances listed in our Refund Policy. In the event that you meet the applicable mark for receiving refund, Iqonic Design will issue you a refund and ask you to specify how the product turned down your item performance expectations.</p>
<h5 style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 0px; font-family: Conv_Hero-Regular, sans-serif; font-weight: var(--font-weight-h5); line-height: var(--font-line-height-h5); color: var(--wp--preset--color--title); font-size: var(--font-size-h5); font-stretch: condensed; clear: both; overflow-wrap: break-word; letter-spacing: var(--font-letter-spacing-h5); background-color: #ffffff;">Email</h5>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">By signing up on our website https://iqonic.design you agree to receive emails from us &ndash; Transactional as well as promotional (occasional).</p>
<h5 style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 0px; font-family: Conv_Hero-Regular, sans-serif; font-weight: var(--font-weight-h5); line-height: var(--font-line-height-h5); color: var(--wp--preset--color--title); font-size: var(--font-size-h5); font-stretch: condensed; clear: both; overflow-wrap: break-word; letter-spacing: var(--font-letter-spacing-h5); background-color: #ffffff;">Ownership</h5>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">Ownership of the product is governed by the usage license.</p>
<h5 style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 0px; font-family: Conv_Hero-Regular, sans-serif; font-weight: var(--font-weight-h5); line-height: var(--font-line-height-h5); color: var(--wp--preset--color--title); font-size: var(--font-size-h5); font-stretch: condensed; clear: both; overflow-wrap: break-word; letter-spacing: var(--font-letter-spacing-h5); background-color: #ffffff;">Changes about terms</h5>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">We may change/update our terms of use without any prior notice. If we change our terms and condition, we will post those changes on this page. Users can check latest version in here.</p>
<p>&nbsp;</p>',
            ),
            18 =>
            array (
                'id' => 19,
                'key' => 'privacy_policy',
                'type' => 'privacy_policy',
            'value' => '<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">This Privacy Policy is brought by Iqonic Design. Iqonic Design is the sole owner of a number of demo websites containing previews of WordPress website themes. This Privacy Policy shall apply to all Iqonic Design sites where this Privacy Policy is featured. This Privacy Policy describes how the Iqonic Design collects, uses, shares and secures personal information that you provide.</p>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">Iqonic Design does not share personal information of any kind with anyone. We will not sell or rent your name or personal information to any third party. We do not sell, rent or provide outside access to our mailing list or any data we store. Any data that a user stores via our facilities is wholly owned by that user or business. At any time, a user or business is free to take their data and leave, or to simply delete their data from our facilities.</p>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">Iqonic Design only collects specific personal information that is necessary for you to access and use our services. This personal information includes, but is not limited to, first and last name, email address, Country of residence.</p>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">Iqonic Design may release personal information if Iqonic Design is required to by law, search warrant, subpoena, court order or fraud investigation. We may also use personal information in a manner that does not identify you specifically nor allow you to be contacted but does identify certain criteria about our site&rsquo;s users in general (such as we may inform third parties about the number of registered users, number of unique visitors, and the pages most frequently browsed).</p>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">&nbsp;</p>
<h5 style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 0px; font-family: Conv_Hero-Regular, sans-serif; font-weight: var(--font-weight-h5); line-height: var(--font-line-height-h5); color: var(--wp--preset--color--title); font-size: var(--font-size-h5); font-stretch: condensed; clear: both; overflow-wrap: break-word; letter-spacing: var(--font-letter-spacing-h5); background-color: #ffffff;">Use of Information</h5>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">We use the information to enable your use of the site and its features and to assure security of use and prevent any potential abuse. We may use the information that we collect for a variety of purposes including:</p>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;"><span style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; font-weight: bolder;">Promotion</span>&nbsp;&mdash; With your consent we send promotional communications, such as providing you with information about products and services, features, surveys, newsletters, offers, promotions, contests and events;</p>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;"><span style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; font-weight: bolder;">Safety and security</span>&nbsp;&mdash; We use the information we have to verify accounts and activities, combat harmful conduct, detect and prevent spam and other bad experiences, maintain the integrity of the Platform, and promote safety and security.</p>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;"><span style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; font-weight: bolder;">Product research and development</span>&nbsp;&mdash; We use the information we have to develop, test and improve our Platform and Services, by conducting surveys and research, and testing and troubleshooting new products and features.</p>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;"><span style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; font-weight: bolder;">Communication with you</span>&nbsp;&mdash; We use the information we have to send you various communications, communicate with you about our products, and let you know about our policies and terms. We also use your information to respond to you when you contact us.</p>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">&nbsp;</p>
<h5 style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 0px; font-family: Conv_Hero-Regular, sans-serif; font-weight: var(--font-weight-h5); line-height: var(--font-line-height-h5); color: var(--wp--preset--color--title); font-size: var(--font-size-h5); font-stretch: condensed; clear: both; overflow-wrap: break-word; letter-spacing: var(--font-letter-spacing-h5); background-color: #ffffff;">Amendments</h5>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">We may amend this Privacy Policy from time to time. When we amend this Privacy Policy, we will update this page accordingly and require you to accept the amendments in order to be permitted to continue using our services.</p>
<h5 style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 0px; font-family: Conv_Hero-Regular, sans-serif; font-weight: var(--font-weight-h5); line-height: var(--font-line-height-h5); color: var(--wp--preset--color--title); font-size: var(--font-size-h5); font-stretch: condensed; clear: both; overflow-wrap: break-word; letter-spacing: var(--font-letter-spacing-h5); background-color: #ffffff;"><br style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000;" />Contact Us</h5>
<p style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; margin: 15px 0px; line-height: 1.66em; font-family: var(--global-font-family); font-size: 20px; color: var(--wp--preset--color--body); background-color: #ffffff;">You can learn more about how privacy works within our site by contacting us. If you have questions about this Policy, you can contact us via email address provided. Additionally, we may also resolve any disputes you have with us in connection with our privacy policies and practices through direct contact. Write to us at&nbsp;<span style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; transition: all 0.5s ease-in-out 0s; text-decoration-line: underline;"><em style="box-sizing: border-box; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-scroll-snap-strictness: proximity; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000;">hello@iqonic.design</em></span></p>
<p>&nbsp;</p>',
            ),


            19 =>
            array (
                'id' => 20,
                'key' => 'OTHER_SETTING',
                'type' => 'OTHER_SETTING',
                'value' => '{"social_login":1,"google_login":1,"apple_login":1,"otp_login":1,"post_job_request":1,"blog":1,"maintenance_mode":0,"force_update_user_app":0,"user_app_minimum_version":null,"user_app_latest_version":null,"force_update_provider_app":0,"provider_app_minimum_version":null,"provider_app_latest_version":null,"force_update_admin_app":0,"admin_app_minimum_version":null,"admin_app_latest_version":null,"maintenance_mode_secret_code":"", "advanced_payment_setting":1,"wallet":1}',
            ),


        ));


    }
}
