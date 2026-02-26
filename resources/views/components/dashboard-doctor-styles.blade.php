<style>
    .container {
        margin-top: 20px;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-xl {
        max-width: 75% !important;
    }

    .align-items-center .btn-adl {
        background-color: #17a2b8;
        /* สีฟ้าอ่อน */
        border-color: #17a2b8;
        color: white;
    }

    .align-items-center .btn-cg {
        background-color: rgb(65, 220, 255);
        /* สีเขียว */
        border-color: rgb(65, 220, 255);
        color: white;
    }

    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }


    @media only screen and (max-width: 767px) {
        .container {
            padding: 10px;
        }

        .table th,
        .table td {
            font-size: 12px;
        }

        .modal-xl {
            max-width: 95% !important;
        }

        .modal-lg {
            max-width: 90% !important;
        }

        .btn-adl,
        .btn-cg,
        .btn-info {
            width: 100%;
            margin-bottom: 5px;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 1024px) {
        .container {
            padding: 20px;
        }

        .table th,
        .table td {
            font-size: 14px;
        }

        .modal-xl {
            max-width: 85% !important;
        }

        .modal-lg {
            max-width: 80% !important;
        }

        .btn-adl,
        .btn-cg,
        .btn-info {
            width: 48%;
            margin-bottom: 10px;
        }
    }
</style>