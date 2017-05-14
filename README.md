# jxFind

**Module for Finding Texts in Products, Categories and Content Pages**

This module shows all products, categories and CMS pages which are containing the search string in the _title_, _description_, _long description_ or similar fields.

![show products and customers](/docs/img/jxfind-results-de-r75.png)


## Setup ##

1. Unzip the complete file with all the folder structures and upload the content of the folder _copy_this_ to the root folder of your shop.  
OR  
Install the [ioly OXID-Connector](https://github.com/ioly/ioly/tree/connector-oxid) (if you haven't done that already), type _jxfind_ in searchbox and click on ```Install```.  

2. After this navigate in the admin backend of the shop to _Extensions_ - _Modules_. Select the module _jxFind_ and click on `Activate`.

If you open the menu _Products_, you will see the the new menu item _Text Finder_.

### Optional Steps ###
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

- **0.1 - Initial release**
	- Search for texts in articles, categories and CMS pages 
	- Support of OXID versions 4.7, 4.8 and 4.9  

- **0.1.5 - Searchbox in headbar**
	- Old code removed
	- New block definition to header.tpl added
	- Searchbox as block in headbar

- **0.2.0 - Language support**
	- All languages are searchable now (only first language before)

- **0.2.1 - Bug fixing**
	- Small translation error fixed
	- Modified origin template header.tpl moved to folder changed_full