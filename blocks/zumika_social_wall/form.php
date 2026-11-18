<?php
defined('C5_EXECUTE') or die('Access Denied.');
/** @var \Concrete\Core\Form\Service\Form $form */
$feedKey = $feedKey ?? '';
$layout = $layout ?? '';
$postLimit = $postLimit ?? 0;
$options = ['' => t('Choose a feed…')];
foreach ($result['feeds'] as $feed) {
    $options[$feed['key']] = $feed['name'];
}
// Keep a previously chosen feed selectable even if the API is unreachable right now.
if ($feedKey !== '' && !isset($options[$feedKey])) {
    $options[$feedKey] = $feedKey;
}
?>
<?php if ($result['error'] !== '') { ?>
    <div class="alert alert-warning"><?php echo h($result['error']) ?></div>
<?php } ?>

<div class="form-group mb-3">
    <?php echo $form->label('feedKey', t('Feed')) ?>
    <?php echo $form->select('feedKey', $options, $feedKey) ?>
</div>

<div class="form-group mb-3">
    <?php echo $form->label('layout', t('Layout')) ?>
    <?php echo $form->select('layout', $layouts, $layout) ?>
</div>

<div class="form-group mb-3">
    <?php echo $form->label('postLimit', t('Posts per load')) ?>
    <?php echo $form->number('postLimit', $postLimit, ['min' => 0, 'max' => 50]) ?>
    <div class="form-text"><?php echo t('0 uses the number set in Zumika.') ?></div>
</div>
