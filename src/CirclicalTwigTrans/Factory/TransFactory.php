<?php

namespace CirclicalTwigTrans\Factory;

use CirclicalTwigTrans\Model\Twig\Trans;

class TransFactory
{
    const DOMAIN = 'text_domain';

    public function __invoke($serviceLocator)
    {
        $config = $serviceLocator->get('config');

        foreach ($config['translator']['translation_file_patterns'] as $trcfg) {
            if (empty($trcfg[self::DOMAIN])) {
                $trcfg[self::DOMAIN] = 'default';
            }

            bindtextdomain($trcfg[self::DOMAIN], realpath($trcfg['base_dir']) . DIRECTORY_SEPARATOR);
            bind_textdomain_codeset($trcfg[self::DOMAIN], 'UTF-8');
        }

        $trans = new Trans(
            $serviceLocator->get('ZfcTwigRenderer'),
            $serviceLocator->get('translator')
        );

        return $trans;
    }
}
