<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('ddd_cup_event', static function (Blueprint $table): void {
            $table->id('incrementalId');
            $table->uuid('id')->nullable(false)->index()->unique();
            $table->uuid('cupId')->nullable(false)->index();
            $table->uuid('eventId')->nullable(false)->index();
            $table->json('groupsDistances')->nullable(false);
            $table->float('points')->nullable(false);
            $table->boolean('disabled')->nullable(false)->index();
            $table->timestamp('createdAt')->nullable(false);
            $table->uuid('createdBy')->nullable(false);
            $table->timestamp('updatedAt')->nullable(false);
            $table->uuid('updatedBy')->nullable(false);

            $table->unique(['cupId', 'eventId', 'disabled']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ddd_cup_event');
    }
};
