msVKMarket.window.CreateGroup = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'msvkmarket-group-window-create';
    }
    Ext.applyIf(config, {
        title: _('msvkmarket_group_create'),
        width: 550,
        autoHeight: true,
        url: msVKMarket.config.connector_url,
        action: 'mgr/group/create',
        fields: getGroupItems(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    msVKMarket.window.CreateGroup.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.window.CreateGroup, MODx.Window);
Ext.reg('msvkmarket-group-window-create', msVKMarket.window.CreateGroup);

msVKMarket.window.UpdateGroup = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'msvkmarket-item-window-update';
    }
    Ext.applyIf(config, {
        title: _('msvkmarket_item_update'),
        width: 550,
        autoHeight: true,
        url: msVKMarket.config.connector_url,
        action: 'mgr/group/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    msVKMarket.window.UpdateGroup.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.window.UpdateGroup, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id'
        }, getGroupItems(config)];
    }

});
Ext.reg('msvkmarket-group-window-update', msVKMarket.window.UpdateGroup);

function getGroupItems (config){

    return [{
        xtype: 'textfield',
        name: 'name',
        fieldLabel: _('msvkmarket_group_name'),
        anchor: '99%'
    },{
        xtype: 'numberfield',
        name: 'group_id',
        fieldLabel: _('msvkmarket_group_id'),
        anchor: '100%'
    },{
        xtype: 'numberfield',
        name: 'app_id',
        fieldLabel: _('msvkmarket_group_app_id'),
        anchor: '100%'
    },{
        xtype: 'textfield',
        name: 'secretkey',
        fieldLabel: _('msvkmarket_group_skey'),
        anchor: '100%'
    },{
        xtype: 'textfield',
        name: 'token',
        fieldLabel: _('msvkmarket_group_token'),
        anchor: '100%'
    },{
        xtype: 'xcheckbox',
        name: 'status',
        boxLabel: _('msvkmarket_manager_on'),
        fieldLabel: _('msvkmarket_status'),
        anchor: '100%'
    }];
}

/*
MODx.msg.status({
    title: _('banonip'),
    message: r.results.message,
    dontHide: true
});
*/