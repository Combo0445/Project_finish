<div class="container">
    <form action="{{ route('password.verify-code') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="code">Verification Code</label>
            <input type="text" name="code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify Code</button>
    </form>
</div>
