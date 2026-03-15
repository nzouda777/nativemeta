<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class LegalController extends Controller
{
    public function mentions()
    {
        return Inertia::render('Public/Legal/Mentions');
    }

    public function cgv()
    {
        return Inertia::render('Public/Legal/CGV');
    }

    public function privacy()
    {
        return Inertia::render('Public/Legal/Privacy');
    }
}
