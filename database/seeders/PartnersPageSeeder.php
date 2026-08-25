<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeds the "partners" Page row with exactly the copy that used to be
 * hardcoded in resources/views/pages/partners.blade.php, so switching that
 * view over to $page->content is a no-op visually.
 *
 * Pillars and steps are fixed-count by design (the CSS grid this page uses
 * assumes 4 pillars / 5 steps per audience), so they're stored as plain
 * indexed arrays rather than a free-form repeater.
 */
class PartnersPageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(['slug' => 'partners'], [
            'title' => 'Partners',
            'content' => [
                'intro_eyebrow' => 'Why RHL Properties',
                'intro_heading' => 'Twenty-five years of partnerships that finished on time.',
                'intro_text_1' => 'Since 1998 we have completed fifty-two developments without a single project abandoned mid-construction. Landowners keep their share protected by registered agreement from day one, and investors see the same quarterly reporting our own board reads.',
                'intro_text_2' => 'Every partnership begins the same way — a site visit, an honest feasibility study, and written terms before anything is signed.',
                'intro_image' => 'assets/images/hero-2-commercial.jpg',

                'how_eyebrow' => 'How it works',
                'how_heading' => 'Choose the path that fits you.',

                'landowner_lead' => 'You own the land. We bring design, approvals, financing and construction — and you receive an agreed share of the finished development, secured in writing before work begins.',
                'landowner_pillars' => [
                    ['title' => 'A fair, written share', 'desc' => 'Your share of the built area is fixed by registered joint-venture deed at the outset — never renegotiated once construction starts.'],
                    ['title' => 'Signing money up front', 'desc' => 'A non-refundable advance is paid on signing, with the balance scheduled against verified construction milestones.'],
                    ['title' => 'We carry the cost', 'desc' => 'Approvals, design, materials and labour are financed entirely by RHL Properties. You are not asked to fund construction.'],
                    ['title' => 'Handover on a date', 'desc' => 'A completion date is written into the agreement, with an agreed penalty payable to you if we miss it.'],
                ],
                'landowner_steps' => [
                    ['title' => 'Submit your land', 'desc' => 'Send us the location, plot size and ownership documents using the form below. A first response takes two to three working days.'],
                    ['title' => 'Site visit and title check', 'desc' => 'Our team visits the plot and our legal counsel verifies title, mutation and any encumbrance. There is no cost to you at this stage.'],
                    ['title' => 'Feasibility and offer', 'desc' => 'We model what the site can support under current planning rules and return a written offer setting out your share, the advance and the timeline.'],
                    ['title' => 'Agreement and advance', 'desc' => 'Terms are registered as a joint-venture deed. The signing advance is paid and the power of attorney is limited strictly to obtaining approvals.'],
                    ['title' => 'Construction and handover', 'desc' => 'You receive quarterly progress reports and open site access throughout. On completion, your share is handed over with individual documentation.'],
                ],

                'investor_lead' => 'Invest alongside a developer that publishes its numbers. Positions are available in individual developments or across a portfolio, from pre-launch through to completed, income-producing assets.',
                'investor_pillars' => [
                    ['title' => 'Enter at any stage', 'desc' => 'Pre-launch pricing on projects still in approval, or completed assets already tenanted and producing rent from day one.'],
                    ['title' => 'Reporting you can audit', 'desc' => 'Quarterly statements covering construction progress, cost against budget, sales velocity and occupancy — the same pack our board reads.'],
                    ['title' => 'Our capital sits alongside', 'desc' => 'RHL Properties retains a stake in every development it syndicates, so our exposure moves in the same direction as yours.'],
                    ['title' => 'A defined exit', 'desc' => 'Resale, buy-back and hold-for-income routes are set out in the subscription documents before you commit, not after.'],
                ],
                'investor_steps' => [
                    ['title' => 'Introductory call', 'desc' => 'A short conversation about your horizon, target return and whether income or capital growth matters more to you.'],
                    ['title' => 'Opportunity pack', 'desc' => 'You receive the current schedule of developments with costs, projected returns, timelines and the risks attached to each.'],
                    ['title' => 'Site and books', 'desc' => 'Visit the sites and review the audited accounts and the delivery record on completed projects before committing anything.'],
                    ['title' => 'Subscription', 'desc' => 'Terms, payment schedule and exit routes are documented and signed. Funds are drawn against construction milestones, not in advance.'],
                    ['title' => 'Reporting and exit', 'desc' => 'Quarterly reporting through the build, then distribution, resale or transfer to income according to the route you chose.'],
                ],

                'stats_eyebrow' => 'Track Record',
                'stats_heading' => 'The numbers behind the partnership.',
                'stats' => [
                    ['value' => '52+', 'label' => 'Developments Completed'],
                    ['value' => '6.4M+', 'label' => 'Sq. Ft. Delivered'],
                    ['value' => '140+', 'label' => 'Landowner Partnerships'],
                    ['value' => '25', 'label' => 'Years of Excellence'],
                    ['value' => '0', 'label' => 'Projects Abandoned'],
                    ['value' => '96%', 'label' => 'Delivered On Schedule'],
                ],

                'contact_eyebrow' => 'Start a conversation',
                'contact_heading' => 'Submit your land or your interest.',
                'contact_text' => "Tell us which side of the partnership you're on and we'll send the relevant pack. Nothing is committed at this stage.",

                'aside_ready_text' => 'Landowners: title deed, mutation certificate, latest rent receipt and a recent survey plan. Investors: nothing — the first conversation needs no paperwork.',
                'aside_timeline_text' => 'First response in 2–3 working days. Site visit and title check within two weeks. Written offer within a month of the visit.',
                'aside_work_text' => 'Every completed and ongoing development is listed with its status and location.',
            ],
        ]);
    }
}
