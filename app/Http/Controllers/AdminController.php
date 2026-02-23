<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\News;
use App\Models\NewsImage;
use App\Models\Slider;
use App\Models\Personnel;
use App\Models\BarthelAdl;
use App\Models\CareGiver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * AdminController handles administrative tasks such as user management,
 * news updates, and slider management.
 */
class AdminController extends Controller
{
    /**
     * Display the admin dashboard with user assessment counts.
     */
    public function showAdmin()
    {
        $users = User::withCount('barthel_adls')->get();
        return view('admin.dashboard-admin', compact('users'));
    }


    /**
     * Show the user registration form.
     */
    public function registerUser()
    {
        $personnelTypes = Personnel::all();
        return view('admin.register-user', compact('personnelTypes'));
    }

    /**
     * Process the user registration form submission.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitUser(Request $request)
    {
        $request->validate([
            'Username' => 'required|unique:users,Username|max:255',
            'Email' => 'required|email|max:255|unique:users,Email',
            'Password' => 'required',
            'Type_Personnel' => 'required',
            'Type_Elderly' => 'nullable|string'
        ]);

        $personnel = Personnel::find($request->Type_Personnel);

        if (!$personnel) {
            return redirect()->route('user.register')->with('error', 'ประเภทบุคลากรที่เลือกไม่ถูกต้อง');
        }

        $user = new User();
        $user->Username = $request->Username;
        $user->Email = $request->Email;
        $user->Password = Hash::make($request->Password);
        $user->ID_Personnel = $personnel->ID_Personnel;
        $user->Type_Personnel = $personnel->Type_Personnel;
        $user->Name_User = '';
        $user->Address = '';
        $user->Phone = '';

        if ($user->Type_Personnel == 'Doctor') {
            $user->Type_Doctor = $request->Type_Elderly;
        } else {
            $user->Type_Doctor = '';
        }

        // Set default profile image based on user type
        switch ($user->Type_Personnel) {
            case 'Admin':
                $user->Image_User = 'images-user/Admin.jpg';
                break;
            case 'Staff':
                $user->Image_User = 'images-user/Staff.png';
                break;
            case 'Doctor':
                $user->Image_User = 'images-user/Doctor.png';
                break;
            default:
                $user->Image_User = '';
                break;
        }

        $user->save();

        return redirect()->route('user.register')->with('success', 'ลงทะเบียนผู้ใช้เรียบร้อยแล้ว!');
    }

    /**
     * Delete a user by ID, preventing admin deletion.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user->Type_Personnel !== 'Admin') {
            $user->delete();
            return redirect()->route('admin.dashboard')->with('success', 'ลบผู้ใช้เรียบร้อยแล้ว!');
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'บัญชีผู้ดูแลระบบไม่สามารถลบได้');
        }
    }

    /**
     * Show the layout management page for admins.
     */
    public function ShowlayoutAdmin()
    {
        $sliders = Slider::all();
        $news = News::all();
        $visitorCount = 12344865; // ตัวอย่างข้อมูล (เนื่องจากยังไม่มีระบบนับจำนวนผู้เข้าชมจริง)
        $adlAssessmentCount = BarthelAdl::count();
        $cgAssessmentCount = CareGiver::count();
        return view('admin.layout-admin', compact('sliders', 'news', 'visitorCount', 'adlAssessmentCount', 'cgAssessmentCount'));
    }

    /**
     * Store a new news item with optional images.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeNews(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image'
        ]);

        $news = new News($request->only(['title', 'content']));
        $news->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('news_images', 'public');
                NewsImage::create([
                    'news_id' => $news->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.layout-admin')->with('success', 'บัญชีผู้ดูแลระบบไม่สามารถลบได้');
    }

    public function updateNews(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image'
        ]);

        $news = News::findOrFail($id);
        $news->fill($request->only(['title', 'content']));
        $news->save();

        // ถ้ามีการอัปโหลดรูปภาพใหม่ ให้ลบรูปภาพเก่าออก
        if ($request->hasFile('images')) {
            // ลบรูปภาพเก่าทั้งหมดที่เกี่ยวข้องกับข่าวนี้
            foreach ($news->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }

            // เพิ่มรูปภาพใหม่เข้าไปในฐานข้อมูลและที่เก็บไฟล์
            foreach ($request->file('images') as $image) {
                $path = $image->store('news_images', 'public');
                NewsImage::create([
                    'news_id' => $news->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.layout-admin')->with('success', 'อัปเดตข่าวสารสำเร็จแล้ว');
    }


    public function destroyNews($id)
    {
        $news = News::findOrFail($id);

        // ลบรูปภาพที่เกี่ยวข้องทั้งหมดในตาราง NewsImage
        foreach ($news->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // ลบข่าว
        $news->delete();

        return redirect()->route('admin.layout-admin')->with('success', 'ลบข่าวเรียบร้อยแล้ว');
    }


    // Slider Management
    public function storeSlider(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        $slider = new Slider();
        $slider->fill($request->only(['image']));
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('slider_images', 'public');
            $slider->image = $path;
        }

        $slider->save();

        return redirect()->route('admin.layout-admin')->with('success', 'เพิ่มภาพเลื่อนเรียบร้อยแล้ว');
    }

    public function updateSlider(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image'
        ]);

        $slider = Slider::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $path = $request->file('image')->store('slider_images', 'public');
            $slider->image = $path;
        }

        $slider->save();

        return redirect()->route('admin.layout-admin')->with('success', 'อัปเดตรูปภาพตัวเลื่อนเรียบร้อยแล้ว');
    }

    public function destroySlider($id)
    {
        $slider = Slider::findOrFail($id);

        // Delete image
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.layout-admin')->with('success', 'ลบภาพเลื่อนเรียบร้อยแล้ว');
    }

    public function ReportUser()
    {
        $users = User::all();

        return view('admin.report-admin', compact('users'));
    }
}
