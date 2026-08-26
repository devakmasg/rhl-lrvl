<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The Achievements page listed four awards and four certifications, every
     * one typed into Blade — the worst case on the site, since winning an
     * award is exactly the kind of news the owner wants to publish without a
     * developer.
     *
     * One table for both: they render as different components but carry the
     * same fields, and keeping them together means one admin screen. Awards
     * show their year; certifications are numbered by position, so `year` is
     * left null for them.
     */
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('kind')->index(); // award | certification
            $table->string('year')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        foreach ($this->seedRows() as $i => $row) {
            DB::table('achievements')->insert($row + [
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }

    /** Exactly what the page hardcoded, in the same order. */
    private function seedRows(): array
    {
        $awards = [
            ['year' => '2023', 'title' => 'Best Residential Developer', 'description' => "Bangladesh Real Estate & Housing Awards, recognising Gulshan Heights' design and on-time handover."],
            ['year' => '2022', 'title' => 'RAJUK Compliance Excellence', 'description' => 'Recognised for a fully clean approval record across every active development at the time.'],
            ['year' => '2021', 'title' => 'Best Commercial Project', 'description' => 'RHL Logistics Hub in Tejgaon, awarded for design efficiency in light-industrial development.'],
            ['year' => '2019', 'title' => 'Customer Trust Award', 'description' => 'REHAB Bangladesh recognition for handover satisfaction across residential deliveries.'],
        ];

        $certifications = [
            ['title' => 'REHAB Bangladesh Member', 'description' => 'Registered member of the Real Estate & Housing Association of Bangladesh since 2001.'],
            ['title' => 'RAJUK-Registered Developer', 'description' => 'Every current and completed development carries a verifiable RAJUK approval on file.'],
            ['title' => 'ISO 9001:2015 Certified', 'description' => 'Quality management certification covering our construction and project-delivery processes.'],
            ['title' => 'Fire Service & Civil Defence Clearance', 'description' => 'All occupied developments hold current fire-safety clearance certificates.'],
        ];

        $rows = [];

        foreach ($awards as $i => $row) {
            $rows[] = $row + ['kind' => 'award', 'sort_order' => $i + 1];
        }

        foreach ($certifications as $i => $row) {
            $rows[] = $row + ['kind' => 'certification', 'year' => null, 'sort_order' => $i + 1];
        }

        return $rows;
    }
};
