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
                    //xtype: 'msvkmarket-grid-items',
                    cls: 'main-wrapper'
                }]
            }]
        }]
    });
    msVKMarket.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.panel.Home, MODx.Panel);
Ext.reg('msvkmarket-panel-home', msVKMarket.panel.Home);

function getItems (config){
    return [{
        xtype: 'textfield',
        fieldLabel: _('banonip_item_name'),
        name: 'name',
        id: config.id + '-name',
        anchor: '99%',
        allowBlank: false,
    }, {
        fieldLabel: _('banonip_item_desc_masc'),
        layout: 'column',
        items: [{
            columnWidth: .15,
            xtype: 'xcheckbox',
            boxLabel: _('banonip_item_a'),
            name: 'a',
            id: config.id + '-a',
            checked: false,
            disabled: true
        }, {
            columnWidth: .15,
            xtype: 'xcheckbox',
            boxLabel: _('banonip_item_b'),
            name: 'b',
            id: config.id + '-b',
            handler: function(field, value){
                if (value == true) {
                    Ext.getCmp(config.id + '-c').setValue(true);
                    Ext.getCmp(config.id + '-d').setValue(true);
                }
            }
        }, {
            columnWidth: .15,
            xtype: 'xcheckbox',
            boxLabel: _('banonip_item_c'),
            name: 'c',
            id: config.id + '-c',
            handler: function(field, value){
                if (value == true) {
                    Ext.getCmp(config.id + '-d').setValue(true);
                }else{
                    Ext.getCmp(config.id + '-b').setValue(false);
                }
            }
        }, {
            columnWidth: .3,
            xtype: 'xcheckbox',
            boxLabel: _('banonip_item_d'),
            name: 'd',
            id: config.id + '-d',
            handler: function(field, value){
                if (value == false) {
                    Ext.getCmp(config.id + '-b').setValue(false);
                    Ext.getCmp(config.id + '-c').setValue(false);
                }
            }
        },{
            columnWidth: .15,
            style: 'text-align: right',
            xtype: 'xcheckbox',
            boxLabel: _('banonip_item_active'),
            name: 'active',
            id: config.id + '-active',
        }]
    }, {
        xtype: 'textarea',
        fieldLabel: _('banonip_item_description'),
        name: 'description',
        id: config.id + '-description',
        height: 80,
        anchor: '99%'
    }];
};