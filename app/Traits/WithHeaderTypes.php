<?php

namespace App\Traits;

use App\Models\Tipo;

trait WithHeaderTypes
{
    protected function getHeaderTypes()
    {
        return Tipo::orderBy('ordem')->get();
    }
}
