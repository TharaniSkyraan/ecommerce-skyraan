<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\RestockMail;
use App\Models\NofityAvailableProduct;
use App\Models\User;

class TestingController extends Controller
{
    public function testingfun()
    {
        
        return new RestockMail($order);
    }
}
