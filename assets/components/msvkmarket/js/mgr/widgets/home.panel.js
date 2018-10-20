msVKMarket.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('msvkmarket') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('msvkmarket_export'),
                layout: 'anchor',
                items: [{
                    html: _('msvkmarket_intro_msg'),
                    cls: 'panel-desc'
                }, {
                    layout: 'column',
                    cls: 'main-wrapper',
                    items: [{
                        xtype: 'msvkmarket-tree-categories',
                        optionGrid: 'msvkmarket-grid-items',
                        columnWidth: .25
                    },{
                        xtype: 'msvkmarket-grid-items'
                        ,columnWidth: .75
                    }]
                }]
            },{
                title: _('msvkmarket_groups'),
                layout: 'anchor',
                items: [{
                    html: _('msvkmarket_group_intro_msg'),
                    cls: 'panel-desc'
                }, {
                    xtype: 'msvkmarket-grid-group',
                    cls: 'main-wrapper'
                }]
            },{
                title: _('msvkmarket_compilation'),
                layout: 'anchor',
                items: [{
                    html: _('msvkmarket_compilation_intro_msg'),
                    cls: 'panel-desc'
                }, {
                    xtype: 'msvkmarket-grid-compilation',
                    cls: 'main-wrapper'
                }]
            }]
        }]
    });
    msVKMarket.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.panel.Home, MODx.Panel);
Ext.reg('msvkmarket-panel-home', msVKMarket.panel.Home);