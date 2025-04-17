{{-- resources/views/staff/PerformanceReport/EditPerformanceReport.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>แก้ไข Performance Report</title>
  <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet"/>
  <style>
    body { font-family: Arial; background: #f4f4f4; padding:20px; }
    .container { max-width:800px; margin:0 auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
    .form-group { margin-bottom:15px; }
    label { font-weight:bold; display:block; margin-bottom:5px; }
    input, select, textarea { width:100%; padding:8px; box-sizing:border-box; }
    .btn { padding:10px 20px; color:#fff; border:none; border-radius:5px; cursor:pointer; text-decoration:none; }
    .btn-success { background:#28a745; }
    .btn-danger  { background:#dc3545; }
  </style>
</head>
<body>
  @include('layout.nav')

  <div class="container mt-5">
    <h4 class="text-center mb-4">แก้ไข Performance Report</h4>

    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul></div>
    @endif

    <form action="{{ route('performanceReport.update', $performanceReport->id) }}" method="POST">
      @csrf @method('PUT')

      {{-- 1. Elderly --}}
      <div class="form-group">
        <label>ผู้สูงอายุ</label>
        <select id="ID_Elderly" name="ID_Elderly" class="form-control" required>
          <option value="">-- เลือกผู้สูงอายุ --</option>
          @foreach($elderlys as $e)
            <option value="{{ $e->ID_Elderly }}"
              {{ $performanceReport->ID_Elderly==$e->ID_Elderly?'selected':'' }}>
              {{ $e->Name_Elderly }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- 2. ADL ล่าสุด --}}
      <div class="form-group">
        <label>ADL (ล่าสุด)</label>
        <select id="ID_Adl" name="ID_Adl" class="form-control" required>
          <option value="{{ $performanceReport->ID_Adl }}" selected>
            {{ optional($performanceReport->adl)->Score_ADL ?? '-' }}
          </option>
        </select>
      </div>

      {{-- 3. TAI ล่าสุด --}}
      <div class="form-group">
        <label>TAI (ล่าสุด)</label>
        <select id="ID_Tai" name="ID_Tai" class="form-control" required>
          <option value="{{ $performanceReport->ID_Tai }}" selected>
            TAI #{{ optional($performanceReport->tai)->id ?? '-' }}
          </option>
        </select>
      </div>

      {{-- 4. CG ล่าสุด --}}
      <div class="form-group">
        <label>CG (ล่าสุด)</label>
        <select id="ID_Cg" name="ID_CG" class="form-control" required>
          <option value="{{ $performanceReport->ID_CG }}" selected>
            {{ optional($performanceReport->caregiver)->Name_CG ?? '-' }}
          </option>
        </select>
      </div>

      {{-- 5. ผู้สร้างรายงาน --}}
      <div class="form-group">
        <label>ผู้สร้างรายงาน</label>
        <input type="text" class="form-control" value="{{ auth()->user()->Name_User }}" disabled>
        <input type="hidden" name="ID_User" value="{{ auth()->id() }}">
      </div>

      {{-- 6. วันที่ --}}
      <div class="form-group">
        <label>วันที่</label>
        <input type="datetime-local" name="Date" class="form-control"
          value="{{ \Carbon\Carbon::parse($performanceReport->Date)->format('Y-m-d\TH:i') }}" required>
      </div>

      {{-- 7. สภาวะ --}}
      <div class="form-group">
        <label>สภาวะ</label>
        <input type="text" name="State" class="form-control"
               value="{{ $performanceReport->State }}" required>
      </div>

      {{-- 8. กิจกรรม --}}
      <div class="form-group">
        <label>กิจกรรม</label>
        <textarea name="Activity" class="form-control" rows="2" required>{{ $performanceReport->Activity }}</textarea>
      </div>

      {{-- 9. ปัญหาที่พบ --}}
      <div class="form-group">
        <label>ปัญหาที่พบ</label>
        <textarea name="Problems" class="form-control" rows="2">{{ $performanceReport->Problems }}</textarea>
      </div>

      {{-- 10. เกี่ยวข้องเป็น --}}
      <div class="form-group">
        <label>เกี่ยวข้องเป็น</label>
        <input type="text" name="Relative" class="form-control"
               value="{{ $performanceReport->Relative }}">
      </div>

      {{-- 11. หมายเหตุ --}}
      <div class="form-group">
        <label>หมายเหตุ</label>
        <textarea name="Note" class="form-control" rows="2">{{ $performanceReport->Note }}</textarea>
      </div>

      <div class="text-right">
        <button type="submit" class="btn btn-success">บันทึก</button>
        <a href="{{ route('performanceReport.index') }}" class="btn btn-danger">ยกเลิก</a>
      </div>
    </form>
  </div>

  {{-- AJAX โหลดข้อมูล ADL, TAI, CG ล่าสุด ได้เหมือน Add --}}
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    $('#ID_Elderly').on('change', function(){
      let id = $(this).val();
      if(!id) return;

      $('#ID_Adl,#ID_Tai,#ID_Cg').html('<option>กำลังโหลด…</option>');

      $.getJSON("{{ url('performance-report/data') }}/" + id, function(res){
        $('#ID_Adl').html(
            res.adl
              ? `<option value="${res.adl.id}" selected>${res.adl.label}</option>`
              : '<option value="">ไม่พบ ADL</option>'
        );
        $('#ID_Tai').html(
            res.tai
              ? `<option value="${res.tai.id}" selected>${res.tai.label}</option>`
              : '<option value="">ไม่พบ TAI</option>'
        );
        $('#ID_Cg').html(
            res.cg
              ? `<option value="${res.cg.id}" selected>${res.cg.label}</option>`
              : '<option value="">ไม่พบ CG</option>'
        );
      });
    });
  </script>
</body>
</html>
