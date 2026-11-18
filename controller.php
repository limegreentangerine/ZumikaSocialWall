<?php

namespace Concrete\Package\ZumikaSocialWall;

use Concrete\Core\Page\Page;
use Concrete\Core\Entity\Package;
use ClassKit\Package\PackageController;
use ClassKit\Package\Traits\{BlockTrait, PageTrait};

class Controller extends PackageController
{
    use BlockTrait;
    use PageTrait;

    /**
     * The packages handle.
     * Note that this must be unique in the
     * entire concrete5 package ecosystem.
     *
     * @var string
     */
    protected $pkgHandle = 'zumika_social_wall';

    /**
     * The packages version.
     *
     * @var string
     */
    protected $pkgVersion = '2.0.0';

    /**
     * The minimum Concrete version compatible with the package.
     *
     * @var string
     */
    protected $appVersionRequired = '9.5.0';

    /**
     * The minimum PHP version compatible with the package.
     *
     * @var string
     */
    protected $phpVersionRequired = '8.4';

    /**
     * Package service providers to register.
     *
     * @var array
     */
    protected $providers = [];

    /**
     * An array describing the package dependencies, keyed by package handle.
     *
     * @var array
     */
    protected $packageDependencies = [
        'class_kit' => true,
    ];

    /**
     * Package class autoloader registrations.
     *
     * @var array
     */
    protected $pkgAutoloaderRegistries = [
        'src' => '\ZumikaSocialWall',
    ];

    /**
     * Package tasks to register.
     *
     * @var array
     */
    protected $tasks = [];

    public function getPackageName()
    {
        return t('Zumika Social Wall');
    }

    public function getPackageDescription()
    {
        return t('Show a Zumika social wall on your site.');
    }

    public function installOrUpgrade(Package $pkg)
    {
        // Add blocks
        $this->autoInstallBlocks($pkg);

        // 1.x kept its settings at /dashboard/system/zumika; that controller no longer exists
        $legacy = Page::getByPath('/dashboard/system/zumika');
        if (is_object($legacy) && !$legacy->isError() && (int) $legacy->getPackageID() === (int) $pkg->getPackageID()) {
            $legacy->delete();
        }

        // dashboard pages
        $this->addSinglePage('/dashboard/zumika', $pkg, t('Zumika'));
        $this->addSinglePage('/dashboard/zumika/settings', $pkg, t('Settings'));
    }

    public function registerRoutes(): void {}

    public function registerEvents(): void {}
}
