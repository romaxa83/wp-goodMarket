<?php
namespace backend\modules\blog\validators;

use yii\validators\RegularExpressionValidator;

class AliasValidator extends RegularExpressionValidator
{
    public $pattern = '#^[a-z0-9_-]*$#s';
    public $message = 'Алиас должен состоять из букв латиницы и цифр';
}
