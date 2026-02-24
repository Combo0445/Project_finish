<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\News;
use App\Models\NewsImage;
use App\Models\Slider;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function showAdmin()
    {
        $users = User::all();
        return view('admin.dashboard-admin', compact('users'));
    }

    public function registerUser()
    {
        $personnelTypes = Personnel::where('Type_Personnel', '!=', 'Admin')->get();
        return view('admin.register-user', compact('personnelTypes'));
    }

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

    public function ShowlayoutAdmin()
    {
        $sliders = Slider::all();
        $news = News::all();
        $visitorCount = 12344865; // ตัวอย่างข้อมูล
        $adlAssessmentCount = 6789; // ตัวอย่างข้อมูล
        $cgAssessmentCount = 6548;
        return view('admin.layout-admin', compact('sliders', 'news', 'visitorCount', 'adlAssessmentCount', 'cgAssessmentCount'));
    }

    // News Management
    public function storeNews(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
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

            return redirect()->route('admin.layout-admin')->with('success', 'เพิ่มข่าวเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข่าว: ' . $e->getMessage());
        }
    }

    public function updateNews(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการแก้ไขข่าว: ' . $e->getMessage());
        }
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
