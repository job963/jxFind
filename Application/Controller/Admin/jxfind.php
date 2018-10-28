<?php

/*
 *    This file is part of the module jxSales for OXID eShop Community Edition.
 *
 *    The module jxSales for OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    The module OxProbs for OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      https://github.com/job963/jxSales
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) Joachim Barthel 2014-2018
 *
 */

namespace JxMods\JxFind\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Module\Module;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\TableViewNameGenerator;


class JxFind extends AdminDetailsController
{

    /**
     * Find search text in Articles, Categories and CMS Pages
     * 
     * @return string Filename of template file
     */
    public function render()
    {

        /**
         * @var Request $request 
         */
        $request = Registry::getRequest();

        /**
         * @var Module $module
         */
        $module = oxNew(Module::class);

        $jxSearchValue = $request->getRequestParameter( 'jxfind_srcval' );
        $jxSearchLang = $request->getRequestParameter( 'jxfind_lang' );
        if (empty($jxSearchValue))
            $jxSearchValue = "";
        else
            $jxSearchValue = strtoupper($jxSearchValue);
        
        $aLangs = $this->_getAllLanguages();
        
        $this->_aViewData["jxfind_srcval"] = $jxSearchValue;
        $this->_aViewData["jxfind_lang"] = $jxSearchLang;
        $this->_aViewData["aLangs"] = $aLangs;

        $this->_aViewData["aProdResults"] = $this->_findProducts( $jxSearchValue, $jxSearchLang );
        $this->_aViewData["aCatResults"] = $this->_findCategories( $jxSearchValue, $jxSearchLang );
        $this->_aViewData["aCmsResults"] = $this->_findCmsPages( $jxSearchValue, $jxSearchLang );

        $module->load( 'jxfind' );
        $this->_aViewData['sModuleId'] = $module->getId();
        $this->_aViewData['sModuleVersion'] = $module->getInfo('version');
        
        parent::render();
        
        return "jxfind.tpl";
    }
    
    
    
    /**
     * Gets all languages of the shop
     * 
     * @return array 
     */
    private function _getAllLanguages()
    {
        $aConfigLanguageParams = $this->getConfig()->getConfigParam( 'aLanguageParams' );
        $aConfigLanguages = $this->getConfig()->getConfigParam( 'aLanguages' );
        
        $aLanguages = array();
        foreach ($aConfigLanguageParams as $key => $aConfigLanguageParam) {
            $aLanguages[] = array('id' => $aConfigLanguageParam['baseId'], 'title' => $aConfigLanguages[$key]);
        }
        
        return $aLanguages;
    }
    
    
    // not used at the moment
    public function downloadResult()
    {
        switch ( $this->getConfig()->getConfigParam( 'sJxSalesSeparator' ) ) {
            case 'comma':
                $sSep = ',';
                break;
            case 'semicolon':
                $sSep = ';';
                break;
            case 'tab':
                $sSep = chr(9);
                break;
            case 'pipe':
                $sSep = '|';
                break;
            case 'tilde':
                $sSep = '~';
                break;
            default:
                $sSep = ',';
                break;
        }
        if ( $this->getConfig()->getConfigParam( 'bJxSalesQuote' ) ) {
            $sBegin = '"';
            $sSep   = '"' . $sSep . '"';
            $sEnd   = '"';
        }
        
        $jxSearchValue = $request->getRequestParameter( 'jxfind_srcval' );
        if (empty($jxSearchValue))
            $jxSearchValue = "";
        else
            $jxSearchValue = strtoupper($jxSearchValue);
        
        $aOrders = array();
        $aOrders = $this->_retrieveData($sSrcVal);

        $aOxid = $request->getRequestParameter( 'jxsales_oxid' ); 
        
        $sContent = '';
        if ( $this->getConfig()->getConfigParam( 'bJxSalesHeader' ) ) {
            $aHeader = array_keys($aOrders[0]);
            $sContent .= $sBegin . implode($sSep, $aHeader) . $sEnd . chr(13);
        }
        foreach ($aOrders as $aOrder) {
            if ( in_array($aOrder['orderartid'], $aOxid) ) {
                $sContent .= $sBegin . implode($sSep, $aOrder) . $sEnd . chr(13);
            }
        }

        header('Content-Type: text/plain');
        header('content-length: '.strlen($sContent));
        header('Content-Disposition: attachment; filename="sales-report.csv"');
        echo $sContent;
        
        exit();

        return;
    }

    
    /**
     * Finds articles according the given string and language
     * 
     * @param string $sSrcVal
     * @param string $iSrcLang
     * 
     * @return array Findings in articles
     */
    private function _findProducts($sSrcVal, $iSrcLang)
    {
        $oxvArticles = getViewName( 'oxarticles', $iSrcLang );
        $oxvArtextends = getViewName( 'oxartextends', $iSrcLang );
        
        $limit = "0, 100 ";
        if (empty($sSrcVal)) {
            $limit = "0,0 ";
        }
        
        $sSql = "SELECT a.oxid AS oxid, a.oxactive As oxactive, a.oxartnum AS oxartnum, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, "
                . "a.oxshortdesc AS oxshortdesc, a.oxsearchkeys AS oxsearchkeys, e.oxlongdesc AS oxlongdesc "
                . "FROM $oxvArticles a, $oxvArtextends e "
                . "WHERE "
                    . "a.oxid = e.oxid "
                    . "AND a.oxactive = 1 "
                    . "AND ("
                        . "a.oxtitle LIKE '%$sSrcVal%' "
                        . "OR a.oxvarselect LIKE '%$sSrcVal%' "
                        . "OR a.oxshortdesc LIKE '%$sSrcVal%' "
                        . "OR a.oxsearchkeys LIKE '%$sSrcVal%' "
                        . "OR e.oxlongdesc LIKE '%$sSrcVal%' "
                    . ") "
                . "LIMIT " . $limit;
        
        return $this->_fetchAllRecords($sSql);
    }

    
    /**
     * Finds categories according the given string and language
     * 
     * @param string $sSrcVal
     * @param string $iSrcLang
     * 
     * @return array Findings in categories
     */
    private function _findCategories($sSrcVal, $iSrcLang)
    {
        $oxvCategories = getViewName( 'oxcategories', $iSrcLang );
        
        $limit = "0, 100 ";
        if (empty($sSrcVal)) {
            $limit = "0,0 ";
        }

        $sSql = "SELECT c.oxid AS oxid, c.oxactive AS oxactive, c.oxtitle AS oxtitle, c.oxdesc AS oxdesc, c.oxlongdesc AS oxlongdesc "
                . "FROM $oxvCategories c "
                . "WHERE c.oxactive = 1 "
                    . "AND (c.oxtitle LIKE '%$sSrcVal%' "
                    . "OR c.oxdesc LIKE '%$sSrcVal%' "
                    . "OR c.oxlongdesc LIKE '%$sSrcVal%' ) "
                . "LIMIT " . $limit;

        return $this->_fetchAllRecords($sSql);
    }

    
    /**
     * Finds CMS pages according the given string and language
     * 
     * @param string $sSrcVal
     * @param string $iSrcLang
     * 
     * @return array Findings in CMS pages
     */
    private function _findCmsPages($sSrcVal, $iSrcLang)
    {
        $oxvContents = getViewName( 'oxcontents', $iSrcLang );
        
        $limit = "0, 100 ";
        if (empty($sSrcVal)) {
            $limit = "0,0 ";
        }

        $sSql = "SELECT c.oxid AS oxid, c.oxactive AS oxactive, c.oxloadid AS oxloadid, c.oxtitle AS oxtitle, c.oxcontent AS oxcontent "
                . "FROM $oxvContents c "
                . "WHERE c.oxactive = 1 "
                    . "AND (c.oxtitle LIKE '%$sSrcVal%' "
                    . "OR c.oxcontent LIKE '%$sSrcVal%' ) "
                . "LIMIT " . $limit;

        return $this->_fetchAllRecords($sSql);
    }
    
    
    /**
     * Fetches all records of the given select statement
     * 
     * @param string $query
     * 
     * @return array
     */
    private function _fetchAllRecords(string $query)
    {
        $oDb = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        
        try {
            $resultSet = $oDb->select( $query );
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }

        return $resultSet->fetchAll();
    }

    
 }
 