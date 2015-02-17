<?php
namespace CirclicalTwigTrans\Factory;

use CirclicalTwigTrans\Model\Twig\Trans;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * 
     * @return Trans|mixed
     */
    public function createService( ServiceLocatorInterface $serviceLocator )
    {
        /* @var $viewRenderer \Zend\View\Renderer\PhpRenderer */
        $viewRenderer = $serviceLocator->get('ViewRenderer');

        /* @var $plugIn \Zend\I18n\View\Helper\Translate */
        $plugIn = $viewRenderer->plugin('translate');

        return new Trans(
            $serviceLocator->get('ZfcTwigRenderer'),
            $plugIn
        );
    }
}
