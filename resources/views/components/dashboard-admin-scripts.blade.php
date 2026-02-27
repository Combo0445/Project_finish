<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // System now uses server-side PDF generation for consistency
        document.getElementById('generate-pdf')?.addEventListener('click', function () {
            window.open("{{ route('admin.report-user') }}", '_blank');
        });
    });
</script>