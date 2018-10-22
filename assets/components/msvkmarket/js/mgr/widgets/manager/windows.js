/*
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
*/

/*
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
*/
var categories_id = [ [1, _('msvkm_cat_Women_clothing')], [2, _('msvkm_cat_Men_Clothing')], [3, _('msvkm_cat_Baby_clothes')], [4, _('msvkm_cat_Shoes_and_bags')], [5, _('msvkm_cat_Accessories_decorations')], [100, _('msvkm_cat_Car_Seats')], [101, _('msvkm_cat_Baby_carriages')], [102, _('msvkm_cat_Children_room')], [103, _('msvkm_cat_Toys')], [104, _('msvkm_cat_Babies_parents')], [105, _('msvkm_cat_Learning_creativity')], [106, _('msvkm_cat_To_schoolboys')], [200, _('msvkm_cat_Phones_accessories')], [201, _('msvkm_cat_Photo_video_cameras')], [202, _('msvkm_cat_Audio_video_equipment')], [203, _('msvkm_cat_Portable_Technology')], [204, _('msvkm_cat_consoles_games')], [205, _('msvkm_cat_Engineering_cars')], [206, _('msvkm_cat_Optical_devices')], [207, _('msvkm_cat_Radiotics')], [300, _('msvkm_cat_Computers')], [301, _('msvkm_cat_Laptops_Netbooks')], [302, _('msvkm_cat_Accessories_Accessories')], [303, _('msvkm_cat_Peripherals')], [304, _('msvkm_cat_network_hardware')], [305, _('msvkm_cat_equipment_consumables')], [306, _('msvkm_cat_Movies_music_software')], [400, _('msvkm_cat_Cars')], [401, _('msvkm_cat_Motorcycles_Motorcycles')], [402, _('msvkm_cat_Trucks_special_equipment')], [403, _('msvkm_cat_Water_transport')], [404, _('msvkm_cat_Spare_parts_accessories')], [500, _('msvkm_cat_Apartments')], [501, _('msvkm_cat_Rooms')], [502, _('msvkm_cat_Houses_cottages_cottages')], [503, _('msvkm_cat_Land')], [504, _('msvkm_cat_Garages_parking')], [505, _('msvkm_cat_Commercial_property')], [506, _('msvkm_cat_Property_Abroad')], [600, _('msvkm_cat_Appliances')], [601, _('msvkm_cat_Furniture_interior')], [602, _('msvkm_cat_Kitchen_accessories')], [603, _('msvkm_cat_Textile')], [604, _('msvkm_cat_Household_goods')], [605, _('msvkm_cat_Repair_construction')], [606, _('msvkm_cat_garden_garden')], [700, _('msvkm_cat_Decorative_cosmetics')], [701, _('msvkm_cat_Perfumery')], [702, _('msvkm_cat_Facial_body_care')], [703, _('msvkm_cat_Devices_accessories')], [704, _('msvkm_cat_Optics')], [800, _('msvkm_cat_Leisure')], [801, _('msvkm_cat_Tourism')], [802, _('msvkm_cat_Hunting_fishing')], [803, _('msvkm_cat_equipment_fitness')], [804, _('msvkm_cat_Games')], [900, _('msvkm_cat_Tickets_travel')], [901, _('msvkm_cat_Books_magazines')], [902, _('msvkm_cat_Collecting')], [903, _('msvkm_cat_Musical_instruments')], [904, _('msvkm_cat_Board_games')], [905, _('msvkm_cat_Sets_Certificates')], [906, _('msvkm_cat_Souvenirs_flowers')], [907, _('msvkm_cat_Needlework_creativity')], [1000, _('msvkm_cat_Dogs')], [1001, _('msvkm_cat_Cats')], [1002, _('msvkm_cat_Rodents')], [1003, _('msvkm_cat_Birds')], [1004, _('msvkm_cat_Fish')], [1005, _('msvkm_cat_Other_animals')], [1006, _('msvkm_cat_Feed_accessories')], [1100, _('msvkm_cat_Grocery')], [1101, _('msvkm_cat_Bioproducts')], [1102, _('msvkm_cat_Baby_food')], [1103, _('msvkm_cat_Food_order')], [1104, _('msvkm_cat_Beverages')], [1200, _('msvkm_cat_Photo_video')], [1201, _('msvkm_cat_Remote work')], [1202, _('msvkm_cat_Organization_events')], [1203, _('msvkm_cat_beauty_health')], [1204, _('msvkm_cat_Installation_repair_machinery')], [1205, _('msvkm_cat_housekeeping_services')], [1206, _('msvkm_cat_cargo_transportation')], [1207, _('msvkm_cat_Education_development')], [1208, _('msvkm_cat_Financial_services')], [1209, _('msvkm_cat_Consultations_specialists')]	];

msVKMarket.window.MoreConfigToImport = function(config){
    config = config || {};
    Ext.applyIf(config, {
        title: _('msvkm_manager_window_more_info'),
        autoHeight: true,
        width: 600,
        buttons: [{
            text: config.cancelBtnText || _('cancel'),
            scope: this,
            handler: function() { config.closeAction !== 'close' ? this.hide() : this.close(); }
        },{
            text: '<i class="icon icon-rocket"></i> &nbsp; ' + _('msvkmarket_poehali'),
            cls: 'primary-button',
            anchor: '100%',
            handler: function() {
                var grid 	 = Ext.getCmp('msvkmarket-grid-items');
                var products = grid.getSelectedAsList();

                var groups 		= this.fp.getForm().findField('groups_id').getValue();
                var compilation = this.fp.getForm().findField('compilation_id').getValue();
                var category_id = this.fp.getForm().findField('category_id').getValue();
                var status		= this.fp.getForm().findField('status').getValue();

                if (groups === '') { return false; }

                if (products == false) {
                    var parent = [];
                    var tree = Ext.getCmp('msvkmarket-tree').getChecked();
                    for (var i = 0; i < tree.length; i++) {
                        parent.push(tree[i].attributes.pk);
                    }
                    parent = parent.join(',') === '' ? 0 : parent.join(',');

                    MODx.Ajax.request({
                        url: msVKMarket.config.connector_url,
                        params: {
                            action: 'mgr/manager/getidlist',
                            parents: parent
                        }
                        ,listeners: {
                            success: {
                                fn:function(r) {
                                    products = r.results;
                                }
                            }
                        }
                    });
                }

                Ext.MessageBox.confirm(
                    _('msvkmarket_manager_more_import'),
                    _('msvkmarket_manager_w_ms_desc'),
                    function(config){
                        if(config === 'yes'){
                            var w = new msVKMarket.window.Console({
                                register  : 'mgr',
                                autoScroll : true,
                                baseParams : {
                                    action:		 'mgr/manager/import',
                                    id: 		 products,
                                    groups: 	 groups,
                                    album_id:    compilation,
                                    category_id: category_id,
                                    status:	     status,
                                    step:		 0
                                },
                                updateQuery: function(){
                                    var step  = this.baseParams.step;
                                    var arrId = this.baseParams.id;
                                    if (arrId != null) {
                                        var arrSplit = arrId.split(',');
                                        arrSplit.shift();
                                        this.baseParams.id   = arrSplit.join(',');
                                    }
                                    this.baseParams.step = step+1;
                                }, scope: grid
                            }).show();
                            w.log({message: _('msvkmarket_items_import_start'), level: 3});
                            w.on('complete', function(){
                                window.close();
                                grid.refresh();
                            });
                        }
                    }
                );
            }, scope: this
        }],
        fields: [{
            xtype: 'combo-superselect-groups',
            name: 'groups_id',
            fieldLabel: _('msvkmarket_select_groups'),
            anchor: '100%',
            listeners: {
                additem: function(){
                    this.updateStore('additem');
                },
                removeitem: function(){
                    this.updateStore('removeitem');
                }, scope: this
            }
        },{
            xtype: 'combo-superselect-compilation',
            name: 'compilation_id',
            fieldLabel: _('msvkmarket_select_albums'),
            anchor: '100%'
        },{
            xtype: 'combo',
            name: 'category_id',
            fieldLabel: _('msvkmarket_select_category'),
            typeAhead: true,
            lazyRender:true,
            mode: 'local',
            store: new Ext.data.ArrayStore({
                id: 0,
                fields: ['id', 'name' ],
                data: categories_id
            }),
            valueField: 'id',
            displayField: 'name',
            anchor: '100%'
        },{
            xtype: 'xcheckbox',
            name: 'status',
            fieldLabel: _('msvkmarket_manager_vk_status'),
            boxLabel: _('msvkmarket_manager_on'),
            anchor: '100%'
        }]
    });
    msVKMarket.window.MoreConfigToImport.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.window.MoreConfigToImport, MODx.Window,{
    updateStore : function(e){
        var groups			= this.fp.getForm().findField('groups_id'); // группы
        var compilationId	= this.fp.getForm().findField('compilation_id'); // подборки
        var ids 			= groups.getValue(); // id`шк групп
        if(e == 'removeitem'){
            compilationId.clearValue();
        }
        if (groups.getValue() != '') {
            compilationId.setDisabled(false);
        }else{
            compilationId.setDisabled(true);
        }
        compilationId.store.baseParams.ids = ids;
        compilationId.updateStore;
    }
});
Ext.reg('msvkmarket-item-windows-more-config-to-import', msVKMarket.window.MoreConfigToImport);





// todo потом проверьить где используется
// окошко индивидуальной синхронизации
msVKMarket.window.MoreInfo = function(config){
    config = config || {};
    Ext.applyIf(config, {
        title: 'msvkm_manager_window_more_5_info'
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