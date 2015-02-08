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
 * @copyright (C) Joachim Barthel 2014-2015 
 *
 */
 
class jxfind extends oxAdminView
{
    protected $_sThisTemplate = "jxfind.tpl";

    public function render()
    {
        parent::render();

        $sSrcVal = $this->getConfig()->getRequestParameter( 'jxfind_srcval' );
        if (empty($sSrcVal))
            $sSrcVal = "";
        else
            $sSrcVal = strtoupper($sSrcVal);
        $this->_aViewData["jxfind_srcval"] = $sSrcVal;

        $this->_aViewData["aProdResults"] = $this->_retrieveProdData($sSrcVal);
        $this->_aViewData["aCatResults"] = $this->_retrieveCatData($sSrcVal);
        $this->_aViewData["aCmsResults"] = $this->_retrieveCmsData($sSrcVal);

        $oModule = oxNew('oxModule');
        $oModule->load('jxfind');
        $this->_aViewData["sModuleId"] = $oModule->getId();
        $this->_aViewData["sModuleVersion"] = $oModule->getInfo('version');
        
        return $this->_sThisTemplate;
    }
    
    
    public function downloadResult()
    {
        $myConfig = oxRegistry::get("oxConfig");
        switch ( $myConfig->getConfigParam("sJxSalesSeparator") ) {
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
        if ( $myConfig->getConfigParam("bJxSalesQuote") ) {
            $sBegin = '"';
            $sSep   = '"' . $sSep . '"';
            $sEnd   = '"';
        }
        
        $sSrcVal = oxConfig::getParameter( "jxsales_srcval" ); 
        if (empty($sSrcVal))
            $sSrcVal = "";
        else
            $sSrcVal = strtoupper($sSrcVal);
        
        $aOrders = array();
        $aOrders = $this->_retrieveData($sSrcVal);

        $aOxid = oxConfig::getParameter( "jxsales_oxid" ); 
        
        $sContent = '';
        if ( $myConfig->getConfigParam("bJxSalesHeader") ) {
            $aHeader = array_keys($aOrders[0]);
            $sContent .= $sBegin . implode($sSep, $aHeader) . $sEnd . chr(13);
        }
        foreach ($aOrders as $aOrder) {
            if ( in_array($aOrder['orderartid'], $aOxid) ) {
                $sContent .= $sBegin . implode($sSep, $aOrder) . $sEnd . chr(13);
            }
        }

        header("Content-Type: text/plain");
        header("content-length: ".strlen($sContent));
        header("Content-Disposition: attachment; filename=\"sales-report.csv\"");
        echo $sContent;
        
        exit();

        return;
    }

    
    private function _retrieveProdData($sSrcVal)
    {
        $sOxvArticles = getViewName( 'oxarticles', $this->_iEditLang, $sShopID );
        $sOxvArtextends = getViewName( 'oxartextends', $this->_iEditLang, $sShopID );
        
        $sSql = "SELECT a.oxid AS oxid, a.oxactive As oxactive, a.oxartnum AS oxartnum, a.oxtitle AS oxtitle, a.oxvarselect AS oxvarselect, "
                . "a.oxshortdesc AS oxshortdesc, a.oxsearchkeys AS oxsearchkeys, e.oxtags AS oxtags, e.oxlongdesc AS oxlongdesc "
                . "FROM $sOxvArticles a, $sOxvArtextends e "
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

        $aResults = array();

        if ($sSrcVal != "") {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql);
            while (!$rs->EOF) {
                array_push($aResults, $rs->fields);
                $rs->MoveNext();
            }
        }
        
        return $aResults;
    }

    
    private function _retrieveCatData($sSrcVal)
    {
        $sOxvCategories = getViewName( 'oxcategories', $this->_iEditLang, $sShopID );
        
        $sSql = "SELECT c.oxid AS oxid, c.oxactive AS oxactive, c.oxtitle AS oxtitle, c.oxdesc AS oxdesc, c.oxlongdesc AS oxlongdesc "
                . "FROM $sOxvCategories c "
                . "WHERE c.oxactive = 1 "
                    . "AND (c.oxtitle LIKE '%$sSrcVal%' "
                    . "OR c.oxdesc LIKE '%$sSrcVal%' "
                    . "OR c.oxlongdesc LIKE '%$sSrcVal%' )";

        $aResults = array();

        if ($sSrcVal != "") {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql);
            while (!$rs->EOF) {
                array_push($aResults, $rs->fields);
                $rs->MoveNext();
            }
        }
        
        return $aResults;
    }

    
    private function _retrieveCmsData($sSrcVal)
    {
        /*$myConfig = oxRegistry::get("oxConfig");
        $replaceMRS = $myConfig->getConfigParam("bJxSalesReplaceMRS");
        $replaceMR = $myConfig->getConfigParam("bJxSalesReplaceMR");*/
        
        $sOxvContents = getViewName( 'oxcontents', $this->_iEditLang, $sShopID );
        
        $sSql = "SELECT c.oxid AS oxid, c.oxactive AS oxactive, c.oxloadid AS oxloadid, c.oxtitle AS oxtitle, c.oxcontent AS oxcontent "
                . "FROM $sOxvContents c "
                . "WHERE c.oxactive = 1 "
                    . "AND (c.oxtitle LIKE '%$sSrcVal%' "
                    . "OR c.oxcontent LIKE '%$sSrcVal%' )";

        $aResults = array();

        if ($sSrcVal != "") {
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs = $oDb->Execute($sSql);
            while (!$rs->EOF) {
                array_push($aResults, $rs->fields);
                $rs->MoveNext();
            }
        }
        
        return $aResults;
    }
    
 }
?>