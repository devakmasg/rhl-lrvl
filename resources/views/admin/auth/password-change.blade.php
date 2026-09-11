<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Choose Your Password | RHL Properties Ltd</title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2040%2040'%3E%3Ccircle%20cx='20'%20cy='20'%20r='20'%20fill='%23111110'/%3E%3Cpath%20d='M11%2026L20%2012l9%2014'%20stroke='%23b08d57'%20stroke-width='2.4'%20fill='none'%20stroke-linejoin='round'/%3E%3Ccircle%20cx='20'%20cy='20'%20r='2.8'%20fill='%23b08d57'/%3E%3C/svg%3E">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ \App\Support\Asset::v('assets/admin/css/admin.css') }}">
</head>
<body class="admin">

  {{-- Deliberately the bare login shell rather than layouts.admin: until the
       temporary password is replaced there is nowhere else to go, so showing
       the sidebar would only offer links that bounce straight back here. --}}
  <div class="login-screen">
    <div class="login-card">
      <div class="login-brand">
        <svg class="mark" viewBox="0 0 40 40" fill="none"><circle cx="20" cy="20" r="19" stroke="currentColor" stroke-width="1"/><path d="M11 26L20 12l9 14" stroke="#b08d57" stroke-width="1.4"/><circle cx="20" cy="20" r="2.4" fill="#b08d57"/></svg>
        <span class="word">RHL<small>ADMIN PANEL</small></span>
      </div>

      <h1>Choose your password</h1>
      <p class="login-sub">Welcome, {{ auth()->user()->name }}. The password you were given is temporary &mdash; pick your own to continue.</p>

      @if ($errors->any())
        <div class="form-status is-bad" style="margin-top:16px;">
          {{ $errors->first() }}
        </div>
      @endif

      <form class="login-form" method="POST" action="{{ route('admin.password.change.update') }}" style="margin-top:16px;">
        @csrf
        @method('PUT')
        <div class="field" style="margin-bottom:16px;">
          <label for="newPassword">New password</label>
          <input type="password" id="newPassword" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required minlength="8" autocomplete="new-password" autofocus>
          <span class="hint">At least 8 characters.</span>
        </div>
        <div class="field" style="margin-bottom:16px;">
          <label for="newPasswordConfirm">Confirm new password</label>
          <input type="password" id="newPasswordConfirm" name="password_confirmation" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required minlength="8" autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-primary">Save Password &amp; Continue</button>
      </form>

      <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:14px;text-align:center;">
        @csrf
        <button type="submit" class="login-back" style="background:none;border:0;cursor:pointer;">&larr; Sign out instead</button>
      </form>
    </div>
  </div>

  <script src="{{ \App\Support\Asset::v('assets/admin/js/admin.js') }}"></script>
</body>
</html>
