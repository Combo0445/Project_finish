<?php

namespace App\Http\Controllers;

use App\Models\BarthelAdl;
use App\Models\CareGiver;
use App\Models\News;
use App\Models\Slider;

class PageController extends Controller
{
    public function up()
    {
        return response('OK', 200);
    }

    public function home()
    {
        $sliders = Slider::orderBy('id', 'desc')->get();
        $news = News::orderBy('id', 'desc')->get();

        $adlAssessmentCount = BarthelAdl::count();
        $cgAssessmentCount = CareGiver::count();

        $adlGroupCounts = [
            'กลุ่มติดสังคม' => BarthelAdl::where('Group_ADL', 'กลุ่มติดสังคม')->count(),
            'กลุ่มติดบ้าน' => BarthelAdl::where('Group_ADL', 'กลุ่มติดบ้าน')->count(),
            'กลุ่มติดเตียง' => BarthelAdl::where('Group_ADL', 'กลุ่มติดเตียง')->count(),
        ];

        return view('welcome', compact('sliders', 'news', 'adlAssessmentCount', 'cgAssessmentCount', 'adlGroupCounts'));
    }

    public function newsShow($id)
    {
        $newsItem = News::findOrFail($id);

        return view('layout.newshow', compact('newsItem'));
    }

    public function contact()
    {
        return view('layout.contact');
    }

    public function about()
    {
        return view('layout.about');
    }

    public function history()
    {
        return view('layout.history');
    }

    public function vision()
    {
        return view('layout.vision');
    }

    public function error()
    {
        return view('error.error');
    }
}
