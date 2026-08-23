@extends('layouts.app')

@section('title', 'ประวัติ ' . $elderly->Name_Elderly)

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <x-card>
                <div class="d-flex flex-column flex-md-row align-items-center gap-4 p-3">
                    <img src="{{ $elderly->image_url }}" alt="{{ $elderly->Name_Elderly }}"
                        class="avatar avatar-xl rounded-circle" style="width: 100px; height: 100px;">
                    <div class="flex-grow-1 text-center text-md-start">
                        <h4 class="mb-1">{{ $elderly->Name_Elderly }}</h4>
                        <p class="text-secondary mb-0">
                            {{ $elderly->Gender }} · อายุ {{ \Carbon\Carbon::parse($elderly->Birthday)->age }} ปี ·
                            โทร {{ $elderly->Phone_Elderly }}
                        </p>
                        <p class="text-secondary mb-0">{{ $elderly->Address }}</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm mb-0">
                        <i class="fas fa-arrow-left me-1"></i> กลับหน้าจัดการผู้สูงอายุ
                    </a>
                </div>
            </x-card>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <x-card class="h-100">
                <div class="p-3">
                    <h6 class="text-secondary text-xs text-uppercase mb-2">สถานะ ADL ล่าสุด</h6>
                    @if ($elderly->barthel_adl)
                        <h5 class="mb-1">{{ $elderly->barthel_adl->Group_ADL }}</h5>
                        <p class="text-secondary mb-0">{{ $elderly->barthel_adl->Score_ADL }} คะแนน</p>
                    @else
                        <p class="text-muted mb-0">ยังไม่ได้ประเมิน</p>
                    @endif
                </div>
            </x-card>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <x-card class="h-100">
                <div class="p-3">
                    <h6 class="text-secondary text-xs text-uppercase mb-2">ข้อมูล CG ล่าสุด</h6>
                    @if ($elderly->care_giver)
                        <p class="mb-1">น้ำหนัก {{ $elderly->care_giver->Weight }} กก. · ส่วนสูง {{ $elderly->care_giver->Height }} ซม.</p>
                        <p class="text-secondary mb-0">{{ \Carbon\Carbon::parse($elderly->care_giver->Date_CG)->format('d/m/Y') }}</p>
                    @else
                        <p class="text-muted mb-0">ยังไม่มีข้อมูล</p>
                    @endif
                </div>
            </x-card>
        </div>
        <div class="col-md-4">
            <x-card class="h-100">
                <div class="p-3">
                    <h6 class="text-secondary text-xs text-uppercase mb-2">สถานะ TAI ล่าสุด</h6>
                    @if ($elderly->score_tai)
                        <h5 class="mb-0">{{ $elderly->score_tai->group }}</h5>
                    @else
                        <p class="text-muted mb-0">ยังไม่ได้ประเมิน</p>
                    @endif
                </div>
            </x-card>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <x-card>
                <x-slot name="header">
                    <h6 class="mb-0">ประวัติการประเมิน ADL ({{ $elderly->adl_history->count() }} ครั้ง)</h6>
                </x-slot>
                @if ($elderly->adl_history->isEmpty())
                    <p class="text-muted p-3 mb-0">ยังไม่มีประวัติการประเมิน ADL</p>
                @else
                    <div class="p-3">
                        <canvas id="adlHistoryChart" style="max-height: 220px;"></canvas>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">วันที่</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">คะแนน ADL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">กลุ่ม ADL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($elderly->adl_history as $adl)
                                    <tr>
                                        <td class="text-center">{{ $adl->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ $adl->Score_ADL }}</td>
                                        <td class="text-center">{{ $adl->Group_ADL }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <x-card>
                <x-slot name="header">
                    <h6 class="mb-0">ประวัติรายงานผู้ดูแล (CG) ({{ $elderly->cg_history->count() }} ครั้ง)</h6>
                </x-slot>
                @if ($elderly->cg_history->isEmpty())
                    <p class="text-muted p-3 mb-0">ยังไม่มีประวัติรายงาน CG</p>
                @else
                    <div class="p-3">
                        <canvas id="cgWeightChart" style="max-height: 220px;"></canvas>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">วันที่</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">น้ำหนัก</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">ส่วนสูง</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">สัญญาณชีพ</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">ผู้รายงาน</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($elderly->cg_history as $cg)
                                    <tr>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($cg->Date_CG)->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ $cg->Weight }} กก.</td>
                                        <td class="text-center">{{ $cg->Height }} ซม.</td>
                                        <td class="text-center">{{ $cg->Vital_signs }}</td>
                                        <td class="text-center">{{ $cg->Reporter }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <x-card>
                <x-slot name="header">
                    <h6 class="mb-0">ประวัติการประเมิน TAI ({{ $elderly->tai_history->count() }} ครั้ง)</h6>
                </x-slot>
                @if ($elderly->tai_history->isEmpty())
                    <p class="text-muted p-3 mb-0">ยังไม่มีประวัติการประเมิน TAI</p>
                @else
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">วันที่</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">กลุ่ม</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Mobility</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Confuse</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Feed</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Toilet</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($elderly->tai_history as $tai)
                                    <tr>
                                        <td class="text-center">{{ $tai->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ $tai->group }}</td>
                                        <td class="text-center">{{ $tai->mobility }}</td>
                                        <td class="text-center">{{ $tai->confuse }}</td>
                                        <td class="text-center">{{ $tai->feed }}</td>
                                        <td class="text-center">{{ $tai->toilet }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const adlHistory = @json($elderly->adl_history->sortBy('created_at')->values());
            const adlCanvas = document.getElementById('adlHistoryChart');
            if (adlCanvas && adlHistory.length > 0) {
                new Chart(adlCanvas, {
                    type: 'line',
                    data: {
                        labels: adlHistory.map(a => a.created_at.split('T')[0]),
                        datasets: [{
                            label: 'คะแนน ADL',
                            data: adlHistory.map(a => a.Score_ADL),
                            borderColor: 'rgba(94, 114, 228, 1)',
                            backgroundColor: 'rgba(94, 114, 228, 0.1)',
                            tension: 0.3,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                    }
                });
            }

            const cgHistory = @json($elderly->cg_history->sortBy('Date_CG')->values());
            const cgCanvas = document.getElementById('cgWeightChart');
            if (cgCanvas && cgHistory.length > 0) {
                new Chart(cgCanvas, {
                    type: 'line',
                    data: {
                        labels: cgHistory.map(c => c.Date_CG),
                        datasets: [{
                            label: 'น้ำหนัก (กก.)',
                            data: cgHistory.map(c => c.Weight),
                            borderColor: 'rgba(45, 206, 137, 1)',
                            backgroundColor: 'rgba(45, 206, 137, 0.1)',
                            tension: 0.3,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                    }
                });
            }
        });
    </script>
@endpush
