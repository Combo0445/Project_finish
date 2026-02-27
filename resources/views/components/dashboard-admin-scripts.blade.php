<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<script>
    <script>
    // System now uses server-side PDF generation for consistency (18pt font)
    // The button #generate-pdf should be converted to an <a> link in the parent view if possible,
            // but for now we can redirect it via JS to the existing route.
            document.getElementById('generate-pdf')?.addEventListener('click', function () {
                window.open("{{ route('report.user') }}", '_blank');
    });
</script>
</script>