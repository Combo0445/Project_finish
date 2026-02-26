<style>
    body {
        font-family: 'Open Sans', sans-serif;
        background-color: #f8f9fa;
        color: #344767;
    }

    .news,
    .statistics,
    .contact-form {
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .news h2,
    .statistics h2,
    .contact-form h2 {
        margin-bottom: 20px;
    }

    .statistics .stats {
        display: flex;
        gap: 20px;
    }

    .statistics .stat-item {
        flex: 1;
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .contact-form form div {
        margin-bottom: 15px;
    }

    .contact-form label {
        display: block;
        margin-bottom: 5px;
    }

    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 5px;
    }

    .contact-form button {
        background-color: #fb6340;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .contact-form button:hover {
        background-color: #ea3005;
    }

    .slider {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 500px;
        margin-bottom: 50px;
    }

    .slides {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    .slides img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .prev,
    .next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: #fff;
        border: none;
        padding: 10px;
        cursor: pointer;
        z-index: 1;
    }

    .prev {
        left: 10px;
    }

    .next {
        right: 10px;
    }

    .modal img {
        width: 100%;
        height: auto;
    }

    .modal-dialog-centered {
        max-width: 90%;
        margin: 30px auto;
    }

    .dashboard-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dashboard-card .icon {
        font-size: 2em;
        color: #fb6340;
    }

    .dashboard-card h3 {
        margin: 0;
    }

    .dashboard-card p {
        margin: 5px 0 0;
    }

    .news-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 10px;
        margin-bottom: 50px;
        height: 70%;
        display: flex;
        flex-direction: column;
    }

    .admin-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-bottom: 20px;
    }

    .admin-buttons .btn {
        margin-right: 0;
    }

    .admin-buttons a {
        background-color: #fb6340;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    .admin-buttons a:hover {
        background-color: #ea3005;
    }

    .image-grid {
        display: grid;
        gap: 10px;
    }

    .image-grid img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .image-item::after {
        content: "";
        display: block;
        padding-bottom: 100%;
    }

    .image-item img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (min-width: 600px) {
        .image-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 900px) {
        .image-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .image-item {
        position: relative;
        overflow: hidden;
    }

    .image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .more-overlay .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        font-size: 2rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .short-content {
        display: block;
    }

    .full-content {
        display: none;
    }

    .custom-modal-size {
        max-width: 90%;
    }
</style>