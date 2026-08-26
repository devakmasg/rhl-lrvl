<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The closing "Continue Exploring" band appears on eight pages. Only the
     * homepage's was editable (as connect_cards on its page row); the other
     * seven were two hardcoded cards each — fourteen cards of identical markup
     * typed into Blade.
     *
     * One row per page, keyed the same way page_banners is, so a single admin
     * screen edits all of them and the shared partial renders them.
     *
     * Cards keep their dynamic bits through {tokens} — see CtaBlock — so copy
     * that names the MD or the phone number stays editable without freezing
     * today's value into the text.
     */
    public function up(): void
    {
        Schema::create('cta_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->string('label');
            $table->string('eyebrow')->nullable();
            $table->string('heading')->nullable();
            $table->string('section_id')->nullable();
            $table->json('cards')->nullable();
            $table->timestamps();
        });

        $now = now();

        DB::table('cta_blocks')->insert(array_map(
            fn (array $row) => $row + ['created_at' => $now, 'updated_at' => $now],
            $this->seedRows()
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('cta_blocks');
    }

    /**
     * Exactly the copy the views hardcoded, so the live pages read identically
     * the moment the partial takes over.
     */
    private function seedRows(): array
    {
        $rows = [
            [
                'page_key' => 'home',
                'label' => 'Homepage',
                'eyebrow' => 'Explore RHL Properties',
                'heading' => "Two decades of landmark developments — see where we've been, and where you fit in.",
                'section_id' => null,
                'cards' => [
                    ['title' => 'Get in Touch', 'text' => 'Speak with our team about current availability, partnership opportunities or a project you have in mind.', 'btn_label' => 'Contact us', 'btn_url' => 'contact'],
                    ['title' => 'Featured Developments', 'text' => 'Browse our residential, commercial and mixed-use projects across the region.', 'btn_label' => 'View projects', 'btn_url' => 'projects.index'],
                ],
            ],
            [
                'page_key' => 'achievements',
                'label' => 'Achievements',
                'eyebrow' => 'Continue Exploring',
                'heading' => 'See the projects behind these awards.',
                'section_id' => null,
                'cards' => [
                    ['title' => 'Our Projects', 'text' => 'Browse the residential, commercial and mixed-use developments behind these results.', 'btn_label' => 'View projects', 'btn_url' => 'projects.index'],
                    ['title' => 'Company Overview', 'text' => 'Read the full story of {company}, from 1998 to today.', 'btn_label' => 'About us', 'btn_url' => 'about'],
                ],
            ],
            [
                'page_key' => 'contact',
                'label' => 'Contact',
                'eyebrow' => null,
                'heading' => null,
                'section_id' => 'connect',
                'cards' => [
                    ['title' => 'Buyers & Investors', 'text' => 'Explore residences and commercial spaces across our portfolio, and speak with our sales team about current availability.', 'btn_label' => 'Investing with us', 'btn_url' => 'partners#investors'],
                    ['title' => 'Landowners & Partners', 'text' => 'Partner with {company} on your land or co-development opportunity — we handle design, delivery and everything between.', 'btn_label' => 'Partnering on land', 'btn_url' => 'partners#landowners'],
                ],
            ],
            [
                'page_key' => 'directors',
                'label' => 'Board of Directors',
                'eyebrow' => 'Continue Exploring',
                'heading' => 'Meet the team running delivery day to day.',
                'section_id' => null,
                'cards' => [
                    ['title' => 'Management Team', 'text' => 'Construction, sales and asset management leads across every current development.', 'btn_label' => 'Meet the team', 'btn_url' => 'management'],
                    ['title' => "Managing Director's Message", 'text' => "Read {md_name}'s message on the company's approach to every handover.", 'btn_label' => 'Read the message', 'btn_url' => 'md-message'],
                ],
            ],
            [
                'page_key' => 'management',
                'label' => 'Management Team',
                'eyebrow' => 'Continue Exploring',
                'heading' => 'See the board they report to.',
                'section_id' => null,
                'cards' => [
                    ['title' => 'Board of Directors', 'text' => 'The board overseeing strategy, governance and capital discipline at {company}.', 'btn_label' => 'Meet the board', 'btn_url' => 'directors'],
                    ['title' => "Managing Director's Message", 'text' => "Read the Managing Director's message on the company's approach to every project.", 'btn_label' => 'Read the message', 'btn_url' => 'md-message'],
                ],
            ],
            [
                'page_key' => 'md-message',
                'label' => "Managing Director's Message",
                'eyebrow' => 'Continue Exploring',
                'heading' => 'See the team carrying this forward.',
                'section_id' => null,
                'cards' => [
                    ['title' => 'Board of Directors', 'text' => 'The board overseeing strategy, governance and capital discipline at {company}.', 'btn_label' => 'Meet the board', 'btn_url' => 'directors'],
                    ['title' => 'Management Team', 'text' => 'The people running construction, sales and delivery on every current project.', 'btn_label' => 'Meet the team', 'btn_url' => 'management'],
                ],
            ],
            [
                'page_key' => 'mission-vision',
                'label' => 'Mission & Vision',
                'eyebrow' => 'Continue Exploring',
                'heading' => "See who's behind these commitments.",
                'section_id' => null,
                'cards' => [
                    ['title' => "Managing Director's Message", 'text' => "Read {md_name}'s message on how {company} approaches every project.", 'btn_label' => 'Read the message', 'btn_url' => 'md-message'],
                    ['title' => 'Board & Management', 'text' => 'Meet the board of directors and the management team delivering these values daily.', 'btn_label' => 'Meet the board', 'btn_url' => 'directors'],
                ],
            ],
            [
                'page_key' => 'thank-you',
                'label' => 'Thank You',
                'eyebrow' => 'While You Wait',
                'heading' => 'Keep exploring {company}.',
                'section_id' => null,
                'cards' => [
                    ['title' => 'Browse Developments', 'text' => 'See our full portfolio of residential, commercial and mixed-use projects across Dhaka.', 'btn_label' => 'View projects', 'btn_url' => 'projects.index'],
                    ['title' => 'Talk to Us Sooner', 'text' => 'Need a faster answer? Call or WhatsApp our team directly during office hours.', 'btn_label' => 'Call {phone}', 'btn_url' => 'tel:{phone}'],
                ],
            ],
        ];

        return array_map(
            fn (array $row) => [...$row, 'cards' => json_encode($row['cards'], JSON_UNESCAPED_UNICODE)],
            $rows
        );
    }
};
