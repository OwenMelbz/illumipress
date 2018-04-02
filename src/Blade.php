<?php

namespace OwenMelbz\IllumiPress;

class BladeLoader
{
    public function __construct()
    {
        $this->loadVendorFiles();

        return $this;
    }

    public function loadVendorFiles()
    {
        $vendorDir = realpath(__DIR__ . '/../../../');
        $bladeRunnerDir = $vendorDir . '/ekandreas/bladerunner';

        foreach ($this->getFileList() as $fileName) {
            $filePath = "{$bladeRunnerDir}/{$fileName}.php";
            require_once($filePath);
        }

        return $this;
    }

    public function getFileList()
    {
        return [
            'src/BladeProvider',
            'src/Blade',
            'src/Config',
            'src/Container',
            'src/Controller',
            'src/ControllerDebug',
            'src/FileViewFinder',
            'src/Repository',
            'globals/helpers',
            'globals/setup',
            'globals/filters',
        ];
    }

}