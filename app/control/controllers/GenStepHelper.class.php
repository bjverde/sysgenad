<?php
class GenStepHelper
{
    const STEP00 = 'Welcome';
    const STEP01 = 'Config DataBase';
    const STEP02 = 'Select Tables';
    const STEP03 = 'Select Fields and Log';
    const STEP04 = 'Result';

    public static function getStepPage($stepPage)
    {
        $pagestep = new TPageStep;
        $pagestep->addItem(self::STEP00);
        $pagestep->addItem(self::STEP01);
        $pagestep->addItem(self::STEP02);
        $pagestep->addItem(self::STEP03);
        $pagestep->addItem(self::STEP04);
        $pagestep->select($stepPage);
        return $pagestep;
    }

}