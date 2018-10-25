<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';
 
/**
 * Module information
 * 
 * @link       https://github.com/job963/jxFind
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright  (C) Joachim Barthel 2012-2018
 * @version    0.3.0
 * 
 */
$aModule = array(
    'id'           => 'jxfind',
    'title'        => 'jxFind - Text Finder for Articles, Categories and Content Pages',
    'description'  => array(
                        'de' => 'Text-Finder für die Artikel, Kategories und CMS-Seiten.',
                        'en' => 'Text finder for Articles, Categories and Content Pages.'
                        ),
    'thumbnail'    => 'jxfind.png',
    'version'      => '0.3.0',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxFind',
    'email'        => 'jobarthel@gmail.com',
    'extend'       => array(
                        ),
    'controllers'   => array(
                        'jxfind' => JxMods\JxFind\Application\Controller\Admin\JxFind::class
                            ),
    'files'        => array(
                        'jxfind' => 'jxmods/jxfind/application/controllers/admin/jxfind.php'
                        ),
    'templates'    => array(
                        'jxfind.tpl' => 'jxmods/jxfind/application/views/admin/tpl/jxfind.tpl'
                        ),
    'blocks'       => array(
                        array(
                            'template' => 'header.tpl', 
                            'block'    => 'admin_header_inclinks',                     
                            'file'     => '/out/blocks/admin_header_inclinks.tpl'
                          )
                        ),
    'settings'     => array(
                        )
        );

?>
