[{$smarty.block.parent}]
    <li class="sep">
        <form method="post" action="[{$oViewConf->getSelfLink()}]" id="jxfind" target="basefrm">
            <input type="hidden" name="cl" value="jxfind" />
            <input type="hidden" name="fnc" value="" />
            <input type="hidden" name="editlanguage" value="[{ $editlanguage }]" />
            [{$oViewConf->getHiddenSid()}]
            <span class="rc">&nbsp;</span> 
            <input type="text" name="jxfind_srcval" placeholder="[{ oxmultilang ident="ORDER_ARTICLE_SEARCH" }]..." required size="15" style="padding-left:4px;font-size:12px;">
            <input type="submit" value="[{ oxmultilang ident="ORDER_ARTICLE_SEARCH" }]" style="font-size:12px;font-weight:bold;color:#535353;">
        </form>
    </li>
