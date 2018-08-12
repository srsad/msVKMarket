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

/* modx-combo
msVKMarket.window.ExportCompilation = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('msvkm_option_export_albom')
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,handler: function() { config.closeAction !== 'close' ? this.hide() : this.close(); }
            ,scope: this
        },{
            text: '<i class="icon icon-download"></i> ' + _('msvkmarket_item_export')
            ,cls: 'primary-button'
            ,anchor: '100%'
            ,handler: this.export
            ,scope: this
        }]
        ,id: 'msvkm_export_albom_form_' + Ext.id()
        ,autoHeight: true
        ,fields: [{
            xtype: 'modx-combo',
            name: 'id',
            id: config.id + '-group_id',
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
        }]
        ,url: msVKMarket.config.connector_url
        ,action: 'mgr/compilation/export'
    });
    msVKMarket.window.ExportCompilation.superclass.constructor.call(this, config);
};
*/
// superboxselect
msVKMarket.window.ExportCompilation = function(config) {
    config = config || {};
    config.closeAction = 'close';

    Ext.applyIf(config, {
        title: _('msvkmarket_export')
        ,id: Ext.id() + '-msvkmarket_export_albom'
        ,autoHeight: true
        ,fields: [{
            xtype: 'combo-superselect-groups',
            fieldLabel: _('msvkmarket_group_select'),
            name: 'groups_id',
            id: Ext.id() + 'groups_id'
        }]
        //,url: msVKMarket.config.connector_url
        //,action: 'mgr/compilation/export'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,handler: function() { config.closeAction !== 'close' ? this.hide() : this.close(); }
            ,scope: this
        },{
            text: '<i class="icon icon-download"></i> ' + _('msvkmarket_export')
            ,cls: 'primary-button'
            ,anchor: '100%'
            ,handler: this.export
            ,scope: this
        }]
    });
    msVKMarket.window.ExportCompilation.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.window.ExportCompilation, MODx.Window,{
    export: function () {
        var window = this;
        var groupids = Ext.getCmp(this.fields[0].id);
        groupids = groupids.items.items;
        var grid = Ext.getCmp('msvkmarket-grid-compilation');
        var ids = [];

        for(var i = 0; i < groupids.length; i++){ ids.push(groupids[i].value); }
        ids = ids.join(',');

        if (ids === '') {return true;}

        var w = new msVKMarket.window.Console({
            register  : 'mgr',
            autoScroll : true,
            baseParams : {
                action: 'mgr/compilation/export',
                id: ids,
                step: 0
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
        w.log({message: _('msvkmarket_compilation_export'), level: 3});
        w.on('complete', function(){
            window.close();
            grid.refresh();
        });

    }


});
Ext.reg('msvkmarket-compilation-window-export', msVKMarket.window.ExportCompilation);

/*
var topic = '/mytopic/';
var register = 'mgr';
var console = MODx.load({
    xtype: 'modx-console'
    ,register: register
    ,topic: topic
    ,show_filename: 0
    ,listeners: {
        'shutdown': {fn:function() {
                // do code here when you close the console
            },scope:this}
    }
});
console.show(Ext.getBody());
*/
