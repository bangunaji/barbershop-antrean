<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Service; 
class LandingPageController extends Controller
{
    public function index()
    {
        $services = Service::all(['id', 'name', 'description', 'price', 'duration_minutes']);
        return view('landing', ['services' => $services]);
    }
}