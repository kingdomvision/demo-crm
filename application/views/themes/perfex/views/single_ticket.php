<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <?php if ($ticket->project_id) { ?>
    <div class="col-md-12 single-ticket-project-area">
        <div class="alert alert-info">
            <?php echo _l('ticket_linked_to_project', '<a href="' . site_url('clients/project/' . $ticket->project_id) . '"><b>' . e(get_project_name_by_id($ticket->project_id)) . '</b></a>') ; ?>
        </div>
    </div>
    <?php } ?>
    <?php set_ticket_open($ticket->clientread, $ticket->ticketid, false); ?>
    <?php echo form_hidden('ticket_id', $ticket->ticketid); ?>
    <div class="col-md-4 ticket-info">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-inline-flex tw-items-center">
            <?php echo _l('clients_single_ticket_information_heading'); ?>
        </h4>
        <div class="panel_s">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="tw-font-medium tw-my-0">
                            #<?php echo e($ticket->ticketid); ?> - <?php echo e($ticket->subject); ?>
                        </h4>
                        <div class="tw-divide-solid tw-divide-y tw-divide-neutral-100 tw-mt-4 [&>p:last-child]:tw-pb-0">
                            <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                <?php echo _l('clients_ticket_single_department', '<span class="tw-font-medium tw-text-neutral-700">' . e($ticket->department_name) . '</span>'); ?>
                            </p>
                            <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                <?php echo _l('clients_ticket_single_submitted', '<span class="tw-font-medium tw-text-neutral-700">' . e(_dt($ticket->date)) . '</span>'); ?>
                            </p>
                            <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                <?php echo _l('ticket_dt_submitter'); ?>:
                                <span class="tw-font-medium tw-text-neutral-700">
                                    <?php echo e($ticket->submitter); ?>
                                </span>
                            </p>
                            <div class="tw-py-2">
                                <div class="tw-flex tw-items-center tw-space-x-2">
                                    <span class="tw-text-neutral-500">
                                        <?php echo _l('clients_ticket_single_status'); ?>
                                    </span>
                                    <div class="ticket-status-inline">
                                        <span class="label tw-font-medium"
                                            style="background:<?php echo e($ticket->statuscolor); ?>">
                                            <?php echo e(ticket_status_translate($ticket->ticketstatusid)); ?>
                                            <?php if (get_option('allow_customer_to_change_ticket_status') == 1) { ?>
                                            <i
                                                class="fa-regular fa-pen-to-square pointer toggle-change-ticket-status"></i>
                                        </span>
                                        <?php } ?>
                                    </div>
                                    <?php if (can_change_ticket_status_in_clients_area()) { ?>
                                    <div class="ticket-status hide tw-flex-1">
                                        <div class="input-group input-group-sm">
                                            <select
                                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                                                id="ticket_status_single" class="form-control"
                                                name="ticket_status_single">
                                                <?php foreach ($ticket_statuses as $status) {
                                                if (!can_change_ticket_status_in_clients_area($status['ticketstatusid'])) {
                                                    continue;
                                                } ?>
                                                <option value="<?php echo e($status['ticketstatusid']); ?>" <?php if ($status['ticketstatusid'] == $ticket->ticketstatusid) {
                                                    echo 'selected';
                                                } ?>>
                                                    <?php echo e(ticket_status_translate($status['ticketstatusid'])); ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default toggle-change-ticket-status"
                                                    type="button">
                                                    <i class="fa fa-remove pointer"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                <?php echo _l('clients_ticket_single_priority', '<span class="tw-font-medium tw-text-neutral-700">' . e(ticket_priority_translate($ticket->priorityid)) . '</span>'); ?>
                            </p>
                            <?php if (get_option('services') == 1 && !empty($ticket->service_name)) { ?>
                            <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                <?php echo _l('service') . ': <span class="tw-font-medium tw-text-neutral-700">' . e($ticket->service_name) . '</span>'; ?>
                            </p>
                            <?php } ?>
                            <?php
                                $custom_fields = get_custom_fields('tickets', ['show_on_client_portal' => 1]);
                                foreach ($custom_fields as $field) {
                                    $cfValue = get_custom_field_value($ticket->ticketid, $field['id'], 'tickets');
                                    if (empty($cfValue)) {
                                        continue;
                                    } ?>
                                    <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                        <?php echo e($field['name']); ?>:
                                        <span class="tw-font-medium tw-text-neutral-700"><?php echo $cfValue; ?></span>
                                    </p>
                                    <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'ticket-reply']); ?>
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-inline-flex tw-items-center">
            <?php echo _l('clients_ticket_single_add_reply_heading'); ?>
        </h4>
        <div class="panel_s single-ticket-reply-area">
            <div class="panel-body">
                <div class="form-group">
                    <textarea name="message" class="form-control" rows="8"></textarea>
                    <?php echo form_error('message'); ?>
                </div>
                <div class="attachments_area">
                    <div class="attachments">
                        <div class="attachment tw-max-w-md">
                            <div class="form-group">
                                <label for="attachment"
                                    class="control-label"><?php echo _l('clients_ticket_attachments'); ?></label>
                                <div class="input-group">
                                    <input type="file"
                                        extension="<?php echo str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')); ?>"
                                        filesize="<?php echo file_upload_max_size(); ?>" class="form-control"
                                        name="attachments[0]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default add_more_attachments "
                                            data-max="<?php echo get_option('maximum_allowed_ticket_attachments'); ?>"
                                            type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-primary" type="submit" data-form="#ticket-reply" autocomplete="off"
                    data-loading-text="<?php echo _l('wait_text'); ?>">
                    <?php echo _l('ticket_single_add_reply'); ?>
                </button>
            </div>
        </div>
        <?php echo form_close(); ?>
        <div class="panel_s<?php echo $ticket->admin == null ? ' client-reply' : ''; ?>">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo _l('clients_single_ticket_string'); ?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 border-right tw-font-medium">
                        <?php if ($ticket->admin == null || $ticket->admin == 0) { ?>
                        <p><?php echo e($ticket->submitter); ?></p>
                        <?php } else { ?>
                        <p><?php echo e($ticket->opened_by); ?></p>
                        <p class="text-muted">
                            <?php echo _l('ticket_staff_string'); ?>
                        </p>
                        <?php } ?>
                    </div>
                    <div class="col-md-9">
                        <?php
                            if(empty($ticket->admin)) {
                                echo process_text_content_for_display($ticket->message);
                            } else {
                                echo check_for_links($ticket->message);
                            }
                        ?>
                        <br />
                        <p>-----------------------------</p>
                        <?php if (count($ticket->attachments) > 0) {
                                    echo '<hr />';
                                    foreach ($ticket->attachments as $attachment) { ?>
                        <?php
                            $path     = get_upload_path_by_type('ticket') . $ticket->ticketid . '/' . $attachment['file_name'];
                            $is_image = is_image($path);

                            if ($is_image) {
                                echo '<div class="preview_image">';
                            }
                            ?>
                        <a href="<?php echo site_url('download/file/ticket/' . $attachment['id']); ?>"
                            class="display-block mbot5">
                            <i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i>
                            <?php echo e($attachment['file_name']); ?>
                            <?php if ($is_image) { ?>
                            <img src="<?php echo site_url('download/preview_image?path=' . protected_file_url_by_path($path) . '&type=' . $attachment['filetype']); ?>"
                                class="mtop5">
                            <?php } ?>
                        </a>
                        <?php if ($is_image) {
                                echo '</div>';
                            }
                            echo '<hr />';
                        }
                                } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php foreach ($ticket_replies as $reply) { ?>
        <div class="panel_s<?php echo $reply['admin'] == null ? ' client-reply' : ''; ?>">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 border-right tw-font-medium">
                        <p><?php echo e($reply['submitter']); ?></p>
                        <p class="text-muted">
                            <?php if ($reply['admin'] !== null) {
                                echo _l('ticket_staff_string');
                            } ?>
                        </p>
                    </div>
                    <div class="col-md-9">
                        <?php
                            if(empty($reply['admin'])) {
                                echo process_text_content_for_display($reply['message']);
                            } else {
                                echo check_for_links($reply['message']);
                            }
                        ?>
                        <br />
                        <p>-----------------------------</p>
                        <?php if (count($reply['attachments']) > 0) {
                            echo '<hr />';
                            foreach ($reply['attachments'] as $attachment) {
                                $path     = get_upload_path_by_type('ticket') . $ticket->ticketid . '/' . $attachment['file_name'];
                                $is_image = is_image($path);
                                if ($is_image) {
                                    echo '<div class="preview_image">';
                                } ?>
                        <a href="<?php echo site_url('download/file/ticket/' . $attachment['id']); ?>"
                            class="inline-block mbot5">
                            <i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i>
                            <?php echo e($attachment['file_name']); ?>
                            <?php if ($is_image) { ?>
                            <img src="<?php echo site_url('download/preview_image?path=' . protected_file_url_by_path($path) . '&type=' . $attachment['filetype']); ?>"
                                class="mtop5">
                            <?php } ?>
                        </a>
                        <?php if ($is_image) {
                                    echo '</div>';
                                }
                                echo '<hr />';
                            }
                        } ?>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <span><?php echo e(_l('clients_single_ticket_replied', _dt($reply['date']))); ?></span>
            </div>
        </div>
        <?php } ?>
    </div>

</div>
<?php if (count($ticket_replies) > 1) { ?>
<a href="#top" id="toplink">↑</a>
<a href="#bot" id="botlink">↓</a>
<?php } ?>