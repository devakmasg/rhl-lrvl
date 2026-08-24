@extends('layouts.admin')

@section('title', 'Profile Settings')

@push('head')
<style>
  .avatar-row{display:flex;align-items:center;gap:16px;margin-bottom:20px;}
  .avatar-row img{width:64px;height:64px;border-radius:50%;object-fit:cover;flex:none;}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <h1>Profile Settings</h1>
    <p>Your admin account details and password.</p>
  </div>
</div>

<div class="card card-pad" style="max-width:560px;margin-bottom:20px;">
  <h2 style="font-size:15.5px;margin-bottom:16px;">Account Details</h2>
  <form id="profileForm" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="avatar-row">
      <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=200&q=80' }}" alt="" id="avatarPreview">
      <div>
        <label class="btn btn-outline btn-sm" for="avatarInput" style="cursor:pointer;">Change Photo</label>
        <input type="file" id="avatarInput" name="avatar" accept="image/*" hidden>
      </div>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="profName">Full Name</label>
      <input type="text" id="profName" name="name" value="{{ old('name', $user->name) }}" required>
      <span class="field-error">{{ $errors->first('name') }}</span>
    </div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="profEmail">Email</label>
        <input type="email" id="profEmail" name="email" value="{{ old('email', $user->email) }}" required>
        <span class="field-error">{{ $errors->first('email') }}</span>
      </div>
      <div class="field">
        <label for="profPhone">Phone</label>
        <input type="tel" id="profPhone" name="phone" value="{{ old('phone', $user->phone) }}">
      </div>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="profRole">Role</label>
      <input type="text" id="profRole" value="{{ $user->role }}" disabled>
    </div>
    <button class="btn btn-primary" type="submit">Save Profile</button>
  </form>
</div>

<div class="card card-pad" style="max-width:560px;">
  <h2 style="font-size:15.5px;margin-bottom:16px;">Change Password</h2>
  <form id="passwordForm" method="POST" action="{{ route('admin.profile.password') }}">
    @csrf
    @method('PUT')
    <div class="field" style="margin-bottom:16px;">
      <label for="pwCurrent">Current Password</label>
      <input type="password" id="pwCurrent" name="current_password" required autocomplete="current-password">
      <span class="field-error">{{ $errors->first('current_password') }}</span>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="pwNew">New Password</label>
      <input type="password" id="pwNew" name="password" minlength="8" required autocomplete="new-password">
      <span class="hint">At least 8 characters.</span>
      <span class="field-error">{{ $errors->first('password') }}</span>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="pwConfirm">Confirm New Password</label>
      <input type="password" id="pwConfirm" name="password_confirmation" required autocomplete="new-password">
      <span class="field-error"></span>
    </div>
    <button class="btn btn-primary" type="submit">Update Password</button>
  </form>
</div>

@push('scripts')
<script>
  document.getElementById('avatarInput').addEventListener('change', (e) => {
    if (e.target.files[0]) {
      document.getElementById('avatarPreview').src = URL.createObjectURL(e.target.files[0]);
    }
  });
</script>
@endpush
@endsection
