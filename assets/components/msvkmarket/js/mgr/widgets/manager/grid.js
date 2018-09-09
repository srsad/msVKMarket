msVKMarket.grid.Items = function (config) {

    config = config || {};
    if (!config.id) {
        config.id = 'msvkmarket-grid-items';
    }
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        url: msVKMarket.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: this.sm,
        baseParams: {
            action: 'mgr/manager/getlist'
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateItem(grid, e, row);
            }
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec) {
                return !rec.data.active
                    ? 'msvkmarket-grid-row-disabled'
                    : '';
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true
    });
    msVKMarket.grid.Items.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(msVKMarket.grid.Items, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = msVKMarket.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },
/*
    createItem: function (btn, e) {
        var w = MODx.load({
            xtype: 'msvkmarket-item-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.setValues({active: true});
        w.show(e.target);
    },
*/
    detailedExport: function () {
        console.log('detailedExport');
    },

    fastExport: function () {

        var cs 			 = this.getSelectedAsList();

        console.log(msVKMarket.config.connector_url);

        if (cs === false) {
            var parent = [];
            var tree = Ext.getCmp('msvkmarket-tree').getChecked();
            for (var i = 0; i < tree.length; i++) { parent.push(tree[i].attributes.pk); }
            parent = parent.join(',') === '' ? 0 : parent.join(',');

            MODx.Ajax.request({
                url: msVKMarket.config.connector_url,
                params: {
                    action: 'mgr/manager/getidlist',
                    parents: parent
                },
                listeners: {
                    success: {
                        fn:function(r) {
                            cs = r.results;
                            console.log(cs);
                        }
                    }
                }
            });
        }

        // todo продолжить отсюда
        Ext.MessageBox.confirm(
            'msvkm_manager_fast_synk'
            ,'msvkm_manager_w_fast_synk_desc'
            ,function(config){
                if(config === 'yes'){
                    var w = new VkMarket.window.Console({
                        'register'  : 'mgr'
                        ,autoScroll	: true
                        ,baseParams	: {
                            action: 'mgr/manager/fastsync'
                            ,id: cs
                            ,step: 0
                        },
                        updatQuery: function(){
                            var step 	 = this.baseParams.step;
                            var arrId  	 = this.baseParams.id;
                            if (arrId != null) {
                                var arrSplit = arrId.split(',');
                                arrSplit.shift();
                                this.baseParams.id 	 = arrSplit.join(',');
                            }
                            this.baseParams.step = step+1;
                        },
                        scope: msVKMarket
                    }).show();
                    w.log({message: 'msvkm_manager_synk_start', level: 3});
                    w.on('complete', function(){
                        this.scope.refresh();
                    });
                }

            }
        );
        return true;
    },

    updateItem: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        } else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        console.log('sadasd');
        console.log(id);

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/manager/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        console.log(r);
                        var w = MODx.load({
                            xtype: 'msvkmarket-item-windows-more-info',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    publishedEnable: function(){
        console.log('publishedEnable');
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/manager/disable',
                ids: Ext.util.JSON.encode(ids)
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },
    publishedDisable: function(){
        console.log('publishedDisable');
    },
    productStatusEnableItem: function(){
        console.log('productStatusEnableItem');
    },
    productStatusDisable: function(){
        console.log('productStatusDisable');
    },

    // todo потом удалить disableItem и enableItem
    disableItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/manager/disable',
                ids: Ext.util.JSON.encode(ids)
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },
    enableItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/manager/enable',
                ids: Ext.util.JSON.encode(ids)
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },

    getFields: function () {
        return ['id','pagetitle', 'thumb', 'category_name', 'parent', 'product_status', 'vkpublished', 'date_sync', 'actions'];
    },

    getColumns: function () {
        return [this.sm,{
            header: _('msvkmarket_item_id'),
            dataIndex: 'id',
            sortable: true,
            width: 70
        },{
            dataIndex: 'pagetitle',
            width: 400,
            sortable: true,
            header: _('msvkmarket_item_grid_name'),
            id: 'product-title',
            renderer: this._renderPagetitle
        }, {
            dataIndex: 'thumb',
            width: 80,
            header: _('msvkmarket_item_grid_img'),
            id: 'image-block',
            renderer: msVKMarket.utils.Image
        }, /*{
            dataIndex: 'product_status',
            width: 100,
            sortable: true,
            header: _('msvkmarket_item_grid_status'),
            id: 'status-block'
            //renderer: msVKMarket.utils.ProductStatus
        }, /*{
            dataIndex: 'vkpublished',
            width: 55,
            sortable: true,
            header: _('msvkmarket_item_grid_public'),
            id: 'published-block',
            //renderer: msVKMarket.utils.VKPublished
        },*/ {
            dataIndex: 'date_sync',
            width: 175,
            sortable: true,
            header: _('msvkmarket_item_grid_date')
        }, {
            header: _('msvkmarket_grid_actions'),
            dataIndex: 'actions',
            renderer: msVKMarket.utils.renderActions,
            sortable: false,
            width: 150,
            id: 'actions'
        }];
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-gear"></i>&nbsp;' + 'msvkmarket_item_detailedExport',
            handler: this.detailedExport,
            scope: this
        },{
            text: '<i class="icon icon-refresh"></i>&nbsp;' + 'msvkmarket_item_fastExport',
            handler: this.fastExport,
            scope: this
        }, '->', {
            xtype: 'msvkmarket-field-search',
            width: 250,
            listeners: {
                search: {
                    fn: function (field) {
                        this._doSearch(field);
                    }, scope: this
                },
                clear: {
                    fn: function (field) {
                        field.setValue('');
                        this._clearSearch();
                    }, scope: this
                }
            }
        }];
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                }
                else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    },

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _clearSearch: function () {
        this.getStore().baseParams.query = '';
        this.getBottomToolbar().changePage(1);
    },

    _renderPagetitle: function (value, cell, row) {
        var link = msVKMarket.utils.productLink(value, row['data']['id']);
        if (!row.data['category_name']) {
            return String.format(
                '<div class="native-product"><span class="id">({0})</span>{1}</div>',
                row['data']['id'],
                link
            );
        }else {
            var category_link = msVKMarket.utils.productLink(row.data['category_name'], row.data['parent']);
            return String.format(
                '<div class="nested-product">\
                    <span class="id" style="display:none;">({0})</span>{1}\
                    <div class="product-category"><small>({2})</small> {3}</div>\
                </div>',
                row['data']['id'],
                link,
                row.data['parent'],
                category_link
            );
        }
    }

});
Ext.reg('msvkmarket-grid-items', msVKMarket.grid.Items);