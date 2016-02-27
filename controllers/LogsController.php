<?php
namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination; // used to control DB queries
use app\models\Logs; // use the Logs model
use app\models\Locations; // use the Locations model

// Renders views in views/locs
class LogsController extends Controller
{
	// This is the default action for this controller
    public function actionIndex()
    {
        $query = Logs::find(); // fectches all logs data

        $pagination = new Pagination([
            'defaultPageSize' => 5, // sets at most 5 rows in a page
            'totalCount' => $query->count(),
        ]);

        $logs = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        // Render the view named index and pass our variables
        return $this->render('index', [
            'logs' => $logs,
            'pagination' => $pagination,
        ]);
    }

    // Action for looking up ip information
    public function actionLookup()
    {

        /*file_put_contents('php://stderr', print_r("Debugging\n", TRUE)); // debug to apache error log (sudo nano /var/log/apache2/error.log)*/

        $queryLogs = Logs::find(); // fectches all logs data

        $queryLocations = Locations::find(); // fectches all locations data

        $logs = $queryLogs->orderBy('id')
            ->all();

        $locations = $queryLocations->orderBy('id')
            ->all();

        // parse for ip information
        $ipLookup = [];
        foreach ($locations as $location):
            $pieces = explode(":", $location->loc); // parse the string
            $temp = [
                'ip' => $pieces[0], // first piece is IP
                'loc' => $pieces[1] // second is location
            ];
            array_push($ipLookup, $temp);
        endforeach;

        // parse for log information
        $logInfo = [];
        foreach ($logs as $log):
            $pieces = explode(" - - ", $log->log); // parse the string
            // get the status code (Note - HTTP/1.1 presedes all status codes)
            $status = substr(strstr($log->log, "HTTP/1.1"), 10, 3);
            $temp = [
                'ip' => $pieces[0], // first piece is IP
                'log' => $pieces[1], // second is log info
                'status' => $status
            ];
            array_push($logInfo, $temp);
        endforeach;

        // determine location for each ip and hits for distinct locations
        $distinctLocations = [];

        foreach ($logInfo as $log):
            $curLogIp = $log['ip'];
            $curLogLog = $log['log'];
            $curLogStatus = []; // add all status codes for each location
            array_push($curLogStatus, $log['status']);
            // check if the IP is found
            $ipFnd = false;
            foreach ($ipLookup as $ipInfo):
                $lookupIp = $ipInfo['ip'];
                $lookupLoc = $ipInfo['loc'];
                // check if there are any wildcards
                $lookupIpPieces = explode("*", $lookupIp); // parse the string for wild cards
                $wildcardNum = sizeof($lookupIpPieces);
                $tmpCurLogIp = $curLogIp; // temporary curLogIp to test against
                if ($wildcardNum > 1) { // if there is a wildcard
                    // reset the matching criteria to the wildcard length
                    $curLogIpPieces = explode(".", $curLogIp);
                    // Compare to ipv4 length
                    $ipv4 = 4;
                    $i = $ipv4 - $wildcardNum;
                    $tmpIp = "";
                    $j = 0;
                    while ($i-- >= 0) {
                        $tmpIp .= $curLogIpPieces[$j++] . ".";
                    }
                    while (--$wildcardNum > 1) {
                        $tmpIp .= "*.";
                    }
                    $tmpIp .= "*";
                    $tmpCurLogIp = $tmpIp;
                }
                if ($tmpCurLogIp == $lookupIp) {
                    $ipFnd = true;
                    // check to see if this location already exists
                    $locFnd = false;
                    $i = 0;
                    foreach ($distinctLocations as $loc):
                        if ($lookupLoc == $loc['loc']) {
                            $locFnd = true;
                            $distinctLocations[$i]['cnt']++;
                            // check to see if this status has been reported
                            $statusFnd = false;
                            foreach ($loc['status'] as $status):
                                if ($status == $curLogStatus[0]) {
                                    $statusFnd = true;
                                    break;
                                }
                            endforeach;
                            if (!$statusFnd) {
                                array_push($distinctLocations[$i]['status'], $curLogStatus[0]);
                            }
                            // check to see if this IP has been reported
                            $multiIpFnd = false;
                            foreach ($loc['ips'] as $ip):
                                if ($ip == $curLogIp) {
                                    $multiIpFnd = true;
                                    break;
                                }
                            endforeach;
                            if (!$multiIpFnd) {
                                array_push($distinctLocations[$i]['ips'], $curLogIp);
                            }
                            break;
                        }
                        $i++;
                    endforeach;
                    if (!$locFnd) {
                        $obj = [
                            'loc' => $lookupLoc,
                            'cnt' => 1,
                            'status' => $curLogStatus,
                            'ips' => [$curLogIp]
                        ];
                        array_push($distinctLocations, $obj);
                    }
                    break;
                }
            endforeach;
            // if IP is not found, push into location = "UNKNOWN"
            if (!$ipFnd) {    
                // first check to see if we have an unknown category
                $unknownFnd = false;
                $i = 0;
                foreach ($distinctLocations as $loc):
                    if ("UNKNOWN" == $loc['loc']) {
                        $unknownFnd = true;
                        $distinctLocations[$i]['cnt']++;
                        // check to see if this status has been reported
                        $statusFnd = false;
                        foreach ($loc['status'] as $status):
                            if ($status == $curLogStatus[0]) {
                                $statusFnd = true;
                                break;
                            }
                        endforeach;
                        if (!$statusFnd) {
                            array_push($distinctLocations[$i]['status'], $curLogStatus[0]);
                        }
                        // check to see if this IP has been reported
                        $multiIpFnd = false;
                        foreach ($loc['ips'] as $ip):
                            if ($ip == $curLogIp) {
                                $multiIpFnd = true;
                                break;
                            }
                        endforeach;
                        if (!$multiIpFnd) {
                            array_push($distinctLocations[$i]['ips'], $curLogIp);
                        }
                        break;
                    }
                    $i++;
                endforeach;
                if (!$unknownFnd) {
                    $obj = [
                        'loc' => "UNKNOWN",
                        'cnt' => 1,
                        'status' => $curLogStatus,
                        'ips' => [$curLogIp]
                    ];
                    array_push($distinctLocations, $obj);
                }
            }
        endforeach;

        // Order by location
        usort($distinctLocations, function($a, $b) {
            return $a['loc'] > $b['loc'] ? 1 : -1; //Compare the location strings
        });

        // render lookup and pass distinctLocations
        return $this->render('lookup', ['locs' => $distinctLocations]);
    }
}