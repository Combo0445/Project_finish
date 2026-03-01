@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
    <x-card>
        <x-slot name="header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h4 class="mb-0">ระบบบันทึกประวัติการใช้งาน (Audit Logs)</h4>
                <form action="{{ route('audit-logs.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="ค้นหา Model หรือ Action" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-dark mb-0">ค้นหา</button>
                    @if(request('search'))
                        <a href="{{ route('audit-logs.index') }}" class="btn btn-sm btn-link mb-0 text-secondary">ล้าง</a>
                    @endif
                </form>
            </div>
        </x-slot>

        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">วัน-เวลา</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ผู้ใช้งาน</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">การกระทำ
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">เป้าหมาย
                            (Model)</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">IP
                            Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>
                                <div class="d-flex px-3 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ $log->created_at->format('d/m/Y H:i:s') }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">
                                    {{ $log->user ? $log->user->Name_User : 'System/Guest' }}</p>
                                <p class="text-xs text-secondary mb-0">{{ $log->user ? $log->user->Type_Personnel : '-' }}</p>
                            </td>
                            <td class="align-middle text-center text-sm">
                                @if($log->action == 'created')
                                    <span class="badge badge-sm bg-gradient-success">เพิ่มข้อมูล</span>
                                @elseif($log->action == 'updated')
                                    <span class="badge badge-sm bg-gradient-warning">แก้ไขข้อมูล</span>
                                @elseif($log->action == 'deleted')
                                    <span class="badge badge-sm bg-gradient-danger">ลบข้อมูล</span>
                                @else
                                    <span class="badge badge-sm bg-gradient-secondary">{{ $log->action }}</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                <span class="text-secondary text-xs font-weight-bold">
                                    {{ class_basename($log->model_type) }} (ID: {{ $log->model_id }})
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="text-secondary text-xs font-weight-bold">{{ $log->ip_address ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary text-sm py-4">ไม่พบประวัติการใช้งาน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 px-3">
                {{ $logs->links() }}
            </div>
        </div>
    </x-card>
@endsection