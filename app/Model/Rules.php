<?php

namespace roulette\Model;

class Rules
{

    const ZERO = 0;
    private static $types = ['one', 'two', 'three', 'four', 'six', 'twelve', 'black', 'red'];

    public static function checkType(String $type)
    {
        return in_array($type, self::$types);
    }

    public static function checkField($type, $choose, $field, $sum)
    {
        if (!self::checkType($type)) {
            Throw new \Exception ('Incorrect bet type');
        }

        if ($field <0 OR $field>36) Throw new \Exception ('Impossible field');
        if ($choose <1 OR $choose>36) Throw new \Exception ('Impossible choose');

        if ($field == self::ZERO) return 0;

        switch ($type){
            case 'one' :
                if ($choose == $field) return 36*$sum;
                else return 0;
                break;
            case 'two' :
                if ($choose == $field OR $choose == $field-1) return 18*$sum;
                else return 0;
                break;
            case 'three' :
                if ($choose <= $field AND $choose >= $field-2) return 12*$sum;
                else return 0;
                break;
            case 'four' :
                if ($choose <= $field AND $choose >= $field-3) return 9*$sum;
                else return 0;
                break;
            case 'six' :
                if ($choose <= $field AND $choose >= $field-5) return 6*$sum;
                else return 0;
                break;
            case 'twelve' :
                if ($choose <= $field AND $choose >= $field-11) return 3*$sum;
                else return 0;
                break;
            case 'black' :
                if ($field % 2 == 1) return 2*$sum;
                else return 0;
                break;
            case 'red' :
                if ($field % 2 == 0) return 2*$sum;
                else return 0;
                break;
            default:  Throw new \Exception ('Incorrect bet type');
        }

    }

}