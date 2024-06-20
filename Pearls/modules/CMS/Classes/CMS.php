<?php

namespace Pearls\Modules\CMS\Classes;

use Pearls\Modules\CMS\Models\Chronicle;

class CMS
{
    function __construct() {}

    public function getAllChronicles() {
        return Chronicle::count();
    }

    public function getActiveChronicles() {
        return Chronicle::where(['status' => 'active'])->count();
    }

    public function getDisabledChronicles() {
        return Chronicle::where(['status' => 'disabled'])->count();
    }
}