msVKMarket.window.CreateCompilation = function (config) {
    config = config || {};
    if(!config.id) {
        config.id = 'msvkmarket-compilation-window-create';
    }

    Ext.applyIf(config, {
        title: _('msvkmarket_compilation_create'),
        width: 300,
        autoHeight: true,
        url: msVKMarket.config.connector_url,
        action: 'mgr/compilation/create',
        fields: getCompilationItems(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    msVKMarket.window.CreateCompilation.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.window.CreateCompilation, MODx.Window);
Ext.reg('msvkmarket-compilation-window-create', msVKMarket.window.CreateCompilation);

msVKMarket.window.UpdateCompilation = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'msvkmarket-compilation-window-update';
    }
    Ext.applyIf(config, {
        title: _('msvkmarket_item_update'),
        width: 300,
        autoHeight: true,
        url: msVKMarket.config.connector_url,
        action: 'mgr/compilation/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    msVKMarket.window.UpdateCompilation.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.window.UpdateCompilation, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id'
        }, getCompilationItems(config)];
    }

});
Ext.reg('msvkmarket-compilation-window-update', msVKMarket.window.UpdateCompilation);

function getCompilationItems (config) {
    return [{
        xtype: 'textfield',
        name: 'name',
        fieldLabel: _('msvkmarket_item_name'),
        anchor: '100%'
    },{
        xtype: 'modx-combo',
        name: 'group_id',
        displayField: 'name',
        valueField: 'id',
        fieldLabel: _('msvkmarket_group_select'),
        emptyText: _('msvkmarket_group_select'),
        editable: false,
        fields: ['id', 'name'],
        anchor: '99%',
        hideMode: 'offsets',
        url: msVKMarket.config.connector_url,
        baseParams: {
            action: 'mgr/group/getlist',
            where: '{"status": "1"}',
            combo: true
        }
    }];
}