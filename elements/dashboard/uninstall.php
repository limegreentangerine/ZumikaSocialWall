<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<div class="alert alert-danger">
    <?php echo t('This will remove the Zumika Social Wall block and every wall placed on the site.'); ?>
</div>

<div class="form-group">
    <p><?php echo t('Are you sure you want to uninstall this package?'); ?></p>
</div>
<div class="checkbox">
    <label>
        <input type="checkbox" name="confirm_uninstall" value="1">
        <?php echo t('Confirm Uninstall'); ?>
    </label>
</div>
