<?php

namespace GreenCheap\Analytics;

use GreenCheap\Application as App;

class GoogleService
{
    public $analytics;

    /**
     * 
     */
    public function __construct()
    {
        $module = App::module('analytics');
        $this->analytics = $this->initializeAnalytics($module->path.'/google_service.json');
    }

    /**
     * Initializes an Analytics Reporting API V4 service object.
     *
     * @return An authorized Analytics Reporting API V4 service object.
     */
    function initializeAnalytics(string $keyFilePath)
    {

        // Use the developers console and download your service account
        // credentials in JSON format. Place them in this directory or
        // change the key file location if necessary.
        $KEY_FILE_LOCATION = $keyFilePath;
        // Create and configure a new client object.
        $client = new \Google_Client();
        $client->setApplicationName("GreenCheap CMS Analytics Reporting");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new \Google_Service_AnalyticsReporting($client);

        return $analytics;
    }
}
