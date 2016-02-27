<?php

namespace app\models;

use Yii;
use yii\base\Model;

class EntryForm extends Model
{
    public $name;
    public $email;

    /**
     * Validates the data. Comes with predefined error messages
     */
    public function rules() //called via ->validate()
    {
        return [
            [['name', 'email'], 'required'], //name and email are required
            ['email', 'email'], //email is syntatically an email address
        ];
    }
}
