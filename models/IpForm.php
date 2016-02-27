<?php

namespace app\models;

use Yii;
use yii\base\Model;

class IpForm extends Model
{
    public $ip;

    /**
     * Validates the data. Comes with predefined error messages
     */
    public function rules() //called via ->validate()
    {
        return [
            [['ip'], 'required'], // ip is required
            ['ip', 'ip'], // uo is syntatically an ip address
        ];
    }
}
