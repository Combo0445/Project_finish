<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScoreTAI;
use App\Models\Elderly;
use App\Models\User;

class TAIController extends Controller
{
    public function index(Request $request)
    {
        $tai = ScoreTAI::with(['elderly', 'user'])->get();

        return view('staff.TAI.ShowTAI', compact('tai'));
    }

    public function create() {

    }

    public function auto_store(Request $request) {
        
    }
}
