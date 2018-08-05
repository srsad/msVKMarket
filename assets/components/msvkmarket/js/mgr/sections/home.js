console.log('connnect');
msVKMarket.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'msvkmarket-panel-home',
            renderTo: 'msvkmarket-panel-home-div'
        }]
    });
    msVKMarket.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.page.Home, MODx.Component);
Ext.reg('msvkmarket-page-home', msVKMarket.page.Home);