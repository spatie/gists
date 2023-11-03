<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration
{
    public function up(): void
    {
        Schema::table('mailcoach_segments', function (Blueprint $table) {
            $table->dropColumn(['all_positive_tags_required', 'all_negative_tags_required']);
        });

        Schema::table('mailcoach_campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'from_email',
                'from_name',
                'reply_to_email',
                'reply_to_name',
                'subject',
                'template_id',
                'html',
                'structured_html',
                'email_html',
                'webview_html',
                'mailable_class',
                'mailable_arguments',
                'utm_tags',
                'sent_to_number_of_subscribers',
                'add_subscriber_tags',
                'add_subscriber_link_tags',
                'open_count',
                'unique_open_count',
                'open_rate',
                'click_count',
                'unique_click_count',
                'click_rate',
                'unsubscribe_count',
                'unsubscribe_rate',
                'bounce_count',
                'bounce_rate',
                'statistics_calculated_at',
                'last_modified_at',
                'all_sends_created_at',
                'all_sends_dispatched_at',
            ]);
        });

        Schema::table('mailcoach_automation_mails', function (Blueprint $table) {
            $table->dropColumn([
                'from_email',
                'from_name',
                'reply_to_email',
                'reply_to_name',
                'subject',
                'template_id',
                'html',
                'structured_html',
                'email_html',
                'webview_html',
                'mailable_class',
                'mailable_arguments',
                'utm_tags',
                'sent_to_number_of_subscribers',
                'add_subscriber_tags',
                'add_subscriber_link_tags',
                'open_count',
                'unique_open_count',
                'open_rate',
                'click_count',
                'unique_click_count',
                'click_rate',
                'unsubscribe_count',
                'unsubscribe_rate',
                'bounce_count',
                'bounce_rate',
                'statistics_calculated_at',
                'last_modified_at',
            ]);
        });

        Schema::table('mailcoach_transactional_mail_log_items', function (Blueprint $table) {
            $table->dropColumn([
                'subject',
                'body',
                'structured_html',
            ]);
        });

        Schema::table('mailcoach_sends', function (Blueprint $table) {
            $table->dropColumn([
                'campaign_id',
                'automation_mail_id',
                'transactional_mail_log_item_id',
            ]);
        });

        Schema::drop('mailcoach_positive_segment_tags');
        Schema::drop('mailcoach_negative_segment_tags');

        Schema::drop('mailcoach_campaign_links');
        Schema::drop('mailcoach_campaign_clicks');
        Schema::drop('mailcoach_campaign_opens');
        Schema::drop('mailcoach_campaign_unsubscribes');
        Schema::drop('mailcoach_automation_mail_links');
        Schema::drop('mailcoach_automation_mail_clicks');
        Schema::drop('mailcoach_automation_mail_opens');
        Schema::drop('mailcoach_automation_mail_unsubscribes');

        Schema::drop('mailcoach_transactional_mail_clicks');
        Schema::drop('mailcoach_transactional_mail_opens');
    }
}
