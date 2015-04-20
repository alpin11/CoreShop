/**
 * CoreShop
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.coreshop.org/license
 *
 * @copyright  Copyright (c) 2015 Dominik Pfaffenbauer (http://dominik.pfaffenbauer.at)
 * @license    http://www.coreshop.org/license     New BSD License
 */

pimcore.registerNS("pimcore.plugin.coreshop");

pimcore.plugin.coreshop = Class.create(pimcore.plugin.admin,{


    getClassName: function (){
        return "pimcore.plugin.coreshop";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
        this.navEl = Ext.get('pimcore_menu_logout').insertSibling('<li id="pimcore_menu_coreshop" class="icon-coreshop-shop">CoreShop</li>');
    },

    uninstall: function(){
        //TODO remove from menu
    },

    pimcoreReady: function (params, broker){
        var menu = new Ext.menu.Menu({
            items: [{
                text: t("coreshop_settings"),
                iconCls: "coreshop_icon_settings",
                handler: this.openSettings
            }],
            cls: "pimcore_navigation_flyout"
        });
        var toolbar = pimcore.globalmanager.get("layout_toolbar");
        this.navEl.on("mousedown", toolbar.showSubMenu.bind(menu));
    },

    openSettings : function()
    {
        try {
            pimcore.globalmanager.get("coreshop_settings").activate();
        }
        catch (e) {
            //console.log(e);
            pimcore.globalmanager.add("coreshop_settings", new pimcore.plugin.coreshop.settings());
        }
    }
});

new pimcore.plugin.coreshop();