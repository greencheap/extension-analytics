<?php 
namespace GreenCheap\Analytics;

use GreenCheap\Application as App;

class Reports
{
    /**
     * @return object
     */
    public static function getMetrics($reports)
    {
        $data = null;
        foreach($reports as $report){
            foreach($report->getData()->getRows() as $row){
                $metrics = $row->getMetrics();
                foreach($metrics as $metric){
                    $data = $metric->getValues()[0];
                }
            }
        }
        return (object) [
            'explorer' => 'metrics',
            'data' => $data
        ];
    }

    /**
     * @return object
     */
    public static function getDimensions($reports)
    {
        $data = [];
        foreach($reports as $key => $report){
            foreach($report->getData()->getRows() as $row){
                $title = $row->getDimensions()[0];
                $value = null;
                foreach($row->getMetrics() as $metric){
                    $value = $metric->getValues()[0];
                }
                $data[] = [
                    'title' => $title,
                    'value' => $value
                ];
            }
        }
        return (object) [
            'explorer' => 'dimensions',
            'data' => $data
        ];
    }
}
?>