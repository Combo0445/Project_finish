@extends('layouts.app')

@section('title', 'เพิ่มคำแนะนำการดูแล')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4>เพิ่มคำแนะนำการดูแล</h4>
                </div>
                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('care_instructions.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="Date_CI">วันที่</label>
                                <input type="date" id="Date_CI" name="Date_CI" class="form-control"
                                    value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="Name_Elderly">ชื่อผู้สูงอายุ</label>
                                <input type="text" id="Name_Elderly" name="Name_Elderly" class="form-control"
                                    value="{{ $elderly->Name_Elderly }}" readonly>
                                <input type="hidden" name="ID_Elderly" value="{{ $elderly->ID_Elderly }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="Name_Doctor">ชื่อของนายแพทย์</label>
                                <input type="text" id="Name_Doctor" name="Name_Doctor" class="form-control"
                                    value="{{ Auth::user()->Name_User }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="Name_Staff">เลือกเจ้าหน้าที่ผู้รับผิดชอบ <span class="text-danger">*</span></label>
                                <select id="Name_Staff" name="Name_Staff" class="form-control" required>
                                    <option value="" disabled {{ !$reporter ? 'selected' : '' }}>-- เลือกเจ้าหน้าที่ --</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->Name_User }}" {{ $reporter === $staff->Name_User ? 'selected' : '' }}>
                                            {{ $staff->Name_User }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">เลือกเจ้าหน้าที่เพื่อส่งการแจ้งเตือน</small>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="Care_instructions">ข้อมูลคำแนะนำการดูแล <span class="text-danger">*</span></label>
                            
                            <!-- Quick Templates -->
                            <div class="mb-2">
                                <span class="text-sm text-secondary me-2">เพิ่มคำแนะนำด่วน:</span>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill mb-1 template-btn" data-text="- แนะนำให้ดื่มน้ำอย่างน้อยวันละ 8 แก้ว หรือ 1.5-2 ลิตร">การดื่มน้ำ</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill mb-1 template-btn" data-text="- ควรออกกำลังกายเบาๆ เช่น การเดิน ขยับแขนขา หรือเหยียดกล้ามเนื้อ วันละ 15-30 นาที">ออกกำลังกาย</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill mb-1 template-btn" data-text="- รับประทานอาหารอ่อน ย่อยง่าย รสไม่จัด เน้นผักและปลา">อาหาร</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill mb-1 template-btn" data-text="- พลิกตะแคงตัวผู้ป่วยทุกๆ 2 ชั่วโมง เพื่อป้องกันแผลกดทับ">ป้องกันแผลกดทับ</button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill mb-1 template-btn" data-text="- สังเกตอาการผิดปกติอย่างใกล้ชิด หากมีไข้ซึมลง หรือรับประทานอาหารได้น้อย ควรรีบไปพบแพทย์">เฝ้าระวังอาการ</button>
                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill mb-1" id="clear-btn">ล้างข้อความ</button>
                            </div>

                            <textarea id="Care_instructions" name="Care_instructions" class="form-control" rows="6"
                                placeholder="พิมพ์คำแนะนำการดูแลที่นี่..." required></textarea>
                        </div>


                        <div class="mt-4 mb-3">
                            <button type="submit" class="btn btn-success me-2">บันทึกคำแนะนำ</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- History Sidebar -->
        <div class="col-md-4">
            <div class="card mb-4 h-100">
                <div class="card-header pb-0 border-bottom">
                    <h6><i class="fas fa-history text-info me-2"></i>ประวัติคำแนะนำการดูแล (ล่าสุด)</h6>
                </div>
                <div class="card-body p-3 overflow-auto" style="max-height: 500px;">
                    @if(isset($history) && $history->count() > 0)
                        <div class="timeline timeline-one-side">
                            @foreach($history as $item)
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="fas fa-user-md text-success text-gradient"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">{{ \Carbon\Carbon::parse($item->Date_CI)->format('d/m/Y') }}</h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">โดย {{ $item->Name_Doctor }}</p>
                                        <p class="text-sm mt-3 mb-2" style="white-space: pre-line;">{{ $item->Care_instructions }}</p>
                                        <span class="badge badge-sm bg-gradient-{{ $item->Confirm ? 'success' : 'warning' }}">
                                            {{ $item->Confirm ? 'สตาฟยืนยันแล้ว' : 'รอสตาฟยืนยัน' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-medical-alt fa-3x text-light mb-3"></i>
                            <p class="text-muted">ยังไม่มีประวัติคำแนะนำการดูแลสำหรับผู้ป่วยรายนี้</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('Care_instructions');
        const templateBtns = document.querySelectorAll('.template-btn');
        const clearBtn = document.getElementById('clear-btn');

        templateBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const textToAdd = this.getAttribute('data-text');
                const currentVal = textarea.value;
                
                if (currentVal.trim() === '') {
                    textarea.value = textToAdd;
                } else {
                    textarea.value = currentVal + '\n' + textToAdd;
                }
                
                // Focus and scroll to bottom
                textarea.focus();
                textarea.scrollTop = textarea.scrollHeight;
            });
        });

        clearBtn.addEventListener('click', function() {
            textarea.value = '';
            textarea.focus();
        });

    });
</script>
@endpush