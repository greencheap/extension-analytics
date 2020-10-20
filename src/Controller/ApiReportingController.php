<?php

namespace GreenCheap\Analytics\Controller;

use GreenCheap\Application as App;
use GreenCheap\Analytics\GoogleService;
use Symfony\Component\HttpFoundation\Response;
use GreenCheap\Analytics\Reports;
/**
 * @Access(admin=true)
 */
class ApiReportingController extends GoogleService
{
    /**
     * @Request({"view_id":"string","analytics":"array","explorer":"array"} , csrf=true)
     */
    public function getReportAction(string $view_id = '', array $analytics = [], array $explorer = [])
    {
        if (!$view_id) {
            return App::abort(500, __('Not Found View ID'));
        }
        try {
            // Create the DateRange object.
            $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
            $dateRange->setStartDate($analytics['startDate']);
            $dateRange->setEndDate($analytics['endDate']);

            if($explorer['tag'] === 'dimensions'){
                $report = new \Google_Service_AnalyticsReporting_Dimension();
                $report->setName($explorer['name']);

            }else{
                $report = new \Google_Service_AnalyticsReporting_Metric();
                $report->setExpression($explorer['name']);
                $report->setAlias($explorer['alias']);
            }

            $request = new \Google_Service_AnalyticsReporting_ReportRequest();
            $request->setViewId($view_id);
            $request->setDateRanges($dateRange);

            if($explorer['tag'] === 'dimensions'){
                $request->setDimensions(array($report));
            }else{
                $request->setMetrics(array($report));
            }

            $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
            $body->setReportRequests(array($request));
            $response = $this->analytics->reports->batchGet($body);

            if($explorer['tag'] === 'dimensions'){
                $data = Reports::getDimensions($response);
            }else{
                $data = Reports::getMetrics($response);
            }

            return compact('data');
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
}
