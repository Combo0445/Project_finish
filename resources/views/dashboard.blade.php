@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $role = auth()->user()->Type_Personnel;
    @endphp

    {{-- 1. ADMIN DASHBOARD --}}
    @if($role === 'Admin')
        <x-card>
            <x-slot name="header">
                <h4 class="mb-0">จัดการข้อมูลผู้ใช้</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('user.register') }}" class="btn btn-primary d-flex align-items-center">
                        <i class="fas fa-plus me-2"></i> สร้างบัญชี
                    </a>
                    <button id="generate-pdf" class="btn btn-outline-success">
                        <i class="fas fa-print me-2"></i> พิมพ์รายงาน
                    </button>
                </div>
            </x-slot>

            <x-data-table id="userTable" :headers="[
                    ['label' => 'รูป', 'class' => 'text-center'],
                    ['label' => 'ชื่อ - นามสกุล', 'class' => 'text-center'],
                    ['label' => 'ชื่อผู้ใช้', 'class' => 'text-center'],
                    ['label' => 'ประเภทของผู้ใช้', 'class' => 'text-center'],
                    ['label' => 'ประเภทที่แพทย์ดูแล', 'class' => 'text-center'],
                    ['label' => 'อีเมล', 'class' => 'text-center'],
                    ['label' => 'Actions', 'class' => 'text-center']
                ]">
                @foreach ($users as $u)
                    <tr>
                        <td class="text-center">
                            @php
                                $avatar = 'Logo.png';
                                if ($u->Image_User) {
                                    $avatar = $u->Image_User;
                                } else {
                                    $avatarMap = [
                                        'Admin' => 'images-user/Admin.jpg',
                                        'Staff' => 'images-user/Staff.png',
                                        'Doctor' => 'images-user/Doctor.png',
                                    ];
                                    $avatar = $avatarMap[$u->Type_Personnel] ?? 'Logo.png';
                                }
                            @endphp
                            <img src="{{ url($avatar) }}" alt="Avatar" class="avatar avatar-sm me-3 rounded-circle">
                        </td>
                        <td class="text-center">{{ $u->Name_User ?: 'ไม่มีข้อมูล' }}</td>
                        <td class="text-center">{{ $u->Username ?: 'ไม่มีข้อมูล' }}</td>
                        <td class="text-center">
                            <span
                                class="badge badge-sm bg-gradient-{{ $u->Type_Personnel == 'Admin' ? 'primary' : ($u->Type_Personnel == 'Staff' ? 'info' : 'success') }}">
                                @php $roles = ['Admin' => 'แอดมิน', 'Staff' => 'เจ้าหน้าที่', 'Doctor' => 'แพทย์']; @endphp
                                {{ $roles[$u->Type_Personnel] ?? $u->Type_Personnel }}
                            </span>
                        </td>
                        <td class="text-center">{{ $u->Type_Doctor ?: '-' }}</td>
                        <td class="text-center">{{ $u->Email ?: 'ไม่มีข้อมูล' }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('user.edit', $u->ID_User) }}" class="btn btn-link text-dark p-0 me-3"
                                    title="แก้ไข">
                                    <i class="fas fa-edit text-warning"></i>
                                </a>
                                @if ($u->Type_Personnel !== 'Admin' || auth()->id() !== $u->ID_User)
                                    <button onclick="confirmDelete('{{ $u->ID_User }}')" class="btn btn-link text-dark p-0" title="ลบ">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                    <form id="delete-form-{{ $u->ID_User }}" action="{{ route('user.delete', $u->ID_User) }}"
                                        method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>
        </x-card>

        {{-- 2. STAFF DASHBOARD --}}
    @elseif($role === 'Staff')
        <div class="row">
            <div class="col-12 mb-4">
                <x-card>
                    <x-slot name="header">
                        <h4 class="mb-0">ข้อมูลประวัติส่วนตัวของผู้สูงอายุ</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('add-elderly') }}" class="btn btn-primary d-flex align-items-center">
                                <i class="fas fa-plus me-2"></i> เพิ่มผู้สูงอายุ
                            </a>
                            <a href="{{ route('elderly-report') }}" class="btn btn-outline-success">
                                <i class="fas fa-print me-2"></i> พิมพ์รายงานสรุป
                            </a>
                        </div>
                    </x-slot>

                    <x-data-table id="elderlyTable" :headers="[
                    ['label' => 'รูป', 'class' => 'text-center'],
                    ['label' => 'ชื่อ-นามสกุล', 'class' => 'text-center'],
                    ['label' => 'อายุ', 'class' => 'text-center'],
                    ['label' => 'เบอร์โทร', 'class' => 'text-center'],
                    ['label' => 'ADL', 'class' => 'text-center'],
                    ['label' => 'CG', 'class' => 'text-center'],
                    ['label' => 'Actions', 'class' => 'text-center']
                ]">
                        @foreach ($elderlies as $elderly)
                            <tr>
                                <td class="text-center">
                                    <img src="{{ url($elderly->Image_Elderly ? 'storage/' . $elderly->Image_Elderly : 'storage/default.png') }}"
                                        alt="Elderly Image" class="avatar avatar-sm rounded-circle">
                                </td>
                                <td class="text-center">{{ $elderly->Name_Elderly }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($elderly->Birthday)->age }} ปี</td>
                                <td class="text-center">{{ $elderly->Phone_Elderly }}</td>
                                <td class="text-center">
                                    <span class="badge badge-sm bg-gradient-{{ $elderly->barthel_adl ? 'success' : 'secondary' }}">
                                        {{ $elderly->barthel_adl ? 'ประเมินแล้ว' : 'ยังไม่ประเมิน' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-sm bg-gradient-{{ $elderly->care_giver ? 'success' : 'secondary' }}">
                                        {{ $elderly->care_giver ? 'บันทึกแล้ว' : 'ยังไม่บันทึก' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('search-location', ['id' => $elderly->ID_Elderly]) }}" target="_blank"
                                            class="btn btn-link text-info p-0 me-3" title="ดูที่อยู่">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </a>
                                        <a href="{{ route('edit-elderly', ['id' => $elderly->ID_Elderly]) }}"
                                            class="btn btn-link text-warning p-0 me-3" title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-link text-danger p-0"
                                            onclick="confirmDeleteElderly('{{ $elderly->ID_Elderly }}')" title="ลบ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form action="{{ route('delete-elderly', ['id' => $elderly->ID_Elderly]) }}" method="POST"
                                            id="delete-elderly-form-{{ $elderly->ID_Elderly }}" style="display:none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-data-table>
                </x-card>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-card class="h-100">
                    <x-slot name="header">
                        <h6 class="mb-0">สถิติผู้สูงอายุตามช่วงอายุ</h6>
                    </x-slot>
                    <div class="row align-items-center">
                        <div class="col-12 mb-3"><canvas id="ageBarChart" style="max-height: 200px;"></canvas></div>
                        <div class="col-12"><canvas id="agePieChart" style="max-height: 200px;"></canvas></div>
                    </div>
                </x-card>
            </div>
            <div class="col-md-6">
                <x-card class="h-100">
                    <x-slot name="header">
                        <h6 class="mb-0">สัดส่วนกลุ่มสถานะสุขภาพ (ADL)</h6>
                    </x-slot>
                    <div class="row align-items-center">
                        <div class="col-12 mb-3"><canvas id="adlBarChart" style="max-height: 200px;"></canvas></div>
                        <div class="col-12"><canvas id="adlPieChart" style="max-height: 200px;"></canvas></div>
                    </div>
                </x-card>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <x-card>
                    <x-slot name="header">
                        <h6 class="mb-0"><i class="fas fa-map-marked-alt me-2 text-primary"></i>แผนที่แสดงที่ตั้งผู้สูงอายุ</h6>
                    </x-slot>
                    <div id="map" style="height: 500px; border-radius: 10px;"></div>
                </x-card>
            </div>
        </div>

        {{-- 3. DOCTOR DASHBOARD --}}
    @elseif($role === 'Doctor')
        <div class="row mb-4">
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <x-stat-card title="ผู้ป่วยในการดูแลทั้งหมด" :value="$stats['total_patients']" icon="users" color="primary" />
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <x-stat-card title="คำแนะนำที่รอการยืนยัน" :value="$stats['pending_ci']" icon="clock" color="warning" />
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <x-stat-card title="ได้ทำการประเมินแล้ววันนี้" :value="$stats['today_assessed']" icon="check-circle"
                    color="success" percentage="+100%" description="จากเมื่อวาน" />
            </div>
        </div>

        <x-card>
            <x-slot name="header">
                <h4 class="mb-0">รายชื่อผู้สูงอายุที่ต้องประเมิน</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('report.ci.confirm') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="fas fa-list-check me-2"></i> รายการที่รอยืนยัน
                    </a>
                </div>
            </x-slot>

            <x-data-table id="doctorTable" :headers="[
                    ['label' => 'รูป', 'class' => 'text-center'],
                    ['label' => 'ชื่อ-นามสกุล', 'class' => 'text-center'],
                    ['label' => 'อายุ', 'class' => 'text-center'],
                    ['label' => 'กลุ่ม ADL', 'class' => 'text-center'],
                    ['label' => 'การจัดการ', 'class' => 'text-center']
                ]">
                @foreach ($elderlys as $elderly)
                    <tr>
                        <td class="text-center">
                            <img src="{{ url($elderly->Image_Elderly ? 'storage/' . $elderly->Image_Elderly : 'storage/default.png') }}"
                                alt="Elderly" class="avatar avatar-sm rounded-circle">
                        </td>
                        <td class="text-center">{{ $elderly->Name_Elderly }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($elderly->Birthday)->age }} ปี</td>
                        <td class="text-center">
                            <span
                                class="badge badge-sm bg-gradient-{{ optional($elderly->barthel_adl)->Group_ADL === 'กลุ่มติดเตียง' ? 'danger' : (optional($elderly->barthel_adl)->Group_ADL === 'กลุ่มติดบ้าน' ? 'warning' : 'success') }}">
                                {{ optional($elderly->barthel_adl)->Group_ADL ?: 'ยังไม่ประเมิน' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-info me-2" data-bs-toggle="modal"
                                    data-bs-target="#adlModal-{{ $elderly->ID_Elderly }}">
                                    <i class="fas fa-file-medical"></i> ADL
                                </button>
                                <button class="btn btn-sm btn-outline-info me-2" data-bs-toggle="modal"
                                    data-bs-target="#cgDatesModal-{{ $elderly->ID_Elderly }}">
                                    <i class="fas fa-user-nurse"></i> CG
                                </button>
                                <a href="{{ route('ci.create', ['elderly_id' => $elderly->ID_Elderly]) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-comment-medical me-1"></i> ให้คำแนะนำ
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>
        </x-card>

        <x-dashboard-doctor-modals :elderlys="$elderlys" />
    @endif
@endsection

@push('scripts')
    @if($role === 'Admin')
        <x-dashboard-admin-scripts />
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "บัญชีนี้จะถูกลบถาวร!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ลบ',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#ea0606'
                }).then((result) => {
                    if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
                });
            }
        </script>
    @elseif($role === 'Staff')
        <x-dashboard-staff-scripts :ageGroups="$ageGroups" :adlGroups="$adlGroups" :elderlyLocations="$elderlyLocations" />
        <script>
            function confirmDeleteElderly(id) {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "ข้อมูลผู้สูงอายุนี้จะถูกลบถาวร!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ลบ',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#ea0606'
                }).then((result) => {
                    if (result.isConfirmed) document.getElementById('delete-elderly-form-' + id).submit();
                });
            }
        </script>
    @elseif($role === 'Doctor')
        <x-dashboard-doctor-scripts />
    @endif
@endpush