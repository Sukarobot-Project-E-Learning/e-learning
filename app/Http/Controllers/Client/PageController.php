<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOTools;

class PageController extends Controller
{
    public function tentang()
    {
        SEOTools::setTitle('Tentang Kami');
        SEOTools::setDescription('Sukarobot adalah platform pembelajaran teknologi dan robotika yang didedikasikan untuk semua kalangan. Misi kami adalah mencetak talenta digital yang siap bersaing di era industri 4.0.');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'website');
        SEOTools::twitter()->setSite('@sukarobot');

        return view('client.about.tentang');
    }
}
