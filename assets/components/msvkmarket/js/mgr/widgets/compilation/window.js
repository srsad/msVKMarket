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
        },{
            xtype: 'hidden',
            name: 'album_id',
            id: config.id + '-album_id'
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
        hiddenName: 'group_id',
        displayField: 'name',
        valueField: 'id',
        fieldLabel: _('msvkmarket_group_select'),
        fields: ['id', 'name'],
        anchor: '99%',
        emptyText: _('msvkmarket_group_select'),
        hideMode: 'offsets',
        url: msVKMarket.config.connector_url,
        baseParams: {
            action: 'mgr/group/getlist',
            where: '{"status":"1"}'
        },
        tpl:  '<tpl for="."><div class="x-combo-list-item"><span>({id})</span> - {name}</div></tpl>'
    }];
}

msVKMarket.window.ExportCompilation = function(config) {
    config = config || {};
    if (!config.id) {
        config.id = 'msvkmarket-compilation-window-export';
    }
    Ext.applyIf(config, {
        title: _('msvkmarket_item_export'),
        width: 300,
        url: msVKMarket.config.connector_url,
        action: 'mgr/compilation/export',
        autoHeight: true,
        fields: {
            xtype: 'modx-combo',
            name: 'id',
            hiddenName: 'id',
            displayField: 'name',
            valueField: 'id',
            fieldLabel: _('msvkmarket_group_select'),
            fields: ['id', 'name'],
            anchor: '99%',
            emptyText: _('msvkmarket_group_select'),
            hideMode: 'offsets',
            url: msVKMarket.config.connector_url,
            baseParams: {
                action: 'mgr/group/getlist',
                where: '{"status":"1"}'
            },
            tpl:  '<tpl for="."><div class="x-combo-list-item"><span>({id})</span> - {name}</div></tpl>'
        },
        buttons: [{
            text: config.cancelBtnText || _('cancel'),
            handler: function() { config.closeAction !== 'close' ? this.hide() : this.close(); },
            scope: this
        },{
            text: '<i class="icon icon-download"></i> ' + _('msvkmarket_item_export'),
            cls: 'primary-button',
            anchor: '100%',
            handler: this.submit,
            scope: this
        }],
        listeners: {
            success: {
                fn: function(r){
                    console.log(r);
                }, scope: this
            }
        }
    });
    msVKMarket.window.ExportCompilation.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.window.ExportCompilation, MODx.Window, {
    exportCompilation: function (config) {
        console.log('export sadsad');
    }
});
Ext.reg('msvkmarket-compilation-window-export', msVKMarket.window.ExportCompilation);
