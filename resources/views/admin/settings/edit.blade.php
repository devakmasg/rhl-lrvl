@extends('layouts.admin')

@section('title', 'Contact Settings')

@section('content')
<div class="page-head">
  <div>
    <h1>Contact Settings</h1>
    <p>Head office details used across the footer, contact.html and the homepage map.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary" type="submit" form="settingsForm">Save Changes</button>
  </div>
</div>

<form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}">
  @csrf
  @method('PUT')

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Head Office</h2>
    <div class="field" style="margin-bottom:16px;">
      <label for="csAddress">Address</label>
      <input type="text" id="csAddress" name="address" value="{{ old('address', $setting->address) }}">
      <span class="field-error">{{ $errors->first('address') }}</span>
    </div>
    <div class="field-row">
      <div class="field">
        <label for="csPhone">Primary Phone</label>
        <input type="tel" id="csPhone" name="phone" value="{{ old('phone', $setting->phone) }}">
        <span class="field-error">{{ $errors->first('phone') }}</span>
      </div>
      <div class="field">
        <label for="csWhatsapp">WhatsApp Number</label>
        <input type="tel" id="csWhatsapp" name="whatsapp" value="{{ old('whatsapp', $setting->whatsapp) }}">
        <span class="field-error">{{ $errors->first('whatsapp') }}</span>
      </div>
    </div>
    <div class="field" style="margin-top:16px;">
      <label for="csEmail">Email</label>
      <input type="email" id="csEmail" name="email" value="{{ old('email', $setting->email) }}">
      <span class="field-error">{{ $errors->first('email') }}</span>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Office Hours</h2>
    <div class="field-row">
      <div class="field">
        <label for="csHoursWeek">Sunday &ndash; Thursday</label>
        <input type="text" id="csHoursWeek" name="hours_weekday" value="{{ old('hours_weekday', $setting->hours_weekday) }}">
      </div>
      <div class="field">
        <label for="csHoursSat">Saturday</label>
        <input type="text" id="csHoursSat" name="hours_saturday" value="{{ old('hours_saturday', $setting->hours_saturday) }}">
      </div>
    </div>
    <div class="field" style="max-width:260px;margin-top:16px;">
      <label for="csHoursFri">Friday</label>
      <input type="text" id="csHoursFri" name="hours_friday" value="{{ old('hours_friday', $setting->hours_friday) }}">
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Map</h2>
    <div class="field">
      <label for="csMapQuery">Map Search Query</label>
      <input type="text" id="csMapQuery" name="map_query" value="{{ old('map_query', $setting->map_query) }}">
      <span class="hint">Used to build the Google Maps embed on the homepage and contact.html.</span>
    </div>
  </div>

  <div class="card card-pad">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Social Links</h2>
    <div class="field" style="margin-bottom:16px;">
      <label for="csInstagram">Instagram</label>
      <input type="url" id="csInstagram" name="social_instagram" value="{{ old('social_instagram', $setting->social_instagram) }}" placeholder="https://instagram.com/rhlproperties">
      <span class="field-error">{{ $errors->first('social_instagram') }}</span>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="csLinkedin">LinkedIn</label>
      <input type="url" id="csLinkedin" name="social_linkedin" value="{{ old('social_linkedin', $setting->social_linkedin) }}" placeholder="https://linkedin.com/company/rhlproperties">
      <span class="field-error">{{ $errors->first('social_linkedin') }}</span>
    </div>
    <div class="field">
      <label for="csFacebook">Facebook</label>
      <input type="url" id="csFacebook" name="social_facebook" value="{{ old('social_facebook', $setting->social_facebook) }}" placeholder="https://facebook.com/rhlproperties">
      <span class="field-error">{{ $errors->first('social_facebook') }}</span>
    </div>
  </div>
</form>
@endsection
