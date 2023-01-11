@if(session('status') == 'password-updated' )
    <x-alert type="success">{{ session('status') }}</x-alert>
@endif
<div class="tab-pane fade @if(session('status') == 'password-updated') show active @endif" id="password" role="tabpanel">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Change Password</h5>

            <form method="post" action="{{ route('user-password.update') }}">
                @csrf
                @method('PUT')

                <x-input type="password" label="Current Password" name="current_password" placeholder="Enter your current password"></x-input>
                <x-input type="password" label="New Password" name="password" placeholder="Enter your new password"></x-input>
                <x-input type="password" label="Confirm Password" name="password_confirmation" placeholder="Enter your password again"></x-input>

                <button type="submit" class="btn btn-primary">Update password</button>
            </form>
        </div>
    </div>
</div>
