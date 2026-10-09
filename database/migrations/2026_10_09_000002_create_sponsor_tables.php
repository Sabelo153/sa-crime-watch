<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsors', function (Blueprint $t) {
            $t->id();
            $t->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $t->string('business', 100);
            $t->string('headline', 100);
            $t->string('description', 240);
            $t->string('url', 2048);
            $t->string('image_path')->nullable();
            $t->enum('placement', ['banner', 'sidebar']);
            $t->boolean('active')->default(false);
            $t->date('starts_on');
            $t->date('ends_on');
            $t->timestamps();
            $t->index(['placement', 'active', 'starts_on', 'ends_on']);
        });
        Schema::create('sponsor_inquiries', function (Blueprint $t) {
            $t->id();
            $t->string('business', 100);
            $t->text('email');
            $t->text('message');
            $t->enum('placement', ['banner', 'sidebar', 'either']);
            $t->timestamp('expires_at')->index();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsor_inquiries');
        Schema::dropIfExists('sponsors');
    }
};
