<?php
class TCopyFilesHelper
{
    public static function systemSkeletonToNewSystemByTpSystem($pathSkeleton)
    {        
        $pathNewSystem = TGeneratorHelper::getPathNewSystem();
        
        $list = new RecursiveDirectoryIterator($pathSkeleton);
        $it   = new RecursiveIteratorIterator($list);
        
        foreach ($it as $file) {
            if ($it->isFile()) {
                //echo ' SubPathName: ' . $it->getSubPathName();
                //echo ' SubPath:     ' . $it->getSubPath()."<br>";
                TGeneratorHelper::mkDir($pathNewSystem.DS.$it->getSubPath());
                copy($pathSkeleton.DS.$it->getSubPathName(), $pathNewSystem.DS.$it->getSubPathName());
            }
        }
    }
    public static function adiantiFolderToNewSystem($pathAdianti)
    {
        $pathNewSystem = TGeneratorHelper::getPathNewSystem();

        if( is_file($pathAdianti) ){
            $origin = $pathAdianti;
            $target = $pathNewSystem.DS.$origin;

            /*
            echo ' <hr>';
            echo ' <pre>';
            echo ' <br><strong>oriem:</strong>       ' . $origin;
            echo ' <br><strong>destino:</strong>     ' . $target;
            echo ' </pre>';
            */

            copy($origin, $target);
        } else {
            TGeneratorHelper::mkDir($pathNewSystem.DS.$pathAdianti);
            $list = new RecursiveDirectoryIterator($pathAdianti);
            $it   = new RecursiveIteratorIterator($list);
            
            foreach ($it as $file) {
                if ($it->isFile()) {
                    $origin = $pathAdianti.DS.$it->getSubPathName();
                    $target = $pathNewSystem.DS.$origin;
                    $mkdir  = $pathNewSystem.DS.$pathAdianti.DS.$it->getSubPath();

                    /*
                    echo ' <hr>';
                    echo ' <pre>';
                    echo ' <br><strong>SubPathName:</strong> ' . $it->getSubPathName();
                    echo ' <br><strong>SubPath:</strong>     ' . $it->getSubPath();
                    echo ' <br><strong>oriem:</strong>       ' . $origin;
                    echo ' <br><strong>destino:</strong>     ' . $target;
                    echo ' <br><strong>mkdir:</strong>       ' . $mkdir;
                    echo ' </pre>';
                    */

                    TGeneratorHelper::mkDir($mkdir);
                    copy($origin, $target);
                }
            }
        } // FIM is_file 

    }

    public static function adiantiToNewSystem()
    {
        TCopyFilesHelper::adiantiFolderToNewSystem('cmd.php');
        TCopyFilesHelper::adiantiFolderToNewSystem('composer.json');
        TCopyFilesHelper::adiantiFolderToNewSystem('composer.lock');
        TCopyFilesHelper::adiantiFolderToNewSystem('crontab.php');
        TCopyFilesHelper::adiantiFolderToNewSystem('download.php');
        TCopyFilesHelper::adiantiFolderToNewSystem('engine.php');
        TCopyFilesHelper::adiantiFolderToNewSystem('index.php');
        TCopyFilesHelper::adiantiFolderToNewSystem('init.php');
        
        TCopyFilesHelper::adiantiFolderToNewSystem('lib');
        TCopyFilesHelper::adiantiFolderToNewSystem('lib');
        TCopyFilesHelper::adiantiFolderToNewSystem('tmp');
        TCopyFilesHelper::adiantiFolderToNewSystem('vendor');
    }

    public static function systemSkeletonToNewSystem()
    {
        $tpSystem = TSysgenSession::getValue(TableInfo::TP_SYSTEM);
        if (empty($tpSystem)) {
            throw new InvalidArgumentException(Message::ERRO_EMPTY_TP_SYSTEM);
        }

        switch ($tpSystem) {
            case TGeneratorHelper::TP_SYSTEM_FORM:
                $pathSkeleton  = 'system_skeleton';
                self::systemSkeletonToNewSystemByTpSystem($pathSkeleton);
                self::adiantiToNewSystem();
            break;
            //--------------------------------------------------------------------------------
            case TGeneratorHelper::TP_SYSTEM_REST:
                $pathSkeleton  = 'system_skeleton'.DS.'common';
                self::systemSkeletonToNewSystemByTpSystem($pathSkeleton);
                $pathSkeleton  = 'system_skeleton'.DS.'rest';
                self::systemSkeletonToNewSystemByTpSystem($pathSkeleton);
            break;
            //--------------------------------------------------------------------------------
            case TGeneratorHelper::TP_SYSTEM_FORM_REST:
                $pathSkeleton  = 'system_skeleton'.DS.'common';
                self::systemSkeletonToNewSystemByTpSystem($pathSkeleton);
                $pathSkeleton  = 'system_skeleton'.DS.'rest';
                self::systemSkeletonToNewSystemByTpSystem($pathSkeleton);
                $pathSkeleton  = 'system_skeleton'.DS.'form';
                self::systemSkeletonToNewSystemByTpSystem($pathSkeleton);
            break;
        }
    }

}