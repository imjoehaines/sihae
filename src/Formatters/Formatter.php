<?php

namespace Sihae\Formatters;

interface Formatter
{
    public function format(array $data) : array;
}
