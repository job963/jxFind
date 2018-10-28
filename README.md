# jxFind

**Module for Finding Texts in Products, Categories and Content Pages**

This module shows all products, categories and CMS pages which are containing the search string in the 
_title_, _description_, _long description_ or similar fields.

![show products and customers](/docs/img/jxfind-results-de.png)


## OXID eShop Versions

The module is available for the following versions
  * **OXID eShop 6** (actual module version)
  * OXID eShop 4.x / 5.x ([download here](https://github.com/job963/jxFind/tree/oxid-4x))

## Setup

### OXID eShop 6

1. Install the module  
    ```composer config repo.JxMods/JxFind git https://github.com/job953/jxfind.git```

    ```composer require jxmods/jxfind```

2. Activate the module  
Navigate in the admin backend of the shop to _Extensions_ - _Modules_.  
Select the module _jxFind_ and click on `Activate`.

If you open the menu _Products_, you will see the the new menu item _Text Finder_.

### OXID eShop 4/5

Goto branch [oxid-4x](https://github.com/job963/jxFind/tree/oxid-4x) and follow the instructions there.


### Optional Steps
If you want to use the searchbox in the headbar, you have to modify the file header.tpl in the folder /application/views/admin/tpl. If you use the original admin theme, you can use the changed file from the folder changed_full. Otherwise insert at the end of the ```<ul>...</ul>``` block before the ```</ul>``` line the following code
```
[{block name="admin_header_inclinks"}]
  [{* -- placeholder for custom links -- *}]
[{/block}]
```
If you use the module *ocb_cleartmp*, do the following steps.
- Search for the end of the ```<ul>..</ul>``` block.
- Insert before the line ```</ul>``` the code
```
      [{* **** EXTENSION  FOR JXFIND **** *}]
	  <li class="sep">
          <form method="post" action="[{$oViewConf->getSelfLink()}]" id="jxfind" target="basefrm">
			  <input type="hidden" name="cl" value="jxfind" />
			  <input type="hidden" name="fnc" value="" />
			  <input type="hidden" name="editlanguage" value="[{ $editlanguage }]" />
			  [{$oViewConf->getHiddenSid()}]
			  <span class="rc"></span>
			  <input type="text" name="jxfind_srcval" placeholder="[{ oxmultilang ident="ORDER_ARTICLE_SEARCH" }]..." required size="15" style="padding-left:0px;font-size:12px;">
			  <input type="submit" value="[{ oxmultilang ident="ORDER_ARTICLE_SEARCH" }]" style="font-size:12px;font-weight:bold;color:#535353;">
          </form>
	  </li>
	  [{* **** END **** *}]

```

## Release history ##

See [Changelog](CHANGELOG.md)