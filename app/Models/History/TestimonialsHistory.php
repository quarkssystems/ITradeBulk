<?php

namespace App\Models\History;

use App\Models\Testimonials;
use App\Models\Support\HistoryModelSupport;

class TestimonialsHistory extends Testimonials
{
    use HistoryModelSupport;
    protected $table = "testimonials_histories";
}
