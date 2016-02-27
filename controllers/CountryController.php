<?php
namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination; //used to control DB queries
use app\models\Country;

/*Create an index action as default
Renders in /views/country/...*/

class CountryController extends Controller
{
    public function actionIndex()
    {
        $query = Country::find(); //fectches all country data

        $pagination = new Pagination([
            'defaultPageSize' => 5, //sets at most 5 rows in a page
            'totalCount' => $query->count(),
        ]);

        $countries = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        //render a view named index and pass our variables
        return $this->render('index', [
            'countries' => $countries,
            'pagination' => $pagination,
        ]);
    }
}