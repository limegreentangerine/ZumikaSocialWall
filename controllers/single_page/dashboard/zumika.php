<?php

namespace Concrete\Package\ZumikaSocialWall\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;

class Zumika extends DashboardPageController
{
    public function view()
    {
        return $this->buildRedirect('/dashboard/zumika/settings');
    }
}
