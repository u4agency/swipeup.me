<?php
// src/Utils/MaintenanceMode.php

namespace App\Utils;

class MaintenanceMode
{
    private $maintenanceMode;

    public function __construct(bool $maintenanceMode)
    {
        $this->maintenanceMode = $maintenanceMode;
    }

    public function isMaintenanceMode(): bool
    {
        return $this->maintenanceMode;
    }
}
