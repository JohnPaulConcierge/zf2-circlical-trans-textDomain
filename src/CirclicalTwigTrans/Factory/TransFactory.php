<?php
namespace CirclicalTwigTrans\Factory;

use CirclicalTwigTrans\Model\Twig\Trans;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransFactory implements FactoryInterface
{
    public function createService( ServiceLocatorInterface $serviceLocator )
    {
        return new Trans(
            $serviceLocator->get('ZfcTwigRenderer'),
            $serviceLocator->get('MvcTranslator' )
        );
    }
}
