<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Newsletter subscribers captured from the storefront footer
     * (Atelier Circle). Stores one row per unique email address with
     * a subscription status so subscribers can be managed without
     * sending any marketing emails.
     */
    public function up(): void
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email', 254)->unique();
            $table->string('status')->default('subscribed')->index();
            $table->timestamp('subscribed_at')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};
