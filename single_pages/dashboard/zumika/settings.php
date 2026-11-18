<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<p class="text-muted">
    <?php echo t('These settings are read from the site\'s %s file and cannot be changed here.', '<code>.env</code>'); ?>
</p>

<?php foreach ($settings as $setting) { ?>
    <?php if ($setting['populated']) { ?>
        <div class="alert alert-success">
            <strong><?php echo h($setting['label']); ?></strong>
            <code><?php echo h($setting['env']); ?></code>:
            <?php echo h($setting['display']); ?>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">
            <strong><?php echo h($setting['label']); ?></strong>
            <?php echo t('has not been populated (or is not valid). Add %s to your .env file.', '<code>' . h($setting['env']) . '</code>'); ?>
            <?php if ($setting['default'] !== null) { ?>
                <?php echo t('Using the default: %s.', '<code>' . h($setting['default']) . '</code>'); ?>
            <?php } ?>
        </div>
    <?php } ?>
<?php } ?>

<p class="help-block">
    <?php echo t('Create an API key in your Zumika workspace under API & plugins. Only set %s if Zumika support gave you a different address.', '<code>' . h($settings['url']['env']) . '</code>'); ?>
</p>

<?php if ($result !== null) { ?>
    <hr>
    <h4><?php echo t('Connection'); ?></h4>
    <?php if ($result['error'] !== '') { ?>
        <div class="alert alert-danger"><?php echo h($result['error']); ?></div>
    <?php } elseif (!$result['feeds']) { ?>
        <div class="alert alert-info"><?php echo t('Connected. This workspace has no feeds yet; create one in Zumika first.'); ?></div>
    <?php } else { ?>
        <p><?php echo t('Connected. Your feeds:'); ?></p>
        <ul>
            <?php foreach ($result['feeds'] as $feed) { ?>
                <li><?php echo h($feed['name']); ?></li>
            <?php } ?>
        </ul>
        <p class="text-muted"><?php echo t('Add the “Zumika Social Wall” block to a page and choose one of these feeds.'); ?></p>
    <?php } ?>
<?php } ?>
