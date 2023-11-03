<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mailcoach_segments', function (Blueprint $table) {
            $table->json('stored_conditions')->nullable()->after('email_list_id');
        });

        Schema::table('mailcoach_campaigns', function (Blueprint $table) {
            $table->boolean('disable_webview')->default(false)->after('show_publicly');

            $table->after('summary_mail_sent_at', function (Blueprint $table) {
                $table->integer('split_test_wait_time_in_minutes')->nullable();
                $table->integer('split_test_split_size_percentage')->nullable();
                $table->timestamp('split_test_started_at')->nullable();
                $table->foreignId('split_test_winning_content_item_id')->nullable();
            });
        });

        Schema::table('mailcoach_sends', function (Blueprint $table) {
            $table->foreignId('content_item_id')
                ->nullable()
                ->after('transport_message_id');
        });

        Schema::table('mailcoach_transactional_mail_log_items', function (Blueprint $table) {
            $table->boolean('fake')->default(false)->after('attachments');
        });

        Schema::create('mailcoach_content_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->morphs('model');

            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();

            $table->string('reply_to_email')->nullable();
            $table->string('reply_to_name')->nullable();

            $table->string('subject')->nullable();

            $table->unsignedBigInteger('template_id')->nullable();
            $table->longText('html')->nullable();
            $table->longText('structured_html')->nullable();
            $table->longText('email_html')->nullable();
            $table->longText('webview_html')->nullable();

            $table->string('mailable_class')->nullable();
            $table->json('mailable_arguments')->nullable();

            $table->boolean('utm_tags')->default(false);
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->boolean('add_subscriber_tags')->default(false);
            $table->boolean('add_subscriber_link_tags')->default(false);

            $table->timestamp('all_sends_created_at')->nullable();
            $table->timestamp('all_sends_dispatched_at')->nullable();

            $table->integer('sent_to_number_of_subscribers')->default(0);
            $table->integer('open_count')->default(0);
            $table->integer('unique_open_count')->default(0);
            $table->integer('open_rate')->default(0);
            $table->integer('click_count')->default(0);
            $table->integer('unique_click_count')->default(0);
            $table->integer('click_rate')->default(0);
            $table->integer('unsubscribe_count')->default(0);
            $table->integer('unsubscribe_rate')->default(0);
            $table->integer('bounce_count')->default(0);
            $table->integer('bounce_rate')->default(0);
            $table->timestamp('statistics_calculated_at')->nullable();

            $table->timestamps();
        });

        Schema::create('mailcoach_links', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table
                ->foreignId('content_item_id')
                ->constrained('mailcoach_content_items')
                ->cascadeOnDelete();

            $table->string('url', 2048);
            $table->integer('click_count')->default(0);
            $table->integer('unique_click_count')->default(0);
            $table->nullableTimestamps();
        });

        Schema::create('mailcoach_clicks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table
                ->foreignId('send_id')
                ->constrained('mailcoach_sends')
                ->cascadeOnDelete();

            $table
                ->foreignId('link_id')
                ->constrained('mailcoach_links')
                ->cascadeOnDelete();

            $table
                ->foreignId('subscriber_id')
                ->nullable()
                ->constrained('mailcoach_subscribers')
                ->cascadeOnDelete();

            $table->nullableTimestamps();
        });

        Schema::create('mailcoach_opens', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table
                ->foreignId('send_id')
                ->constrained('mailcoach_sends')
                ->cascadeOnDelete();

            $table
                ->foreignId('content_item_id')
                ->nullable()
                ->constrained('mailcoach_content_items')
                ->cascadeOnDelete();

            $table
                ->foreignId('subscriber_id')
                ->nullable()
                ->constrained('mailcoach_subscribers')
                ->cascadeOnDelete();

            $table->nullableTimestamps();
        });

        Schema::create('mailcoach_unsubscribes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table
                ->foreignId('content_item_id')
                ->constrained('mailcoach_content_items')
                ->cascadeOnDelete();

            $table
                ->foreignId('subscriber_id')
                ->constrained('mailcoach_subscribers')
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('mailcoach_subscriber_exports', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('status');

            $table
                ->foreignId('email_list_id')
                ->nullable()
                ->constrained('mailcoach_email_lists')
                ->cascadeOnDelete();

            $table->json('filters')->nullable();
            $table->integer('exported_subscribers_count')->default(0);
            $table->text('errors')->nullable();
            $table->timestamps();
        });

        Schema::create('mailcoach_suppressions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('email')->unique();
            $table->string('reason');
            $table->timestamps();
        });
    }
}
