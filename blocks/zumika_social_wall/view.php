<?php
defined('C5_EXECUTE') or die('Access Denied.');
if ($feedKey === '') {
    return;
}
?>
<div data-zumika-feed="<?php echo h($feedKey) ?>"<?php echo $layout !== '' ? ' data-zumika-layout="' . h($layout) . '"' : '' ?><?php echo (int) $postLimit > 0 ? ' data-zumika-limit="' . (int) $postLimit . '"' : '' ?>></div>
