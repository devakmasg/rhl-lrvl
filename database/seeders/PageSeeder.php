<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'slug' => 'home',
            'title' => 'Home',
            'content' => [
                'hero_headline' => "Building Tomorrow's Landmarks.",
                'hero_eyebrow' => 'A Diversified Real Estate & Investment Group',
                'hero_label' => 'Residential Excellence',
                'hero_sub' => 'RHL Properties Ltd shapes skylines and communities across residential, commercial and hospitality real estate — guided by design integrity and long-term value.',
                'intro_headline' => 'A legacy built on trust, developments built to last.',
                'intro_since_text' => "Over two decades delivering landmark residential and commercial developments across Dhaka's most sought-after neighbourhoods.",
                'intro_spectrum_text' => 'From land acquisition and RAJUK-approved design to construction, handover and long-term asset management.',
                'why_cards' => [
                    ['title' => 'RAJUK-Approved Developments', 'desc' => 'Every project clears RAJUK approval before groundbreaking, so ownership and building rights are never in question.'],
                    ['title' => 'On-Time Handover', 'desc' => 'A construction record measured in completed handovers, not projected ones — several delivered ahead of schedule.'],
                    ['title' => 'Prime Dhaka Locations', 'desc' => 'Gulshan, Banani, Dhanmondi and Tejgaon — land selected for long-term value, not just today\'s asking price.'],
                    ['title' => 'Transparent Process', 'desc' => 'Clear payment schedules, registered documentation and a dedicated point of contact from booking to handover.'],
                ],
                'stats' => [
                    ['value' => '6.4M+', 'label' => 'Sq. Ft. Developed'],
                    ['value' => '52+', 'label' => 'Landmark Projects'],
                    ['value' => '25', 'label' => 'Years of Excellence'],
                    ['value' => '8200+', 'label' => 'Satisfied Clients'],
                    ['value' => '12', 'label' => 'Cities Present'],
                    ['value' => '30+', 'label' => 'Industry Awards'],
                ],
            ],
        ]);

        Page::create([
            'slug' => 'about',
            'title' => 'About',
            'content' => [
                'headline' => 'Two decades in Dhaka\'s built environment.',
                'overview' => [
                    "RHL Properties Ltd was founded in 1998 with a single residential project in Gulshan and a simple operating principle: never hand over a unit we wouldn't live in ourselves. Almost three decades later that principle still governs every plot we acquire and every contractor we sign.",
                    'Today the group works across residential, commercial and hospitality real estate, with a land bank concentrated in Gulshan, Banani, Dhanmondi and Tejgaon. Every development clears RAJUK approval before groundbreaking, and every payment schedule is registered and disclosed to buyers in full before booking.',
                    "We remain a privately held, family-led company — a structure we've kept deliberately, because it lets us commit to a ten-year handover record instead of a quarterly one.",
                ],
                'milestones' => [
                    ['year' => '1998', 'text' => 'RHL Properties Ltd founded — incorporated in Dhaka with our first residential plot secured in Gulshan.'],
                    ['year' => '2005', 'text' => "First commercial tower — delivered our first Grade-A office address, opening the company's commercial real estate line."],
                    ['year' => '2012', 'text' => 'Expansion to Banani & Dhanmondi — land bank widened beyond Gulshan, adding lakeside residential sites in two new areas.'],
                    ['year' => '2019', 'text' => 'Tejgaon industrial & logistics line — entered light-industrial development, converting disused warehouse land into leasable space.'],
                    ['year' => '2024', 'text' => 'Gulshan Heights handed over — our flagship lakeside residence delivered three months ahead of its contracted date.'],
                ],
                'facts' => [
                    ['k' => 'Founded', 'v' => '1998'],
                    ['k' => 'Head Office', 'v' => 'Gulshan-1, Dhaka'],
                    ['k' => 'Landmark Projects', 'v' => '52+'],
                    ['k' => 'Sq. Ft. Developed', 'v' => '6.4M+'],
                    ['k' => 'Areas Active In', 'v' => 'Gulshan, Banani, Dhanmondi, Tejgaon'],
                ],
                'mission' => 'To design and build residential, commercial and hospitality spaces that stand for decades — earning trust through quality, transparency and timely handover. Every RAJUK approval, every registered payment schedule and every completed unit is judged against that one standard.',
                'vision' => "To be Bangladesh's most trusted real estate group — recognised for design integrity, RAJUK compliance and long-term value for buyers, landowners and partners alike, across Gulshan, Banani, Dhanmondi and Tejgaon.",
                'core_values' => [
                    ['title' => 'Integrity', 'desc' => 'Land titles verified, RAJUK approvals secured before booking opens — never the reverse.'],
                    ['title' => 'Quality', 'desc' => 'Materials and finishes specified for decades of use, not for the day of handover photos.'],
                    ['title' => 'Transparency', 'desc' => 'Payment schedules, floor plans and construction updates disclosed in full, in writing.'],
                    ['title' => 'Timeliness', 'desc' => 'A handover date is a commitment, not an estimate — several of ours have beaten it.'],
                ],
                'md_name' => 'Md. Rezaul Haque',
                'md_photo' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80',
                'md_quote' => "Every RHL address is a promise we intend to keep — approved land, honest schedules and a handover date we don't move.",
                'md_message' => [
                    "When my father founded RHL Properties in 1998, Dhaka's real estate market ran mostly on verbal promises — a handshake on a payment schedule, a groundbreaking before the paperwork caught up. He built the company on the opposite habit: secure the RAJUK approval first, put the payment schedule in writing, and only then open booking. Twenty-seven years on, that habit is still how we operate.",
                    'Every development that carries the RHL name — from Gulshan Heights to the RHL Trade Centre now rising in Gulshan — goes through the same discipline before a single unit is sold. We would rather delay an announcement than announce a date we\'re not certain of. That\'s why several of our completed projects, including Gulshan Heights, were handed over ahead of their contracted date rather than behind it.',
                    "Our team has grown from a handful of people working out of a single office to a full construction, sales and after-handover support organisation, but the standard hasn't changed: if it isn't something I'd hand the keys to my own family for, it doesn't carry the RHL name.",
                    'To everyone who has trusted us with a booking, a joint-venture plot, or a lease — thank you. To everyone considering it, our door in Gulshan-1 is open, and so are our books.',
                ],
            ],
        ]);
    }
}
