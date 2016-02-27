<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination; // used to control DB queries
use app\models\Locations; // use the Locations model
use app\models\IpForm; // use the IpForm model

// Renders views in views/locs
class LocationsController extends Controller
{
	// This is the default action for this controller
    public function actionIndex()
    {
        $query = Locations::find(); // fectches all locations data

        $pagination = new Pagination([
            'defaultPageSize' => 5, // sets at most 5 rows in a page
            'totalCount' => $query->count(),
        ]);

        $locations = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        // Render the view named index and pass our variables
        return $this->render('index', [
            'locations' => $locations,
            'pagination' => $pagination,
        ]);
    }

    // Action for looking up ip information
    public function actionEntry()
    {

        $model = new IpForm();

        // check to see if yii\web\Request::post() was made, then validate data
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // valid data received in $model

            $query = Locations::find(); // fectches all locations data

            $pagination = new Pagination([
                'defaultPageSize' => 5, // sets at most 5 rows in a page
                'totalCount' => $query->count(),
            ]);

            $locations = $query->orderBy('id')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

            $ipLookup = [];

            $rtn = [
                'loc' => "NOT FOUND",
                'cnt' => 0
            ];

            // Check the log files
            foreach ($locations as $location):
                $pieces = explode(":", $location->loc); // parse the string
                $loc = [
                    'ip' => $pieces[0], // first piece is IP
                    'loc' => $pieces[1] // second is location
                ];
                // See if this is the IP requested
                if ($pieces[0] == $model->ip) {
                    $rtn['loc'] = $pieces[1]; // add it's location
                    $rtn['cnt']++;
                }
                array_push($ipLookup, $loc);
            endforeach;

            // Check with geoip
            if ($rtn['loc'] == "NOT FOUND") {
                $geoip = new \lysenkobv\GeoIP\GeoIP();
                $ip = $geoip->ip($model->ip);
                $rtn['loc'] = $ip->country;
            }

            // if successful, render entry-confirm
            return $this->render('entry-confirm', ['loc' => $rtn['loc'], 'cnt' => $rtn['cnt']]);
        } else {
            // either the page is initially displayed or there is some validation error
            return $this->render('entry', ['model' => $model]);
        }
    }
}