<?php
/**
 * SysGen - System Generator with Formdin Framework
 * Download Formdin Framework: https://github.com/bjverde/formDin
 *
 * @author  Bjverde <bjverde@yahoo.com.br>
 * @license https://github.com/bjverde/sysgen/blob/master/LICENSE GPL-3.0
 * @link    https://github.com/bjverde/sysgen
 *
 * PHP Version 7.1
 */
class GenStepHelper
{
    const STEP00 = 'Welcome';
    const STEP01 = 'Config DataBase';
    const STEP02 = 'Select Tables';
    const STEP03 = 'Config Fields and Log';
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