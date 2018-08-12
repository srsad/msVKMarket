msVKMarket.grid.Compilation = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'msvkmarket-grid-compilation';
    }

    this.sm = new Ext.grid.CheckboxSelectionModel();

    Ext.applyIf(config, {
        url: msVKMarket.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: this.sm,
        baseParams: {
            action: 'mgr/compilation/getlist'
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateCompilation(grid, e, row);
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
    msVKMarket.grid.Compilation.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(msVKMarket.grid.Compilation, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = msVKMarket.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    createCompilation: function (btn, e) {
        var w = MODx.load({
            xtype: 'msvkmarket-compilation-window-create',
            id: Ext.id() + '-window-create',
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

    updateCompilation: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/compilation/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'msvkmarket-compilation-window-update',
                            id: Ext.id() + '-window-update',
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

    removeCompilation: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('msvkmarket_items_remove')
                : _('msvkmarket_item_remove'),
            text: ids.length > 1
                ? _('msvkmarket_items_remove_confirm')
                : _('msvkmarket_item_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/compilation/remove',
                ids: Ext.util.JSON.encode(ids)
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
    },

    getFields: function () {
        return ['id', 'name', 'group_id', 'album_id', 'groupname', 'image', 'actions'];
    },

    getColumns: function () {
        return [
            this.sm, {
            header: _('msvkmarket_item_id'),
            dataIndex: 'id',
            sortable: true,
            width: 75
        }, {
            header: _('msvkmarket_item_name'),
            dataIndex: 'name',
            sortable: true,
            width: 200
        }, {
            header: _('msvkmarket_compilation_group_name'),
            //dataIndex: 'group_id',
            dataIndex: 'groupname',
            sortable: true,
            width: 150
        }, {
            header: _('msvkmarket_grid_actions'),
            dataIndex: 'actions',
            renderer: msVKMarket.utils.renderActions,
            sortable: false,
            width: 75,
            id: 'actions'
        }];
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus" style="margin-right:7px"></i>' + _('msvkmarket_compilation_create'),
            handler: this.createCompilation,
            scope: this
        },{
            text: '<i class="icon icon-download" style="margin-right:7px"></i>' + _('msvkmarket_compilation_export'),
            //handler: this.exportCompilation,
            handler: function() {
            var w = MODx.load({
                        xtype: 'msvkmarket-compilation-window-export'
                    });
                    w.show();
                },
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
    }
});
Ext.reg('msvkmarket-grid-compilation', msVKMarket.grid.Compilation);
