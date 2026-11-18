<?php

namespace Concrete\Package\ZumikaSocialWall\Controller\SinglePage\Dashboard\Zumika;

use ZumikaSocialWall\Api\Zumika;
use ZumikaSocialWall\Config\ZumikaEnv;
use Concrete\Core\Page\Controller\DashboardPageController;

/**
 * Read-only: the Zumika settings live in the site's .env file, so this page only reports which are populated
 * and, when a key is set, tests it by listing the workspace's feeds.
 */
class Settings extends DashboardPageController
{
    public function view()
    {
        $apiKey = ZumikaEnv::apiKey();
        $url = ZumikaEnv::url();

        $this->set('settings', [
            'apiKey' => [
                'env' => ZumikaEnv::API_KEY,
                'label' => t('API Key'),
                'populated' => $apiKey !== null,
                // The key is a secret, so only report that it is set
                'display' => $apiKey !== null ? str_repeat('•', 8) : '',
                'default' => null,
            ],
            'url' => [
                'env' => ZumikaEnv::URL,
                'label' => t('Zumika Address'),
                'populated' => $url !== null,
                'display' => $url ?? '',
                'default' => ZumikaEnv::DEFAULT_URL,
            ],
        ]);
        $this->set('result', $apiKey !== null ? $this->app->make(Zumika::class)->getFeeds() : null);
    }
}
