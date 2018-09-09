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


// окошко индивидуальной синхронизации
msVKMarket.window.MoreInfo = function(config){
    config = config || {};
    Ext.applyIf(config, {
        title: 'msvkm_manager_more'
        ,width: 400
        ,autoHeight: true
        ,modal: true
        ,fields: [
            {xtype: 'hidden', name: 'id'}
            ,{
                xtype: 'combo-superselect-groups',
                fieldLabel: _('msvkmarket_group_select'),
                name: 'groups_id',
                id: Ext.id() + 'groups_id',
                listeners: {
                    additem: function(){
                        this.updateStore('additem');
                    }
                    ,removeitem: function(){
                        this.updateStore('removeitem');
                    }
                    ,autoSize: function(){
                        this.updateStore();
                    }, scope: this
                }
            },{
                xtype: 'combo-superselect-compilation',
                name: 'compilation_id',
                id: Ext.id() + 'groups_id',
                fieldLabel: _('msvkm_option_albums')
            },{
                xtype: 'combo'
                ,name: 'category_id'
                ,fieldLabel: _('msvkm_category')
                ,typeAhead: true
                ,lazyRender:true
                ,mode: 'local'
                ,store: new Ext.data.ArrayStore({
                    id: 0
                    ,fields: ['id', 'name' ]
                    ,data: categories_id
                }),
                valueField: 'id'
                ,displayField: 'name'
                ,anchor: '100%'
            },{
                xtype: 'xcheckbox'
                ,name: 'product_status'
                ,boxLabel: _('msvkm_manager_on')
                ,fieldLabel: _('msvkm_manager_vk_status')
                ,anchor: '100%'
            }]

        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { config.closeAction !== 'close' ? this.hide() : this.close(); }
        },{
            text: '<i class="icon icon-refresh"></i> &nbsp; ' + _('msvkm_update')
            ,cls: 'primary-button'
            ,anchor: '100%'
            ,handler: function(){
                this.updateItem();
            }, scope: this
        }]
    });
    msVKMarket.window.MoreInfo.superclass.constructor.call(this, config);
};

Ext.extend(msVKMarket.window.MoreInfo, MODx.Window,{
    updateStore : function(e){
        var groupId			= this.fp.getForm().findField('groups_id');
        var compilationId	= this.fp.getForm().findField('compilation_id');
        var ids 			= groupId.getValue();

        if(e == 'removeitem'){
            compilationId.clearValue();
        }
        if (groupId.getValue() != '') {
            compilationId.setDisabled(false);
        }else{
            compilationId.setDisabled(true);
        }
        compilationId.store.baseParams.ids = ids;
        compilationId.updateStore;
    },
    updateItem : function(){
        var form 		= this;
        var id 			= form.fp.getForm().findField('id').getValue();
        var groups 		= form.fp.getForm().findField('groups_id').getValue();
        var compilation = form.fp.getForm().findField('compilation_id').getValue();
        var category_id = form.fp.getForm().findField('category_id').getValue();
        var status		= form.fp.getForm().findField('product_status').getValue();
        var grid 		= Ext.getCmp('vkmarket-grid-goods');

        if (groups === '') { return false; }
        // блокируем форму
        form.setDisabled(true);

        if (groups !== '') {
            MODx.Ajax.request({
                url: msVKMarket.config.connector_url
                ,params: {
                    action: 'mgr/manager/update'
                    ,id: id
                    ,groups: groups
                    ,compilation: compilation
                    ,category_id: category_id
                    ,status: status
                }
                ,listeners: {
                    success: {fn:function(r) {
                            form.close();
                            grid.refresh();
                        }
                    }
                }
            });
        }

    }
});
Ext.reg('msvkmarket-item-windows-more-info', msVKMarket.window.MoreInfo);