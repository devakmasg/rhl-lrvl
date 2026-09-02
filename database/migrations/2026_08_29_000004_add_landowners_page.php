<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * A page of its own for landowners.
     *
     * The Partners page already carries landowner copy, but only as one tab of
     * a two-audience section — there was no link to hand a landowner, and
     * nothing for a search engine to rank on "develop my land in Dhaka".
     *
     * The qualifying pillars and the joint-venture process stay on the
     * partners row and are read from there, so the two pages cannot drift
     * apart. Everything this page adds — the openers, the landowner quotes,
     * the FAQ and its own submission form copy — lives on the row below.
     */
    public function up(): void
    {
        $now = now();

        if (! DB::table('pages')->where('slug', 'landowners')->exists()) {
            DB::table('pages')->insert([
                'slug' => 'landowners',
                'title' => 'Landowners',
                'content' => json_encode($this->content()),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('page_banners')->updateOrInsert(
            ['page_key' => 'landowners'],
            [
                'label' => 'Landowners',
                'eyebrow' => 'Joint Venture Development',
                'heading' => 'Develop your land with confidence.',
                'intro' => 'Hand your plot to a developer that secures the approvals first, puts the sharing ratio in writing, and hands the building over on the date it promised.',
                'image_path' => 'assets/images/hero-2-commercial.jpg',
                'seo_title' => 'Landowners — Joint Venture Development | RHL Properties Ltd',
                'seo_description' => 'Develop your Dhaka land with RHL Properties. A written sharing ratio, RAJUK approvals secured before work starts, and a handover date we hold to.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('cta_blocks')->updateOrInsert(
            ['page_key' => 'landowners'],
            [
                'label' => 'Landowners',
                'eyebrow' => 'Continue Exploring',
                'heading' => 'See what we have built for other landowners.',
                'section_id' => null,
                'cards' => json_encode([
                    ['title' => 'Completed Developments', 'text' => 'Every project {company} has handed over, with the year and the location of each.', 'btn_label' => 'View projects', 'btn_url' => 'projects.index'],
                    ['title' => 'Investors & Partners', 'text' => 'Investing rather than contributing land? The terms and the process are set out here.', 'btn_label' => 'Investing with us', 'btn_url' => 'partners#investors'],
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->addMenuLink($now);
    }

    public function down(): void
    {
        DB::table('pages')->where('slug', 'landowners')->delete();
        DB::table('page_banners')->where('page_key', 'landowners')->delete();
        DB::table('cta_blocks')->where('page_key', 'landowners')->delete();
        DB::table('menu_links')->where('target', 'landowners')->delete();
    }

    /** Top level of the header menu, directly after Partners. */
    private function addMenuLink($now): void
    {
        $primaryId = DB::table('menus')->where('key', 'primary')->value('id');

        if (! $primaryId || DB::table('menu_links')->where('target', 'landowners')->exists()) {
            return;
        }

        $partners = DB::table('menu_links')
            ->where('menu_id', $primaryId)
            ->whereNull('parent_id')
            ->where('target', 'partners')
            ->first();

        $sortOrder = $partners ? $partners->sort_order + 1 : 99;

        if ($partners) {
            DB::table('menu_links')
                ->where('menu_id', $primaryId)
                ->whereNull('parent_id')
                ->where('sort_order', '>=', $sortOrder)
                ->increment('sort_order');
        }

        DB::table('menu_links')->insert([
            'menu_id' => $primaryId,
            'parent_id' => null,
            'label' => 'Landowners',
            'target' => 'landowners',
            'sort_order' => $sortOrder,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Starting copy, written to be edited. It is deliberately specific — a
     * page that ships with "Lorem ipsum" or "Your text here" tends to go live
     * that way.
     */
    private function content(): array
    {
        return [
            'intro_eyebrow' => 'Why Develop With Us',
            'intro_heading' => 'Your land is the hardest part. We handle everything after it.',
            'intro_text_1' => 'A joint venture puts your plot into a development without you funding the construction, managing contractors, or chasing an approval file through RAJUK. You contribute the land; we contribute the capital, the design, the build and the sales — and you take an agreed share of the finished building.',
            'intro_text_2' => 'What decides whether that works out is the developer. We secure the approvals before breaking ground, put the sharing ratio and the handover date in a registered agreement, and give you one named person to call for the whole of the project.',
            'intro_image' => 'assets/images/hero-5-business.jpg',
            'video_url' => '',
            'video_caption' => 'A short look at how a joint venture works, start to finish.',
            'diff_eyebrow' => 'How We Are Different',
            'diff_heading' => 'Approvals first. Dates that hold.',
            'diff_text_1' => 'Since 1998 we have handed over developments across Gulshan, Banani, Dhanmondi and Tejgaon — several of them ahead of the contracted date. That record exists because we do not open booking on a project whose paperwork is not finished, and we do not promise a date we are not certain of.',
            'diff_text_2' => 'You will deal with our own architects, engineers and construction team rather than a chain of subcontractors, and you will get a written progress update every month of the build — not only when there is something to celebrate.',
            'diff_image' => 'assets/images/hero-1-residential.jpg',
            'pillars_eyebrow' => 'What You Get',
            'pillars_heading' => 'The terms we commit to in writing.',
            'process_eyebrow' => 'The Process',
            'process_heading' => 'From first conversation to handover.',
            'quotes_eyebrow' => 'Landowners',
            'quotes_heading' => 'What landowners say about working with us.',
            'quotes' => [
                ['quote' => 'They walked me through the sharing ratio with the drawings in front of us, then put every word of it into the agreement. Nothing changed later.', 'name' => 'Shahidul Karim', 'project' => 'Gulshan Heights — 12 katha, Gulshan-2'],
                ['quote' => 'My family had held the plot for thirty years and were nervous about handing it over. The monthly progress reports are what settled it — we always knew where the building was.', 'name' => 'Rezia Sultana', 'project' => 'Banani Residences — 8 katha, Banani'],
                ['quote' => 'The handover came a month before the date in the contract. In this market I did not expect that.', 'name' => 'Anwar Hossain', 'project' => 'Dhanmondi Court — 10 katha, Dhanmondi'],
            ],
            'faq_eyebrow' => 'Questions',
            'faq_heading' => 'Frequently asked questions.',
            'faqs' => [
                ['q' => 'What sharing ratio can I expect?', 'a' => 'It depends on the location, the plot size and what the approved FAR allows on it. We will give you an indicative ratio at the first meeting once we have seen the documents, and the final ratio is fixed in the registered joint-venture agreement before any work begins.'],
                ['q' => 'What documents do I need to start?', 'a' => 'To have a first conversation, the title deed, the mutation and the latest tax receipt are enough. A full legal check on the title comes later, at our cost, before either side signs anything.'],
                ['q' => 'Who pays for the approvals and the construction?', 'a' => 'We do. The landowner contributes the land; RHL Properties funds the design, the RAJUK approval process, the construction and the marketing of the units.'],
                ['q' => 'How long does a project take?', 'a' => 'Typically 30 to 42 months from the signing of the agreement to handover, depending on the size of the building. The agreement names a specific handover date and the compensation payable if we miss it.'],
                ['q' => 'Do I get to choose which units are mine?', 'a' => 'Yes. Unit selection happens at the design stage, before booking opens, and your choices are listed by floor and unit number in the agreement.'],
                ['q' => 'Can I sell my share before handover?', 'a' => 'Yes, and many landowners do. We can market your units alongside our own through our sales team, on terms agreed in advance.'],
                ['q' => 'What happens if construction is delayed?', 'a' => 'The agreement sets out a monthly payment to the landowner for any delay beyond the contracted handover date, other than for reasons outside the control of either party, which are defined in the same clause.'],
                ['q' => 'Is my plot large enough?', 'a' => 'We generally look at plots of 5 katha and above in Gulshan, Banani, Dhanmondi, Tejgaon and the surrounding areas. Smaller or further out, send the details anyway — we will tell you honestly whether it works.'],
            ],
            'contact_eyebrow' => 'Submit Your Plot',
            'contact_heading' => 'Tell us about your land.',
            'contact_text' => 'Send the location, the size and the ownership status. We will come back within two working days with an indicative sharing ratio and what we would build on it.',
            // Sits beside the submit button, so it stays to one line or two.
            'form_note' => 'Reviewed in confidence. We reply within two working days.',
            'aside_heading' => 'Prefer to talk first?',
            'aside_text' => 'Call the office and ask for the land development desk. We can look at your plot on a map while you are on the phone.',
            'aside_confidence_heading' => 'In confidence',
            'aside_confidence_text' => 'Plot and ownership details are seen only by our own land team. We never pass them on, and we do not put your plot in front of another party without your say-so.',
        ];
    }
};
