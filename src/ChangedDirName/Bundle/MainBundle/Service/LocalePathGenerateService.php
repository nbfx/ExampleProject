<?php

/* All inner names have been changed for security reasons. */

namespace ChangedDirName\Bundle\MainBundle\Service;

use ChangedDirName\Bundle\MainBundle\Entity\Locale;
use Doctrine\ORM\EntityManager;
use ChangedDirName\Bundle\MainBundle\Repository\LocaleRepository;
use Symfony\Component\Routing\Router;

/**
 * Class LocalePathGenerateService
 *
 * @package ChangedDirName\Bundle\MainBundle\Service
 */
class LocalePathGenerateService
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * LocaleActionsManager constructor.
     *
     * @param EntityManager $entityManager
     * @param Router        $router
     * @param string        $defaultLocale
     * @param array         $locales
     * @param string        $flagIconPath
     */
    public function __construct(EntityManager $entityManager, Router $router, $defaultLocale, $locales, $flagIconPath)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->defaultLocale = $defaultLocale;
        $this->locales = $locales;
        $this->flagIconPath = $flagIconPath;
    }

    /**
     * Get Locale Repository
     *
     * @return LocaleRepository
     */
    public function getLocaleRepository()
    {
        return $this->entityManager->getRepository('ChangedDirNameMainBundle:Locale');
    }

    /**
     * Returns array with current and available languages links
     *
     * @param string $routeName
     * @param array  $routeParams
     * @param string $currentLocaleName
     * @return array
     * @throws \Exception
     */
    public function getPathForPageWithLocale($routeName, array $routeParams, $currentLocaleName = 'en')
    {
        if (!in_array($currentLocaleName, $this->locales)) {
            throw new \Exception("Locale `$currentLocaleName` not exists.", 400);
        }
        $locales = $this->getLocaleRepository()->findBy(['locale' => $this->locales, 'enabled' => true], ['locale' => 'ASC']);

        return $this->getLocalesDataForLanguageSwitcher($routeName, $routeParams, $currentLocaleName, $locales);
    }

    /**
     * Return locales plain list
     *
     * @return array
     */
    public function getPlainList()
    {
        $locales = [];
        foreach ($this->getLocaleRepository()->findAll() as $locale) {
            $locales[] = $locale->getLocale();
        }

        return $locales;
    }

    /**
     * Return locales data for language switcher
     *
     * @param string $routeName
     * @param array  $routeParams
     * @param string $currentLocaleName
     * @param array  $locales
     * @return array
     */
    private function getLocalesDataForLanguageSwitcher($routeName, $routeParams, $currentLocaleName, $locales)
    {
        $localesData = [
            'currentLocale' => [],
            'links' => [],
        ];

        /** @var $locale Locale */
        foreach ($locales as $locale) {
            if ($currentLocaleName == $locale->getLocale()) {
                $localesData['currentLocale'] = [
                    'flagIcon' => $this->flagIconPath.$locale->getFlagIcon(),
                    'title' => $locale->getName(),
                ];
                continue;
            }

            $localesData['links'][] = [
                'path' => $this->router->generate(
                    $routeName,
                    array_merge($routeParams, ['_locale' => $locale->getLocale()])
                ),
                'flagIcon' => $this->flagIconPath.$locale->getFlagIcon(),
                'title' => $locale->getName(),
            ];
        }

        return $localesData;
    }
}
