<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\News;
use App\Models\NewsImage;
use App\Models\Slider;
use App\Models\Personnel;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function registerUser()
    {
        $personnelTypes = [
            ['id' => 1, 'Type_Personnel' => 'Admin'],
            ['id' => 2, 'Type_Personnel' => 'Staff'],
            ['id' => 3, 'Type_Personnel' => 'Doctor'],
        ];
        return view('admin.register-user', compact('personnelTypes'));
    }

    public function submitUser(UserRequest $request)
    {

        $personnelMap = [
            1 => 'Admin',
            2 => 'Staff',
            3 => 'Doctor',
        ];

        $personnelId = (int) $request->Type_Personnel;
        if (!isset($personnelMap[$personnelId])) {
            return redirect()->route('user.register')->with('error', 'ประเภทบุคลากรที่เลือกไม่ถูกต้อง');
        }

        $user = new User();
        $user->Username = $request->Username;
        $user->Email = $request->Email;
        $user->Password = Hash::make($request->Password);
        $user->ID_Personnel = $personnelId;
        $user->Type_Personnel = $personnelMap[$personnelId];
        $user->Name_User = $request->Name_User;
        $user->Address = '';
        $user->Phone = '';
        $user->line_token = $request->line_token;

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
                $user->Type_Doctor = $request->Type_Elderly ?? '';
                break;
            default:
                $user->Image_User = '';
                break;
        }

        $user->save();

        return redirect()->route('dashboard')->with('success', 'ลงทะเบียนผู้ใช้เรียบร้อยแล้ว!');
    }

    public function deleteUser($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('dashboard')->with('error', 'ไม่สามารถลบบัญชีของตนเองได้');
        }

        $user = User::findOrFail($id);

        if (auth()->user()->Type_Personnel === $user->Type_Personnel) {
            return redirect()->route('dashboard')->with('error', 'ไม่สามารถลบผู้ใช้งานระดับเดียวกันได้');
        }

        $user->delete();
        return redirect()->route('dashboard')->with('success', 'ลบผู้ใช้เรียบร้อยแล้ว!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        // Keep track of old type for safety check
        $oldType = $user->Type_Personnel;
        $newType = $request->Type_Personnel;

        // Safety check: Don't allow changing the last admin's role
        if ($oldType === 'Admin' && $newType !== 'Admin') {
            $adminCount = User::where('Type_Personnel', 'Admin')->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'ไม่สามารถเปลี่ยนประเภทของผู้ดูแลระบบคนสุดท้ายได้');
            }
        }

        $user->Username = $request->Username;
        $user->Email = $request->Email;
        $user->Name_User = $request->filled('Name_User') ? $request->Name_User : $user->Name_User;
        $user->Phone = $request->filled('Phone') ? $request->Phone : $user->Phone;
        $user->Address = $request->filled('Address') ? $request->Address : $user->Address;
        $user->line_token = $request->line_token;

        if ($request->filled('Password')) {
            $user->Password = Hash::make($request->Password);
        }

        $personnelMap = [
            'Admin' => 1,
            'Staff' => 2,
            'Doctor' => 3,
        ];

        if (isset($personnelMap[$newType])) {
            $user->Type_Personnel = $newType;
            $user->ID_Personnel = $personnelMap[$newType];

            if ($newType === 'Doctor') {
                $user->Type_Doctor = $request->Type_Doctor ?? '';
            } else {
                $user->Type_Doctor = '';
            }

            // Update default image if needed
            $defaultImages = [
                'Admin' => 'images-user/Admin.jpg',
                'Staff' => 'images-user/Staff.png',
                'Doctor' => 'images-user/Doctor.png',
            ];

            // If the user currently has a default image from a previous role, update it to the new role's default
            if (in_array($user->Image_User, array_values($defaultImages))) {
                $user->Image_User = $defaultImages[$newType];
            }
        }

        $user->save();

        return redirect()->route('dashboard')->with('success', 'แก้ไขข้อมูลผู้ใช้เรียบร้อยแล้ว!');
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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
