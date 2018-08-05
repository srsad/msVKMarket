msVKMarket.window.CreateItem = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'msvkmarket-item-window-create';
    }
    Ext.applyIf(config, {
        title: _('msvkmarket_item_create'),
        width: 550,
        autoHeight: true,
        url: msVKMarket.config.connector_url,
        action: 'mgr/manager/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    msVKMarket.window.CreateItem.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.window.CreateItem, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'textfield',
            fieldLabel: _('msvkmarket_item_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('msvkmarket_item_description'),
            name: 'description',
            id: config.id + '-description',
            height: 150,
            anchor: '99%'
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('msvkmarket_item_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('msvkmarket-item-window-create', msVKMarket.window.CreateItem);


msVKMarket.window.UpdateItem = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'msvkmarket-item-window-update';
    }
    Ext.applyIf(config, {
        title: _('msvkmarket_item_update'),
        width: 550,
        autoHeight: true,
        url: msVKMarket.config.connector_url,
        action: 'mgr/manager/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    msVKMarket.window.UpdateItem.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.window.UpdateItem, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'textfield',
            fieldLabel: _('msvkmarket_item_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('msvkmarket_item_description'),
            name: 'description',
            id: config.id + '-description',
            anchor: '99%',
            height: 150,
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('msvkmarket_item_active'),
            name: 'active',
            id: config.id + '-active',
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('msvkmarket-item-window-update', msVKMarket.window.UpdateItem);