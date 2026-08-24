<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #1c1b19;">
  <h2>New inquiry — {{ $inquiry->reference }}</h2>
  <p><strong>Name:</strong> {{ $inquiry->name }}<br>
  <strong>Phone:</strong> {{ $inquiry->phone }}<br>
  <strong>Email:</strong> {{ $inquiry->email }}<br>
  <strong>Project:</strong> {{ $inquiry->project_name ?? 'General inquiry' }}</p>
  <p><strong>Message:</strong><br>{{ $inquiry->message }}</p>
  <p style="color:#8a857d;font-size:13px;">Received {{ $inquiry->created_at->format('d M Y, g:i A') }}.</p>
</body>
</html>
