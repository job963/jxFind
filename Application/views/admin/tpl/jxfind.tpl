[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]

<script type="text/javascript">
  if(top)
  {
    top.sMenuItem    = "[{ oxmultilang ident="mxmanageprod" }]";
    top.sMenuSubItem = "[{ oxmultilang ident="jxfind_menu" }]";
    top.sWorkArea    = "[{$_act}]";
    top.setTitle();
  }

function editThis( sID, sClass )
{
    [{assign var="shMen" value=1}]

    [{foreach from=$menustructure item=menuholder }]
      [{if $shMen && $menuholder->nodeType == XML_ELEMENT_NODE && $menuholder->childNodes->length }]

        [{assign var="shMen" value=0}]
        [{assign var="mn" value=1}]

        [{foreach from=$menuholder->childNodes item=menuitem }]
          [{if $menuitem->nodeType == XML_ELEMENT_NODE && $menuitem->childNodes->length }]
            [{ if $menuitem->getAttribute('id') == 'mxorders' }]

              [{foreach from=$menuitem->childNodes item=submenuitem }]
                [{if $submenuitem->nodeType == XML_ELEMENT_NODE && $submenuitem->getAttribute('cl') == 'admin_order' }]

                    if ( top && top.navigation && top.navigation.adminnav ) {
                        var _sbli = top.navigation.adminnav.document.getElementById( 'nav-1-[{$mn}]-1' );
                        var _sba = _sbli.getElementsByTagName( 'a' );
                        top.navigation.adminnav._navAct( _sba[0] );
                    }

                [{/if}]
              [{/foreach}]

            [{ /if }]
            [{assign var="mn" value=$mn+1}]

          [{/if}]
        [{/foreach}]
      [{/if}]
    [{/foreach}]

    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value=sClass; /*'article';*/
    oTransfer.submit();
}

function change_all( name, elem )
{
    if(!elem || !elem.form) 
        return alert("Check Parameters");

    var chkbox = elem.form.elements[name];
    if (!chkbox) 
        return alert(name + " doesn't exist!");

    if (!chkbox.length) 
        chkbox.checked = elem.checked; 
    else 
        for(var i = 0; i < chkbox.length; i++)
            chkbox[i].checked = elem.checked;
}

</script>

<h1>[{ oxmultilang ident="JXFIND_TITLE" }]</h1>
<div style="position:absolute;top:4px;right:8px;color:gray;font-size:0.9em;border:1px solid gray;border-radius:3px;">&nbsp;[{$sModuleId}]&nbsp;[{$sModuleVersion}]&nbsp;</div>

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="article" size="40">
    <input type="hidden" name="updatelist" value="1">
</form>
        
<form name="jxfind" id="jxfind" action="[{ $oViewConf->selflink }]" method="post">
    <p><div style="background-color:#f0f0f0;border-radius:4px;padding:8px;width:98%;">
        [{ $oViewConf->hiddensid }]
        <input type="hidden" name="cl" value="jxfind">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <table [{*width="60%"*}]>
            <tr>
                <td align="left">
                    <label style="font-weight:bold;">[{ oxmultilang ident="JXFIND_SEARCHTEXT" }]:</label> <input type="text" name="jxfind_srcval" value="[{ $jxfind_srcval }]">
                    <input type="submit" 
                        onClick="document.forms['jxfind'].elements['fnc'].value = '';" 
                        value=" [{ oxmultilang ident="ORDER_ARTICLE_SEARCH" }] "
                        style="font-weight:bold;" />
                </td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    <label style="font-weight:bold;">[{ oxmultilang ident="JXFIND_LANGUAGE" }]: 
                        <select name="jxfind_lang" size="1">
                            [{foreach name=lang item=Lang from=$aLangs}]
                                <option value="[{$Lang.id}]" [{if $Lang.id == $jxfind_lang}]selected="selected"[{/if}]>[{$Lang.title}]</option>
                            [{/foreach}]
                        </select>
                    </label>
                </td>
                <td align="right">
                    [{*<input type="submit" value=" [{ oxmultilang ident="ORDER_MAIN_UPDATE_DELPAY" }] " />*}]
                </td>
                <td>
                    [{*<input class="edittext" type="submit" 
                        onClick="document.forms['jxfind'].elements['fnc'].value = 'downloadResult';" 
                        value=" [{ oxmultilang ident="JXFIND_DOWNLOAD" }] " [{ $readonly }]>*}]
                </td>
            </tr>
        </table>
    </div></p>

    [{assign var="oConfig" value=$oViewConf->getConfig()}]
    
    <div id="liste">
    [{if $aProdResults|@count > 0 }]
        <h2>&nbsp;[{ oxmultilang ident="GENERAL_ITEM" }]</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
            <colgroup>
                <col width="3%" />
                <col width="10%" />
                <col width="10%" />
                <col width="15%" />
                <col width="15%" />
                <col width="15%" />
            </colgroup>
            <tbody>
                <tr>
                    [{ assign var="headStyle" value="border-bottom:1px solid #C8C8C8; font-weight:bold;" }]
                    <td class="listfilter first" style="[{$headStyle}]" height="15" width="30" align="center">
                        <div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_ACTIVTITLE" }]</div></div>
                    </td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="ARTICLE_MAIN_ARTNUM" }]</div></div></td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="ARTICLE_MAIN_TITLE" }]</div></div></td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="JXFIND_VARIANT" }]</div></div></td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="ARTICLE_MAIN_SHORTDESC" }]</div></div></td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="ARTICLE_MAIN_SEARCHKEYS" }]</div></div></td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_DESCRIPTION" }]</div></div></td>
                    [{*<td class="listfilter" style="[{$headStyle}]" align="center"><div class="r1"><div class="b1"><input type="checkbox" onclick="change_all('jxfind_oxid[]', this)"></div></div></td>*}]
                </tr>

                [{ assign var="actArtTitle" value="..." }]
                [{ assign var="actArtVar" value="..." }]
                [{ assign var="styleLargeCols" value="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" }]
                [{foreach name=outer item=Result from=$aProdResults}]
                    <tr>
                        [{ cycle values="listitem,listitem2" assign="listclass" }]
                        <td valign="top" class="[{$listclass}][{if $Result.oxactive == 1}] active[{/if}]" height="15">
                            <div class="listitemfloating">&nbsp</a></div>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','article');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               [{$Result.oxartnum}]
                            </a>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','article');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]" >
                               <div style="[{$styleLargeCols}][{if $Result.oxtitle|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxtitle}]</div>
                            </a>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','article');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               <div style="[{$styleLargeCols}][{if $Result.oxvarselect|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxvarselect}]</div>
                            </a>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','article');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               <div style="[{$styleLargeCols}][{if $Result.oxshortdesc|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxshortdesc}]</div>
                            </a>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','article');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               <div style="[{$styleLargeCols}][{if $Result.oxsearchkeys|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxsearchkeys}]</div>
                            </a>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','article');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               <div style="[{$styleLargeCols}][{if $Result.oxlongdesc|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxlongdesc|strip_tags}]</div>
                            </a>
                        </td>

                        [{*<td class="[{$listclass}]" align="center"><input type="checkbox" name="jxfind_oxid[]" value="[{$Result.oxid}]"></td>*}]
                    </tr>
                [{/foreach}]
            </tbody>
        </table>
            
        <p>
        &nbsp;[{$aProdResults|@count}] [{ oxmultilang ident="JXFIND_NUMOF_ENTRIES" }]
        </p>
    [{/if}]

    [{if $aCatResults|@count > 0 }]
        <hr>
        <h2>&nbsp;[{ oxmultilang ident="CATEGORY_LIST_MENUSUBITEM" }]</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
            <colgroup>
            <col width="3%" />
                <col width="10%" />
                <col width="10%" />
                <col width="35%" />
            </colgroup>
            <tbody>
                <tr>
                    [{ assign var="headStyle" value="border-bottom:1px solid #C8C8C8; font-weight:bold;" }]
                    <td class="listfilter first" style="[{$headStyle}]" height="15" width="30" align="center">
                        <div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_ACTIVTITLE" }]</div></div>
                    </td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="ARTICLE_MAIN_TITLE" }]</div></div></td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="ARTICLE_MAIN_SHORTDESC" }]</div></div></td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_DESCRIPTION" }]</div></div></td>
                    [{*<td class="listfilter" style="[{$headStyle}]" align="center"><div class="r1"><div class="b1"><input type="checkbox" onclick="change_all('jxfind_oxid[]', this)"></div></div></td>*}]
                </tr>
                [{ assign var="styleLargeCols" value="max-width:500px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" }]
                [{foreach name=outer item=Result from=$aCatResults}]
                    <tr>
                        [{ cycle values="listitem,listitem2" assign="listclass" }]
                        <td valign="top" class="[{$listclass}][{if $Result.oxactive == 1}] active[{/if}]" height="15">
                            <div class="listitemfloating">&nbsp</a></div>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','category');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               <div style="[{$styleLargeCols}][{if $Result.oxtitle|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxtitle}]</div>
                            </a>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','category');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               <div style="[{$styleLargeCols}][{if $Result.oxdesc|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxdesc}]</div>
                            </a>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','category');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               <div style="[{$styleLargeCols}][{if $Result.oxlongdesc|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxlongdesc|strip_tags}]</div>
                            </a>
                        </td>

                        [{*<td class="[{$listclass}]" align="center"><input type="checkbox" name="jxfind_oxid[]" value="[{$Result.oxid}]"></td>*}]
                    </tr>
                [{/foreach}]
            </tbody>
        </table>
            
        <p>
        &nbsp;[{$aCatResults|@count}] [{ oxmultilang ident="JXFIND_NUMOF_ENTRIES" }]
        </p>
    [{/if}]

    [{if $aCmsResults|@count > 0 }]
        <hr>
        <h2>&nbsp;[{ oxmultilang ident="CONTENT_LIST_MENUSUBITEM" }]</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
            <colgroup>
                <col width="3%" />
                <col width="10%" />
                <col width="10%" />
                <col width="35%" />
            </colgroup>
            <tbody>
                <tr>
                    [{ assign var="headStyle" value="border-bottom:1px solid #C8C8C8; font-weight:bold;" }]
                    <td class="listfilter first" style="[{$headStyle}]" height="15" width="30" align="center">
                        <div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_ACTIVTITLE" }]</div></div>
                    </td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_IDENT" }]</div></div></td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="ARTICLE_MAIN_TITLE" }]</div></div></td>
                    <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_DESCRIPTION" }]</div></div></td>
                    [{*<td class="listfilter" style="[{$headStyle}]" align="center"><div class="r1"><div class="b1"><input type="checkbox" onclick="change_all('jxfind_oxid[]', this)"></div></div></td>*}]
                </tr>
                [{ assign var="styleLargeCols" value="max-width:500px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" }]
                [{foreach name=outer item=Result from=$aCmsResults}]
                    <tr>
                        [{ cycle values="listitem,listitem2" assign="listclass" }]
                        <td valign="top" class="[{$listclass}][{if $Result.oxactive == 1}] active[{/if}]" height="15">
                            <div class="listitemfloating">&nbsp</a></div>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','admin_content');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               <div style="[{$styleLargeCols}][{if $Result.oxloadid|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxloadid}]</div>
                            </a>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','admin_content');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               <div style="[{$styleLargeCols}][{if $Result.oxtitle|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxtitle}]</div>
                            </a>
                        </td>
                        <td class="[{$listclass}]">
                            <a href="Javascript:editThis('[{$Result.oxid}]','admin_content');" title="[{ oxmultilang ident="JXFIND_GOTOPRODUCT" }]">
                               <div style="[{$styleLargeCols}][{if $Result.oxcontent|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxcontent|strip_tags}]</div>
                            </a>
                        </td>

                        [{*<td class="[{$listclass}]" align="center"><input type="checkbox" name="jxfind_oxid[]" value="[{$Result.oxid}]"></td>*}]
                    </tr>
                [{/foreach}]
            </tbody>
        </table>
            
        <p>
        &nbsp;[{$aCmsResults|@count}] [{ oxmultilang ident="JXFIND_NUMOF_ENTRIES" }]
        </p>
    [{/if}]
        
    </div>

</form>
