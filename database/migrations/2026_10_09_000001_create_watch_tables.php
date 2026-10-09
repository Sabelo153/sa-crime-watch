<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->boolean('is_moderator')->default(false));
        Schema::create('communities', function (Blueprint $t) {
            $t->id();
            $t->string('province', 40);
            $t->string('municipality', 100);
            $t->string('name', 100);
            $t->timestamps();
            $t->unique(['province', 'municipality', 'name']);
        });
        Schema::create('community_user', function (Blueprint $t) {
            $t->foreignId('community_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->primary(['community_id', 'user_id']);
        });
        Schema::create('vehicle_reports', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->restrictOnDelete();
            $t->foreignId('community_id')->constrained()->restrictOnDelete();
            $t->string('registration', 20)->index();
            $t->string('make', 60);
            $t->string('model', 60);
            $t->string('colour', 40);
            $t->date('stolen_on');
            $t->text('description');
            $t->string('status', 20)->default('pending');
            $t->timestamps();
            $t->index(['status', 'community_id', 'stolen_on']);
            $t->index(['status', 'make', 'colour']);
        });
        Schema::create('report_photos', function (Blueprint $t) {
            $t->id();
            $t->foreignId('vehicle_report_id')->constrained()->cascadeOnDelete();
            $t->string('path')->unique();
            $t->string('mime', 30);
            $t->timestamps();
        });
        Schema::create('tips', function (Blueprint $t) {
            $t->id();
            $t->foreignId('vehicle_report_id')->constrained()->cascadeOnDelete();
            $t->text('message');
            $t->text('contact')->nullable();
            $t->timestamp('expires_at')->index();
            $t->timestamps();
        });
        Schema::create('moderation_actions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('vehicle_report_id')->constrained()->cascadeOnDelete();
            $t->foreignId('moderator_id')->constrained('users')->restrictOnDelete();
            $t->string('from_status', 20);
            $t->string('to_status', 20);
            $t->string('reason', 500);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['moderation_actions', 'tips', 'report_photos', 'vehicle_reports', 'community_user', 'communities'] as $table) {
            Schema::dropIfExists($table);
        } Schema::table('users', fn (Blueprint $t) => $t->dropColumn('is_moderator'));
    }
};
