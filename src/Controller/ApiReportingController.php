<?php

namespace GreenCheap\Analytics\Controller;

use GreenCheap\Application as App;
use GreenCheap\Analytics\GoogleService;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Access(admin=true)
 */
class ApiReportingController extends GoogleService
{
    /**
     * @Request({"view_id":"string","analytics":"array"} , csrf=true)
     */
    public function getReportAction(string $view_id = '', array $analytics = [])
    {
        if (!$view_id) {
            return App::abort(500, __('Not Found View ID'));
        }
        try {
            // Create the DateRange object.
            $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
            $dateRange->setStartDate($analytics['startDate']);
            $dateRange->setEndDate($analytics['endDate']);

            // Create the Metrics object.
            $sessions = new \Google_Service_AnalyticsReporting_Metric();
            $sessions->setExpression($analytics['expression']);
            $sessions->setAlias($analytics['alias']);

            $request = new \Google_Service_AnalyticsReporting_ReportRequest();
            $request->setViewId($view_id);
            $request->setDateRanges($dateRange);
            $request->setMetrics(array($sessions));

            $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
            $body->setReportRequests(array($request));
            $response = $this->analytics->reports->batchGet($body);
            dump($this->analytics->reports->batchGet($body));
            //return $this->printResults($response);
        } catch (\Google_Service_Exception $e) {
            $code = json_decode($e->getMessage(), true);
            $response = new Response();
            $response->setStatusCode($code['error']['code']);
            $response->setContent($code['error']['message']);
            return $response;
        }
    }

    /**
     * @Request({"view_id":"string"} , csrf=true)
     * @param string $view_id
     * @return array|Response|void
     */
    public function testApiAction(string $view_id = '')
    {
        if (!$view_id) {
            return App::abort(500, __('Not Found View ID'));
        }
        try {
            $request = new \Google_Service_AnalyticsReporting_ReportRequest();
            $request->setViewId($view_id);

            $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
            $body->setReportRequests(array($request));
            $response = $this->analytics->reports->batchGet($body);
            return ['msg' => __('Successfully connected to Analytics')];
        } catch (\Google_Service_Exception $e) {
            $code = json_decode($e->getMessage(), true);
            $response = new Response();
            $response->setStatusCode($code['error']['code']);
            $response->setContent($code['error']['message']);
            return $response;
        }
    }

    /**
     * Parses and prints the Analytics Reporting API V4 response.
     *
     * @param An Analytics Reporting API V4 response.
     */
    function printResults($reports)
    {
        for ($reportIndex = 0; $reportIndex < count($reports); $reportIndex++) {
            $report = $reports[$reportIndex];
            $header = $report->getColumnHeader();
            $dimensionHeaders = $header->getDimensions();
            $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
            $rows = $report->getData()->getRows();

            for ($rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
                $row = $rows[$rowIndex];
                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();
                for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
                    print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");
                }

                for ($j = 0; $j < count($metrics); $j++) {
                    $values = $metrics[$j]->getValues();
                    for ($k = 0; $k < count($values); $k++) {
                        $entry = $metricHeaders[$k];
                        print($entry->getName() . ": " . $values[$k] . "\n");
                    }
                }
            }
        }
    }
}
