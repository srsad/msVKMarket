msVKMarket.combo.Search = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    msVKMarket.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
    });
    this.addEvents('clear', 'search');
};
Ext.extend(msVKMarket.combo.Search, Ext.form.TwinTriggerField, {

    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        };
    },

    _triggerSearch: function () {
        this.fireEvent('search', this);
    },

    _triggerClear: function () {
        this.fireEvent('clear', this);
    },

});
Ext.reg('msvkmarket-combo-search', msVKMarket.combo.Search);
Ext.reg('msvkmarket-field-search', msVKMarket.combo.Search);

msVKMarket.combo.Groups = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'superboxselect',
        name: 'groups_id',
        id: Ext.id() + '-groups_id',
        msgTarget: 'under',
        valueField: 'id',
        store: new Ext.data.JsonStore({
            root: 'results',
            id: 'group-store',
            autoLoad: false,
            autoSave: false,
            totalProperty: 'total',
            fields: ['id', 'name'],
            url: msVKMarket.config.connector_url,
            baseParams: {
                action: 'mgr/group/getlist',
                where: '{"status":"1"}'
            }
        }),
        tpl:  '<tpl for="."><div class="x-combo-list-item"><span>({id})</span> - {name}</div></tpl>',
        mode: 'remote',
        displayField: 'name',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger'
    });

    msVKMarket.combo.Groups.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.combo.Groups, Ext.ux.form.SuperBoxSelect); // SuperBoxSelect
Ext.reg('combo-superselect-groups', msVKMarket.combo.Groups);


// поборки к данным группам
msVKMarket.combo.CompilationSS = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        xtype: 'superboxselect',
        name: 'compilation_id',
        allowBlank: true,
        msgTarget: 'under',
        allowAddNewData: true,
        addNewDataOnBlur: true,
        pinList: false,
        resizable: true,
        minChars: 1,
        disabled: true,
        store: new Ext.data.JsonStore({
            id: 'compilation-store',
            root: 'results',
            autoLoad: false,
            autoSave: false,
            totalProperty: 'total',
            fields: ['id', 'name', 'group_id', 'groupname'],
            //data: ['id', 'name', {id:'1', name: 'float'}, {id:'2', name:'date'}],
            url: msVKMarket.config.connector_url,
            baseParams: {
                action: 'mgr/compilation/getlist',
                ids: config.groupIds != undefined ? config.groupIds : ''
            }
        }),
        tpl: '<tpl for="."><div class="x-combo-list-item"> \
							<b>{name}</b><br> \
							<span><small>({group_id}) - {groupname}</small></span>\
						</div></tpl>',
        mode: 'remote',
        displayField: 'name',
        valueField: 'id',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger'
    });
    msVKMarket.combo.CompilationSS.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket.combo.CompilationSS, Ext.ux.form.SuperBoxSelect);
Ext.reg('combo-superselect-compilation', msVKMarket.combo.CompilationSS);