@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="page-head">
  <div>
    <h1>Site Settings</h1>
    <p>The company name, contact details, footer and header wording used across every page of the site.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary" type="submit" form="settingsForm">Save Changes</button>
  </div>
</div>

<form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}">
  @csrf
  @method('PUT')

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Company Name</h2>
    <p class="hint" style="margin-bottom:16px;">Used everywhere the company is named &mdash; the header wordmark, the footer, page titles and social share cards. Change it here and it changes across the whole site.</p>
    <div class="field-row">
      <div class="field">
        <label for="csSiteName">Full Legal Name</label>
        <input type="text" id="csSiteName" name="site_name" value="{{ old('site_name', $setting->site_name) }}" placeholder="RHL Properties Ltd">
        <span class="hint">Page titles, the footer and image alt text.</span>
        <span class="field-error">{{ $errors->first('site_name') }}</span>
      </div>
      <div class="field">
        <label for="csSiteShort">Short Name</label>
        <input type="text" id="csSiteShort" name="site_short_name" value="{{ old('site_short_name', $setting->site_short_name) }}" placeholder="RHL Properties">
        <span class="hint">Used mid-sentence, e.g. &ldquo;More from RHL Properties.&rdquo; Leave blank to use the full name.</span>
        <span class="field-error">{{ $errors->first('site_short_name') }}</span>
      </div>
    </div>
    <div class="field-row" style="margin-top:16px;">
      <div class="field">
        <label for="csBrandMark">Header Wordmark</label>
        <input type="text" id="csBrandMark" name="brand_mark" value="{{ old('brand_mark', $setting->brand_mark) }}" placeholder="RHL">
        <span class="hint">The large text beside the logo in the site header.</span>
        <span class="field-error">{{ $errors->first('brand_mark') }}</span>
      </div>
      <div class="field">
        <label for="csBrandMarkSub">Wordmark Subtitle</label>
        <input type="text" id="csBrandMarkSub" name="brand_mark_sub" value="{{ old('brand_mark_sub', $setting->brand_mark_sub) }}" placeholder="PROPERTIES LTD">
        <span class="hint">The small spaced-out line beneath it.</span>
        <span class="field-error">{{ $errors->first('brand_mark_sub') }}</span>
      </div>
    </div>
  </div>

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
      <span class="hint">Used to build the Google Maps embed on the homepage and the Contact page.</span>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Footer</h2>
    <div class="field">
      <label for="csFooterBlurb">Footer Blurb</label>
      <textarea id="csFooterBlurb" name="footer_blurb" style="min-height:80px;">{{ old('footer_blurb', $setting->footer_blurb) }}</textarea>
      <span class="hint">The short paragraph under the company name in the site footer.</span>
    </div>
    <div class="field-row" style="margin-top:16px;">
      <div class="field">
        <label for="csFooterContact">Contact Column Heading</label>
        <input type="text" id="csFooterContact" name="footer_contact_heading" value="{{ old('footer_contact_heading', $setting->footer_contact_heading) }}" placeholder="Contact">
      </div>
      <div class="field">
        <label for="csFooterFollow">Follow Column Heading</label>
        <input type="text" id="csFooterFollow" name="footer_follow_heading" value="{{ old('footer_follow_heading', $setting->footer_follow_heading) }}" placeholder="Follow">
        <span class="hint">The link-column heading is set in <a href="{{ route('admin.menus.index') }}">Menus</a>.</span>
      </div>
    </div>
    <div class="field-row" style="margin-top:16px;">
      <div class="field">
        <label for="csFooterRights">Rights Line</label>
        <input type="text" id="csFooterRights" name="footer_rights" value="{{ old('footer_rights', $setting->footer_rights) }}" placeholder="All Rights Reserved.">
        <span class="hint">Follows the year and company name in the bottom bar.</span>
      </div>
      <div class="field">
        <label for="csFooterCredit">Credit Line</label>
        <input type="text" id="csFooterCredit" name="footer_credit" value="{{ old('footer_credit', $setting->footer_credit) }}">
        <span class="hint">Bottom-right of the footer. Leave blank to hide it.</span>
      </div>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Header</h2>
    <div class="field" style="max-width:280px;">
      <label for="csNavCta">Enquiry Button Label</label>
      <input type="text" id="csNavCta" name="nav_cta_label" value="{{ old('nav_cta_label', $setting->nav_cta_label) }}" placeholder="Enquire">
      <span class="hint">The gold button at the right of the header. Menu links are edited in <a href="{{ route('admin.menus.index') }}">Menus</a>.</span>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Search &amp; Social</h2>
    <div class="field">
      <label for="csMetaDescription">Default Description</label>
      <textarea id="csMetaDescription" name="meta_description" style="min-height:80px;">{{ old('meta_description', $setting->meta_description) }}</textarea>
      <span class="hint">Shown by Google and on shared links for any page that has no description of its own in Page Headers.</span>
      <span class="field-error">{{ $errors->first('meta_description') }}</span>
    </div>
  </div>

  <div class="card card-pad">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Social Links</h2>
    <p class="hint" style="margin-bottom:16px;">Only the links you fill in appear in the footer's "Follow" column.</p>
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
