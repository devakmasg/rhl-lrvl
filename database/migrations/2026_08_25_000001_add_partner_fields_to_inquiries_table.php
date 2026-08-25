<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The partners page collects a different shape of lead (landowner vs
     * investor, an area, a plot size or budget) than the project enquiry
     * form. Keeping them in one table means one inbox for the admin; these
     * columns are what tells the two apart.
     */
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('type')->default('general')->after('reference');
            $table->string('partner_role')->nullable()->after('project_name');
            $table->string('area')->nullable()->after('partner_role');
            $table->string('budget')->nullable()->after('area');
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['type', 'partner_role', 'area', 'budget']);
        });
    }
};
