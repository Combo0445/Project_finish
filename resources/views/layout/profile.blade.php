@extends('layouts.app')

@section('title', $user->Username . ' โปรไฟล์')

@push('styles')
    <style>
        .profile-header {
            background-color: #4267b2;
            color: #fff;
            padding: 40px 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }

        .profile-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .profile-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            border: 5px solid #fff;
            margin-bottom: 15px;
            transition: transform 0.3s;
        }

        .profile-container img:hover {
            transform: scale(1.1);
        }

        .profile-container h1 {
            margin: 10px 0 5px;
            font-size: 2.5em;
        }

        .profile-container p {
            margin: 5px 0;
            font-size: 1.2em;
        }

        .profile-container h2 {
            margin-top: 20px;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .profile-container .profile-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: left;
        }

        .profile-container .profile-details p {
            width: 100%;
            max-width: 400px;
            margin: 10px 0;
            font-size: 1.1em;
        }

        .profile-container .edit-button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #4267b2;
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-size: 1.1em;
        }

        .profile-container .edit-button:hover {
            background-color: #365899;
            transform: translateY(-3px);
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="profile-container">
                <img src="{{ $user->image_url }}" alt="Profile Image">
                <h3>{{ $user->Name_User }}</h3>
                <p>
                    @if($user->Type_Personnel === 'Staff')
                        เจ้าหน้าที่
                    @elseif($user->Type_Personnel === 'Doctor')
                        นายแพทย์
                    @else
                        {{ $user->Type_Personnel }}
                    @endif
                </p>

                <div class="profile-details">
                    <p><strong>ชื่อผู้ใช้:</strong> {{ $user->Username }}</p>
                    <p><strong>อีเมล:</strong> {{ $user->Email }}</p>
                    <p><strong>ที่อยู่:</strong> {{ $user->Address }}</p>
                    <p><strong>เบอร์โทร:</strong> {{ $user->Phone }}</p>
                    @if($user->Type_Personnel === 'Doctor')
                        <p><strong>ประเภทผู้สูงอายุที่ดูแล:</strong> {{ $user->Type_Doctor }}</p>
                    @endif
                </div>
                <a href="{{ route('edit-profile') }}" class="edit-button">แก้ไขโปรไฟล์</a>
            </div>
        </div>
    </div>
@endsection