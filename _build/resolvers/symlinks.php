<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/msVKMarket/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/msvkmarket')) {
            $cache->deleteTree(
                $dev . 'assets/components/msvkmarket/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/msvkmarket/', $dev . 'assets/components/msvkmarket');
        }
        if (!is_link($dev . 'core/components/msvkmarket')) {
            $cache->deleteTree(
                $dev . 'core/components/msvkmarket/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/msvkmarket/', $dev . 'core/components/msvkmarket');
        }
    }
}

return true;