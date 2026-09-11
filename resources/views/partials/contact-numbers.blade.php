{{-- A list of phone numbers, each under the name of the desk it reaches.

     Used by the two places with room for more than one number: the footer's
     Contact column and the Contact page's "Talk to us" block. Everywhere else
     on the site shows a single number and reads ContactNumber::primary()
     or ::forSlot() directly.

     Titles are printed only when there is more than one number — a lone number
     already sits under a "Contact" heading, and labelling it "Head Office"
     there just adds a line. --}}
@php
  $showLabels = $numbers->count() > 1;
@endphp
@foreach ($numbers as $contactNumber)
  @if ($showLabels)
    <span class="contact-number">
      <span class="cn-label">{{ $contactNumber->label }}</span>
      <a href="tel:{{ $contactNumber->tel }}">{{ $contactNumber->number }}</a>
    </span>
  @else
    <a href="tel:{{ $contactNumber->tel }}">{{ $contactNumber->number }}</a>
  @endif
@endforeach
