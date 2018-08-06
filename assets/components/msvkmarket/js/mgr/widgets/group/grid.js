msVKMarket.grid.Group = function (config) {
  config = config || {};
  if(!config.id){
      config.id = 'msvkmarket-grid-group';
  }

  this.sm = new Ext.grid.CheckboxSelectionModel();

  Ext.applyIf(config, {
      url: msVKMarket.config.connector_url,
      fields: this.getFields(config),
      columns: this.getColumns(config),
      tbar: this.getTopBar(config),
      sm: this.sm,
      baseParams: {
          action: 'mgr/group/getlist'
      },
      listeners: {
          rowDblClick: function (grid, rowIndex, e) {
              var row = grid.store.getAt(rowIndex);
              this.updateGroup(grid, e, row);
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

    msVKMarket.grid.Group.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);

};
Ext.extend(msVKMarket.grid.Group, MODx.grid.Grid, {
    windows: {},
    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = msVKMarket.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    creatGroup: function (btn, e) {
        var w = MODx.load({
            xtype: 'msvkmarket-group-window-create',
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

    updateGroup: function (btn, e, row) {
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
                action: 'mgr/group/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'msvkmarket-group-window-update',
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

    removeGroup: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('msvkmarket_groups_remove')
                : _('msvkmarket_group_remove'),
            text: ids.length > 1
                ? _('msvkmarket_groups_remove_confirm')
                : _('msvkmarket_group_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/group/remove',
                ids: Ext.util.JSON.encode(ids),
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

    disableGroup: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/group/disable',
                ids: Ext.util.JSON.encode(ids),
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

    enableGroup: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/group/enable',
                ids: Ext.util.JSON.encode(ids),
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
        return ['id', 'name', 'app_id', 'secretkey', 'token', 'group_id', 'status', 'actions'];
    },

    getColumns: function () {
        return [
            this.sm, {
            header: _('msvkmarket_item_id'),
            dataIndex: 'id',
            sortable: true,
            width: 50
        }, {
            header: _('msvkmarket_item_name'),
            dataIndex: 'name',
            sortable: true,
            width: 450
        }, {
            header: _('msvkmarket_item_active'),
            dataIndex: 'status',
            renderer: msVKMarket.utils.renderBoolean,
            sortable: true,
            width: 70
        }, {
            header: _('msvkmarket_grid_actions'),
            dataIndex: 'actions',
            renderer: msVKMarket.utils.renderActions,
            sortable: false,
            width: 100,
            id: 'actions'
        }];
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('msvkmarket_group_create'),
            handler: this.creatGroup,
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
Ext.reg('msvkmarket-grid-group', msVKMarket.grid.Group);