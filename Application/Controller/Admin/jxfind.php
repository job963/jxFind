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
    //-protected $_sThisTemplate = "jxfind.tpl";

    /**
     * Displays the entries of database table oxconfig as readable table
     * 
     * @return string Filename of template file
     */
    public function render()
    {
        //-parent::render();

        /**
         * @var Request $request 
         */
        $request = Registry::getRequest();

        //-$sSrcVal = $this->getConfig()->getRequestParameter( 'jxfind_srcval' );
        //-$iSrcLang = $this->getConfig()->getRequestParameter( 'jxfind_lang' );
        $jxSearchValue = $request->getParameter( 'jxfind_srcval' );
        $jxSearchLang = $request->getParameter( 'jxfind_lang' );
        if (empty($jxSearchValue))
            $jxSearchValue = "";
        else
            $jxSearchValue = strtoupper($jxSearchValue);
        
        $aLangs = $this->_getAllLanguages();
        
        $this->_aViewData["jxfind_srcval"] = $jxSearchValue;
        $this->_aViewData["jxfind_lang"] = $jxSearchLang;
        $this->_aViewData["aLangs"] = $aLangs;

        $this->_aViewData["aProdResults"] = $this->_findProductData( $jxSearchValue, $jxSearchLang );
        $this->_aViewData["aCatResults"] = $this->_findCategoryData( $jxSearchValue, $jxSearchLang );
        $this->_aViewData["aCmsResults"] = $this->_findCmsPageData( $jxSearchValue, $jxSearchLang );

        //-$oModule = oxNew('oxModule');
        //-$oModule->load('jxfind');
        //-$this->_aViewData["sModuleId"] = $oModule->getId();
        //-$this->_aViewData["sModuleVersion"] = $oModule->getInfo('version');
        $module->load( 'jxfind' );
        $this->_aViewData['sModuleId'] = $module->getId();
        $this->_aViewData['sModuleVersion'] = $module->getInfo('version');
        
        parent::render();
        
        //-return $this->_sThisTemplate;
        return "jxfind.tpl";
    }
    
    
    
    private function _getAllLanguages()
    {
        //-$oConfig = oxRegistry::get('oxConfig');
        //-$aConfigLanguageParams = $oConfig->getConfigParam('aLanguageParams');
        //-$aConfigLanguages = $oConfig->getConfigParam('aLanguages');
        $aConfigLanguageParams = $this->getConfig()->getConfigParam( 'aLanguageParams' );
        $aConfigLanguages = $this->getConfig()->getConfigParam( 'aLanguages' );
        
        $aLanguages = array();
        foreach ($aConfigLanguageParams as $key => $aConfigLanguageParam) {
            $aLanguages[] = array('id' => $aConfigLanguageParam['baseId'], 'title' => $aConfigLanguages[$key]);
        }
        /*echo '<pre>';
        print_r($aLanguages);
        echo '</pre>';*/
        
        return $aLanguages;
    }
    
    
    // not used at the moment
    public function downloadResult()
    {
        //-$myConfig = oxRegistry::get("oxConfig");
        //-switch ( $myConfig->getConfigParam("sJxSalesSeparator") ) {
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
        //-if ( $myConfig->getConfigParam("bJxSalesQuote") ) {
        if ( $this->getConfig()->getConfigParam( 'bJxSalesQuote' ) ) {
            $sBegin = '"';
            $sSep   = '"' . $sSep . '"';
            $sEnd   = '"';
        }
        
        /*$sSrcVal = oxConfig::getParameter( "jxsales_srcval" ); 
        if (empty($sSrcVal))
            $sSrcVal = "";
        else
            $sSrcVal = strtoupper($sSrcVal);*/
        $jxSearchValue = $request->getParameter( 'jxfind_srcval' );
        if (empty($jxSearchValue))
            $jxSearchValue = "";
        else
            $jxSearchValue = strtoupper($jxSearchValue);
        
        $aOrders = array();
        $aOrders = $this->_retrieveData($sSrcVal);

        //-$aOxid = oxConfig::getParameter( "jxsales_oxid" ); 
        $aOxid = $request->getParameter( 'jxsales_oxid' ); 
        
        $sContent = '';
        //-if ( $myConfig->getConfigParam("bJxSalesHeader") ) {
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
    private function _findProductData($sSrcVal, $iSrcLang)
    {
        //echo $this->_iEditLang;
        //$sOxvArticles = getViewName( 'oxarticles', $this->_iEditLang, $sShopID );
        //$sOxvArtextends = getViewName( 'oxartextends', $this->_iEditLang, $sShopID );
        $oxvArticles = TableViewNameGenerator::getViewName( 'oxarticles', $iSrcLang, $sShopID );
        $oxvArtextends = TableViewNameGenerator::getViewName( 'oxartextends', $iSrcLang, $sShopID );
        
        $sSql = "SELECT a.oxid AS oxid, a.oxactive As oxactive, a.oxartnum AS oxartnum, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, "
                . "a.oxshortdesc AS oxshortdesc, a.oxsearchkeys AS oxsearchkeys, e.oxtags AS oxtags, e.oxlongdesc AS oxlongdesc "
                . "FROM $oxvArticles a, $oxvArtextends e "
                . "WHERE "
                    . "a.oxid = e.oxid "
                    . "AND a.oxactive = 1 "
                    . "AND ("
                        . "a.oxtitle LIKE '%$sSrcVal%' "
                        . "OR a.oxvarselect LIKE '%$sSrcVal%' "
                        . "OR a.oxshortdesc LIKE '%$sSrcVal%' "
                        . "OR a.oxsearchkeys LIKE '%$sSrcVal%' "
                        . "OR e.oxtags LIKE '%$sSrcVal%' "
                        . "OR e.oxlongdesc LIKE '%$sSrcVal%' "
                    . ")";

        /*$aResults = array();

        if ($sSrcVal != "") {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql);
            while (!$rs->EOF) {
                array_push($aResults, $rs->fields);
                $rs->MoveNext();
            }
        }*/
        
        //-return $aResults;
        // return found articles
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
    private function _findCategoryData($sSrcVal, $iSrcLang)
    {
        //$sOxvCategories = getViewName( 'oxcategories', $this->_iEditLang, $sShopID );
        $oxvCategories = TableViewNameGenerator::getViewName( 'oxcategories', $iSrcLang, $sShopID );
        
        $sSql = "SELECT c.oxid AS oxid, c.oxactive AS oxactive, c.oxtitle AS oxtitle, c.oxdesc AS oxdesc, c.oxlongdesc AS oxlongdesc "
                . "FROM $oxvCategories c "
                . "WHERE c.oxactive = 1 "
                    . "AND (c.oxtitle LIKE '%$sSrcVal%' "
                    . "OR c.oxdesc LIKE '%$sSrcVal%' "
                    . "OR c.oxlongdesc LIKE '%$sSrcVal%' )";

        /*$aResults = array();

        if ($sSrcVal != "") {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql);
            while (!$rs->EOF) {
                array_push($aResults, $rs->fields);
                $rs->MoveNext();
            }
        }*/
        
        //-return $aResults;
        // return found categories
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
    private function _findCmsPageData($sSrcVal, $iSrcLang)
    {
        /*$myConfig = oxRegistry::get("oxConfig");
        $replaceMRS = $myConfig->getConfigParam("bJxSalesReplaceMRS");
        $replaceMR = $myConfig->getConfigParam("bJxSalesReplaceMR");*/
        
        //$sOxvContents = getViewName( 'oxcontents', $this->_iEditLang, $sShopID );
        $oxvContents = TableViewNameGenerator::getViewName( 'oxcontents', $iSrcLang, $sShopID );
        
        $sSql = "SELECT c.oxid AS oxid, c.oxactive AS oxactive, c.oxloadid AS oxloadid, c.oxtitle AS oxtitle, c.oxcontent AS oxcontent "
                . "FROM $oxvContents c "
                . "WHERE c.oxactive = 1 "
                    . "AND (c.oxtitle LIKE '%$sSrcVal%' "
                    . "OR c.oxcontent LIKE '%$sSrcVal%' )";

        /*$aResults = array();

        if ($sSrcVal != "") {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql);
            while (!$rs->EOF) {
                array_push($aResults, $rs->fields);
                $rs->MoveNext();
            }
        }*/
        
        //-return $aResults;
        // return found CMS pages
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
 