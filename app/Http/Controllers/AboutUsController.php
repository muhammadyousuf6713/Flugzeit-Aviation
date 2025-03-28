<?php
namespace App\Http\Controllers;

use App\Models\AboutPage;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
function create()  {
    return view('about_pages.create');
   }
}
