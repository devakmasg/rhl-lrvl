<?php

namespace Database\Seeders;

use App\Models\PageBanner;
use Illuminate\Database\Seeder;

/**
 * Seeded with exactly the copy each page previously hardcoded, so switching
 * the views over to these rows is a no-op visually.
 */
class PageBannerSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->banners() as $banner) {
            PageBanner::updateOrCreate(['page_key' => $banner['page_key']], $banner);
        }
    }

    private function banners(): array
    {
        return [
            [
                // The homepage has no banner block — its hero is the slider —
                // so only the search/sharing half of this row is used.
                'page_key' => 'home',
                'label' => 'Homepage (search listing only)',
                'eyebrow' => null,
                'heading' => null,
                'intro' => null,
                'image_path' => null,
                'seo_title' => "RHL Properties Ltd | Building Tomorrow's Landmarks",
                'seo_description' => 'RHL Properties Ltd — a diversified real estate & investment group across residential, commercial and hospitality developments.',
            ],
            [
                'page_key' => 'about',
                'label' => 'About / Company Overview',
                'eyebrow' => 'About RHL Properties',
                'heading' => 'A legacy built on trust, developments built to last.',
                'intro' => 'RHL Properties Ltd shapes skylines and communities across residential, commercial and hospitality real estate — guided by design integrity and long-term value.',
                'image_path' => 'assets/images/hero-1-residential.jpg',
                'seo_title' => 'About | RHL Properties Ltd',
                'seo_description' => 'RHL Properties Ltd — a Bangladeshi real estate developer since 1998, building residential, commercial and hospitality projects across Gulshan, Banani, Dhanmondi and Tejgaon.',
            ],
            [
                'page_key' => 'mission-vision',
                'label' => 'Mission & Vision',
                'eyebrow' => 'Mission & Vision',
                'heading' => "What we're building toward.",
                'intro' => 'The principles that decide which land we buy, which contractors we sign, and which dates we promise.',
                'image_path' => 'assets/images/hero-2-commercial.jpg',
                'seo_title' => 'Mission & Vision | RHL Properties Ltd',
                'seo_description' => 'RHL Properties Ltd — our mission, vision and the core values behind every development in Dhaka.',
            ],
            [
                'page_key' => 'landowners',
                'label' => 'Landowners',
                'eyebrow' => 'Joint Venture Development',
                'heading' => 'Develop your land with confidence.',
                'intro' => 'Hand your plot to a developer that secures the approvals first, puts the sharing ratio in writing, and hands the building over on the date it promised.',
                'image_path' => 'assets/images/hero-2-commercial.jpg',
                'seo_title' => 'Landowners — Joint Venture Development | RHL Properties Ltd',
                'seo_description' => 'Develop your Dhaka land with RHL Properties. A written sharing ratio, RAJUK approvals secured before work starts, and a handover date we hold to.',
            ],
            [
                'page_key' => 'chairman-message',
                'label' => "Chairman's Message",
                'eyebrow' => 'Leadership',
                'heading' => 'A message from our Chairman.',
                'intro' => null,
                'image_path' => 'assets/images/hero-5-business.jpg',
                'seo_title' => "Chairman's Message | RHL Properties Ltd",
                'seo_description' => 'A message from the Chairman of RHL Properties Ltd, on the values behind every development the company puts its name to.',
            ],
            [
                'page_key' => 'md-message',
                'label' => "Managing Director's Message",
                'eyebrow' => 'Leadership',
                'heading' => 'A message from our Managing Director.',
                'intro' => null,
                'image_path' => 'assets/images/hero-1-residential.jpg',
                'seo_title' => "Managing Director's Message | RHL Properties Ltd",
                'seo_description' => "A message from the Managing Director of RHL Properties Ltd, on the company's approach to every development.",
            ],
            [
                'page_key' => 'directors',
                'label' => 'Our Leaders',
                'eyebrow' => 'Leadership',
                'heading' => 'Our Leaders.',
                'intro' => 'The board overseeing strategy, governance and capital discipline across every RHL development.',
                'image_path' => 'assets/images/hero-5-business.jpg',
                'seo_title' => 'Our Leaders | RHL Properties Ltd',
                'seo_description' => 'Meet the leaders of RHL Properties Ltd, overseeing strategy, governance and capital discipline.',
            ],
            [
                'page_key' => 'management',
                'label' => 'Our Team',
                'eyebrow' => 'Leadership',
                'heading' => 'Our Team.',
                'intro' => 'The people running construction, sales, finance and after-handover support on every current project.',
                'image_path' => 'assets/images/hero-3-hospitality.jpg',
                'seo_title' => 'Our Team | RHL Properties Ltd',
                'seo_description' => 'Meet the team at RHL Properties Ltd running construction, sales, finance and after-handover support.',
            ],
            [
                'page_key' => 'sales-team',
                'label' => 'Sales Team',
                'eyebrow' => 'Talk To Us',
                'heading' => 'Our Sales Team.',
                'intro' => 'The people who will show you the units, walk you through the payment schedule, and stay with you from first viewing to handover.',
                'image_path' => 'assets/images/hero-3-hospitality.jpg',
                'seo_title' => 'Sales Team | RHL Properties Ltd',
                'seo_description' => 'Meet the sales team at RHL Properties Ltd — the people handling viewings, payment schedules and bookings across every current development.',
            ],
            [
                'page_key' => 'achievements',
                'label' => 'Achievements',
                'eyebrow' => 'Recognition',
                'heading' => 'Achievements.',
                'intro' => "Milestones, industry recognition and certifications earned over more than 25 years in Dhaka's real estate market.",
                'image_path' => 'assets/images/hero-4-waterfront.jpg',
                'seo_title' => 'Achievements | RHL Properties Ltd',
                'seo_description' => 'Milestones, industry recognition and certifications earned by RHL Properties Ltd over 25+ years.',
            ],
            [
                'page_key' => 'projects',
                'label' => 'Projects (listing)',
                'eyebrow' => 'Featured Developments',
                'heading' => 'Landmarks in the making',
                'intro' => "Our portfolio spans residential, commercial and mixed-use developments across the region's most sought-after districts.",
                'image_path' => 'assets/images/hero-2-commercial.jpg',
                'seo_title' => 'Projects | RHL Properties Ltd',
                'seo_description' => "Browse RHL Properties Ltd's portfolio of ongoing, upcoming and completed residential and commercial developments across Gulshan, Banani, Dhanmondi and Tejgaon, Dhaka.",
            ],
            [
                'page_key' => 'services',
                'label' => 'Services',
                'eyebrow' => 'What We Do',
                'heading' => 'Diversified across every stage of the built environment.',
                'intro' => 'From land acquisition and design to construction, leasing and long-term asset management — RHL Properties covers the full spectrum of real estate services.',
                'image_path' => 'assets/images/hero-3-hospitality.jpg',
                'seo_title' => 'Services | RHL Properties Ltd',
                'seo_description' => 'From land acquisition and design to construction, leasing and long-term asset management — RHL Properties covers the full spectrum of real estate services.',
            ],
            [
                'page_key' => 'partners',
                'label' => 'Investors & Landowners',
                'eyebrow' => 'Investors & Landowners',
                'heading' => 'Two ways to build with us.',
                'intro' => 'Whether you hold land in a prime address or capital looking for a considered return, the terms are the same in spirit: transparent, documented, and delivered on schedule.',
                'image_path' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?auto=format&fit=crop&w=1800&q=80',
                'seo_title' => 'Investors & Landowners | RHL Properties Ltd',
                'seo_description' => 'Partner with RHL Properties Ltd — joint-venture terms for landowners and investment opportunities across residential and commercial developments.',
            ],
            [
                'page_key' => 'testimonials',
                'label' => 'Testimonials',
                'eyebrow' => 'Client Voices',
                'heading' => 'Trusted by those who build with us',
                'intro' => 'Homeowners, landowners and commercial tenants share their experience partnering with RHL Properties Ltd.',
                'image_path' => 'assets/images/hero-4-waterfront.jpg',
                'seo_title' => 'Testimonials | RHL Properties Ltd',
                'seo_description' => 'Read what homeowners, landowner partners and commercial tenants say about building with RHL Properties Ltd.',
            ],
            [
                'page_key' => 'contact',
                'label' => 'Contact',
                'eyebrow' => "Let's Build Together",
                'heading' => "Whether you're searching for a home, an address for your business, or a partner for your land — we'd love to talk.",
                'intro' => null,
                'image_path' => 'assets/images/hero-5-business.jpg',
                'seo_title' => 'Contact | RHL Properties Ltd',
                'seo_description' => 'Contact RHL Properties Ltd — call, WhatsApp or send an enquiry to our Gulshan-1, Dhaka head office. Office hours, map and quick-contact details inside.',
            ],
            [
                'page_key' => 'news',
                'label' => 'News & Updates (listing)',
                'eyebrow' => 'News & Updates',
                'heading' => 'Construction updates, handovers and announcements.',
                'intro' => 'Everything newsworthy from our residential, commercial and hospitality developments across Dhaka.',
                'image_path' => 'assets/images/hero-2-commercial.jpg',
                'seo_title' => 'News & Updates | RHL Properties Ltd',
                'seo_description' => 'Construction updates, handovers, awards and announcements from RHL Properties Ltd.',
            ],
            [
                'page_key' => 'thank-you',
                'label' => 'Thank You (after enquiry)',
                'eyebrow' => 'Message Received',
                'heading' => 'Thank you',
                'intro' => null,
                'image_path' => 'assets/images/hero-1-residential.jpg',
                'seo_title' => 'Thank You | RHL Properties Ltd',
                'seo_description' => "Thank you for contacting RHL Properties Ltd — we've received your enquiry and will reply within two working days.",
            ],
        ];
    }
}
