<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.0';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'jxfind',
    'title'        => 'jxFind - Text finder for Articles, Categories and Content Pages',
    'description'  => array(
                        'de' => 'Text-Finder für die Artikel, Kategories und CMS-Seiten.',
                        'en' => 'Text finder for Articles, Categories and Content Pages.'
                        ),
    'thumbnail'    => 'jxfind.png',
    'version'      => '0.1',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxFind',
    'email'        => 'jobarthel@gmail.com',
    'extend'       => array(
                        ),
    'files'        => array(
        'jxfind' => 'jxmods/jxfind/application/controllers/admin/jxfind.php'
                        ),
    'templates'    => array(
        'jxfind.tpl' => 'jxmods/jxfind/application/views/admin/tpl/jxfind.tpl'
                        ),
    'settings'     => array(
                        )
    );

?>
