<?php

use Illuminate\Pagination\LengthAwarePaginator;

if (!function_exists('paginate_number')) {
    function paginate_number(LengthAwarePaginator $data, int $localCounter)
    {
        return ($data->currentPage() - 1) * $data->perPage() + $localCounter;
    }
}
