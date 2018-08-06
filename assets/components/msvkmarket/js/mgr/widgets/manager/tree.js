
msVKMarket.tree.Categories = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'msvkmarket-tree';
    }

    Ext.applyIf(config, {
        url: msVKMarket.config.connector_url,
        title: '',
        anchor: '99%',
        rootVisible: false,
        expandFirst: true,
        enableDD: false,
        remoteToolbar: false,
        action: 'mgr/manager/getnodes',
        baseParams: {
            categories: config['categories'] || '',
            options: config['options'] || ''
        },
        stateful: false,
        listeners: this.getListeners(config)
    });

    msVKMarket.tree.Categories.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.tree.Categories, MODx.tree.Tree, {
    getListeners: function () {
        return {
            checkchange: function () {
                var grid = Ext.getCmp(this.optionGrid);
                if (grid) {
                    var checkedNodes = this.getChecked();
                    var categories = [];
                    for (var i = 0; i < checkedNodes.length; i++) {
                        categories.push(checkedNodes[i].attributes.pk);
                    }
                    var s = grid.getStore();
                    s.baseParams.categories = Ext.util.JSON.encode(categories);
                    grid.getBottomToolbar().changePage(1);
                }
            }
        };
    },
    _showContextMenu: function (n, e) {
        n.select();
        this.cm.activeNode = n;
        this.cm.removeAll();
        var m = [];
        m.push({
            text: '<i class="x-menu-item-icon icon icon-refresh"></i> ' + _('msvkmarket_tree_update')
            ,handler: function () {
                this.refreshNode(this.cm.activeNode.id, true);
            }
        },{
            text: '<i class="x-menu-item-icon icon icon-level-down"></i> ' + _('msvkmarket_tree_expand')
            ,handler: function () {
                this.cm.activeNode.expand(true);
            }
        },{
            text: '<i class="x-menu-item-icon icon icon-level-up"></i> ' + _('msvkmarket_tree_collapse')
            ,handler: function () {
                this.cm.activeNode.collapse(true);
            }
        },{
            text: '<i class="x-menu-item-icon icon icon-check-square-o"></i> ' + _('msvkmarket_tree_select_all')
            ,handler: function () {
                var activeNode=this.cm.activeNode;
                var checkchange=this.getListeners().checkchange;

                function massCheck(node){
                    node.getUI().toggleCheck(true);
                    node.expand(false,false,function(node){
                        node.eachChild(massCheck);
                        if(node==activeNode){
                            checkchange();
                        }
                    });
                }
                massCheck(activeNode);
            }
        },{
            text: '<i class="x-menu-item-icon icon icon-square-o"></i> ' + _('msvkmarket_tree_clear_all'),
            handler: function () {
                var activeNode=this.cm.activeNode;
                var checkchange=this.getListeners().checkchange;

                function massUncheck(node){
                    node.getUI().toggleCheck(false);
                    node.eachChild(massUncheck);
                    if(node==activeNode){
                        checkchange();
                    }
                }
                massUncheck(activeNode);
            }
        });
        this.addContextMenuItem(m);
        this.cm.showAt(e.xy);
        e.stopEvent();
    }
});
Ext.reg('msvkmarket-tree-categories', msVKMarket.tree.Categories);