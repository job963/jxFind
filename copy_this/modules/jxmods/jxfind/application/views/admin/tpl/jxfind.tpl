[{*debug*}]
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

[{*<div class="center">*}]
    <h1>[{ oxmultilang ident="JXFIND_TITLE" }]</h1>
    <div style="position:absolute;top:4px;right:8px;color:gray;font-size:0.9em;border:1px solid gray;border-radius:3px;">&nbsp;[{$sModuleId}]&nbsp;[{$sModuleVersion}]&nbsp;</div>

    <form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
        [{ $shop->hiddensid }]
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="cl" value="article" size="40">
        <input type="hidden" name="updatelist" value="1">
    </form>
        
<form name="jxfind" id="jxfind" action="[{ $oViewConf->selflink }]" method="post">
    <p>
        [{ $oViewConf->hiddensid }]
        <input type="hidden" name="cl" value="jxfind">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <table width="80%"><tr>
        <td align="left">
            <label>Suchbegriff:</label> <input type="text" name="jxfind_srcval" value="[{ $jxfind_srcval }]">
            <input type="submit" 
                onClick="document.forms['jxfind'].elements['fnc'].value = '';" 
                value=" [{ oxmultilang ident="ORDER_MAIN_UPDATE_DELPAY" }] " />
        </td>
        <td align="right">
            [{*<input type="submit" value=" [{ oxmultilang ident="ORDER_MAIN_UPDATE_DELPAY" }] " />*}]
        </td>
        <td>
            [{*<input class="edittext" type="submit" 
                onClick="document.forms['jxfind'].elements['fnc'].value = 'downloadResult';" 
                value=" [{ oxmultilang ident="JXFIND_DOWNLOAD" }] " [{ $readonly }]>*}]
        </td>
        </tr></table>
        [{*</form>*}]
    </p>

    [{assign var="oConfig" value=$oViewConf->getConfig()}]
    
    <div id="liste">
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
            <colgroup>
            <col width="3%" />
            <col width="10%" />
            <col width="10%" />
            <col width="15%" />
            <col width="15%" />
            <col width="15%" />
            <col width="15%" />
            </colgroup>
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
            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="ARTICLE_MAIN_TAGS" }]</div></div></td>
            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_DESCRIPTION" }]</div></div></td>
            [{*<td class="listfilter" style="[{$headStyle}]" align="center"><div class="r1"><div class="b1"><input type="checkbox" onclick="change_all('jxfind_oxid[]', this)"></div></div></td>*}]
        </tr>

        [{*<tbody>*}]
        [{ assign var="actArtTitle" value="..." }]
        [{ assign var="actArtVar" value="..." }]
        [{ assign var="styleLargeCols" value="max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" }]
        [{foreach name=outer item=Result from=$aResults}]
            <tr>
                [{ cycle values="listitem,listitem2" assign="listclass" }]
                <td valign="top" class="[{$listclass}][{ if $Result.oxactive == 1}] active[{/if}]" height="15">
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
                       <div style="[{$styleLargeCols}][{if $Result.oxtags|upper|strstr:$jxfind_srcval}]color:blue;[{/if}]">[{$Result.oxtags}]</div>
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
        [{*</tbody>*}]

        </table>
        <p>
        &nbsp;[{$aResults|@count}] [{ oxmultilang ident="JXFIND_NUMOF_ENTRIES" }]
        </p>
    </div>
</form>
[{*</div>*}]
