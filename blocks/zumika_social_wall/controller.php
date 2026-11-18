<?php

namespace Concrete\Package\ZumikaSocialWall\Block\ZumikaSocialWall;

use ZumikaSocialWall\Api\Zumika;
use ZumikaSocialWall\Config\ZumikaEnv;
use Concrete\Core\Block\BlockController;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected $btTable = 'btZumikaSocialWall';
    protected $btInterfaceWidth = 480;
    protected $btInterfaceHeight = 360;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btDefaultSet = 'zumika_social_wall';

    public $feedKey = '';
    public $layout = '';
    public $postLimit = 0;

    public function getBlockTypeName()
    {
        return t('Zumika Social Wall');
    }

    public function getBlockTypeDescription()
    {
        return t('Show a feed of social posts collected by Zumika.');
    }

    public function add()
    {
        $this->loadHelpers();
    }

    public function edit() 
    {
        $this->loadHelpers();
    }

    public function loadHelpers()
    {
        $this->set('result', $this->app->make(Zumika::class)->getFeeds());
        $this->set('layouts', [
            '' => t('As designed in Zumika'),
            'waterfall' => t('Waterfall'),
            'colony' => t('Colony'),
            'migration' => t('Migration'),
            'canopy' => t('Canopy'),
            'savannah' => t('Savannah'),
            'trail' => t('Trail'),
        ]);
    }

    /**
     * Runs on every render, including cached block output (view() is skipped when output is cached).
     * String footer items are de-duplicated, so the script loads once however many walls a page has.
     */
    public function registerViewAssets($outputContent = '')
    {
        $this->addFooterItem(sprintf('<script async src="%s/embed.js"></script>', h(ZumikaEnv::urlOrDefault())));
    }

    public function validate($args)
    {
        $e = $this->app->make('helper/validation/error');
        if (trim((string) ($args['feedKey'] ?? '')) === '') {
            $e->add(t('Choose a feed.'));
        }

        return $e;
    }

    public function save($args)
    {
        $layout = (string) ($args['layout'] ?? '');
        parent::save([
            'feedKey' => preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($args['feedKey'] ?? '')),
            'layout' => in_array($layout, ['waterfall', 'colony', 'migration', 'canopy', 'savannah', 'trail'], true) ? $layout : '',
            'postLimit' => max(0, min(50, (int) ($args['postLimit'] ?? 0))),
        ]);
    }
}
