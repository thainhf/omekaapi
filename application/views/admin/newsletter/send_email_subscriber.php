<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('send_email_subscriber'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('admin_controller/send_email_subscriber_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>
                <div class="form-group" style="margin-bottom: 10px;">
                    <label><?php echo trans('to'); ?></label>&nbsp;&nbsp;&nbsp;<strong><?php echo $subscriber->email; ?></strong>
                    <input type="hidden" name="receiver" value="<?php echo trim($subscriber->email); ?>">
                </div>
                <div class="form-group">
                    <label><?php echo trans('subject'); ?></label>
                    <input type="text" name="subject" class="form-control" placeholder="<?php echo trans('subject'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                </div>

                <div class="form-group">
                    <label><?php echo trans('content'); ?></label>
                    <div class="row">
                        <div class="col-sm-12 editor-buttons">
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#file_manager_image" data-image-type="editor"><i class="fa fa-image"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("add_image"); ?></button>
                        </div>
                    </div>
                    <textarea class="tinyMCE form-control" name="message"></textarea>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('send_email'); ?></button>
            </div>
            <!-- /.box-footer -->

            <?php echo form_close(); ?><!-- form end -->

        </div>
        <!-- /.box -->
    </div>
</div>

<?php $this->load->view('admin/file-manager/_load_file_manager', ['load_images' => true, 'load_files' => false, 'load_videos' => false, 'load_audios' => false]); ?>
