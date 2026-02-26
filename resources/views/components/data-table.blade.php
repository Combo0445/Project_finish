@props([
    'id' => 'dataTable-' . uniqid(),
    'headers' => [],
    'useDataTable' => true,
    'responsive' => true,
    'striped' => false,
    'hover' => true,
])

<div class="{{ $responsive ? 'table-responsive' : '' }} p-0">
    <table id="{{ $id }}" class="table align-items-center mb-0 {{ $striped ? 'table-striped' : '' }} {{ $hover ? 'table-hover' : '' }}">
        <thead class="bg-gray-100">
            <tr>
                @foreach($headers as $header)
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 {{ $header['class'] ?? 'text-center' }}">
                        {{ is_array($header) ? $header['label'] : $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>

@if($useDataTable)
    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#{{ $id }}').DataTable({
                    "language": {
                        "paginate": {
                            "previous": "<",
                            "next": ">"
                        },
                        "search": "ค้นหา : ",
                        "lengthMenu": "แสดง _MENU_ รายการ",
                        "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                        "infoEmpty": "ไม่มีข้อมูลแสดง",
                        "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                        "zeroRecords": "ไม่พบข้อมูลที่ค้นหา"
                    },
                    "dom": '<"row align-items-center"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row align-items-center"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7 d-flex justify-content-end"p>>',
                    "pageLength": 10,
                    "order": @json($attributes->get('order', [[0, 'asc']])),
                });
            });
        </script>
    @endpush
@endif

<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5em 1em;
        margin-left: 2px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #67748e !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #5e72e4;
        color: #fff !important;
        border-color: #5e72e4;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d2d6da;
        border-radius: 0.5rem;
        padding: 0.4rem 0.75rem;
        margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d2d6da;
        border-radius: 0.5rem;
        padding: 0.4rem 1.5rem 0.4rem 0.75rem;
    }
</style>
