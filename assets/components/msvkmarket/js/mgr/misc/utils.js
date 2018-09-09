msVKMarket.utils.renderBoolean = function (value) {
    return value
        ? String.format('<span class="green">{0}</span>', _('yes'))
        : String.format('<span class="red">{0}</span>', _('no'));
};

msVKMarket.utils.getMenu = function (actions, grid, selected) {
    var menu = [];
    var cls, icon, title, action;

    var has_delete = false;
    for (var i in actions) {
        if (!actions.hasOwnProperty(i)) {
            continue;
        }

        var a = actions[i];
        if (!a['menu']) {
            if (a == '-') {
                menu.push('-');
            }
            continue;
        }
        else if (menu.length > 0 && !has_delete && (/^remove/i.test(a['action']) || /^delete/i.test(a['action']))) {
            menu.push('-');
            has_delete = true;
        }

        if (selected.length > 1) {
            if (!a['multiple']) {
                continue;
            }
            else if (typeof(a['multiple']) == 'string') {
                a['title'] = a['multiple'];
            }
        }

        icon = a['icon'] ? a['icon'] : '';
        if (typeof(a['cls']) == 'object') {
            if (typeof(a['cls']['menu']) != 'undefined') {
                icon += ' ' + a['cls']['menu'];
            }
        }
        else {
            cls = a['cls'] ? a['cls'] : '';
        }
        title = a['title'] ? a['title'] : a['title'];
        action = a['action'] ? grid[a['action']] : '';

        menu.push({
            handler: action,
            text: String.format(
                '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
                cls, icon, title
            ),
            scope: grid
        });
    }

    return menu;
};

msVKMarket.utils.renderActions = function (value, props, row) {

    var res = [];
    var cls, icon, title, action, item;
    for (var i in row.data.actions) {
        if (!row.data.actions.hasOwnProperty(i)) {
            continue;
        }
        var a = row.data.actions[i];
        if (!a['button']) {
            continue;
        }

        icon = a['icon'] ? a['icon'] : '';
        if (typeof(a['cls']) == 'object') {
            if (typeof(a['cls']['button']) != 'undefined') {
                icon += ' ' + a['cls']['button'];
            }
        }
        else {
            cls = a['cls'] ? a['cls'] : '';
        }
        action = a['action'] ? a['action'] : '';
        title = a['title'] ? a['title'] : '';

        item = String.format(
            '<li class="{0}"><button class="msvkmarket-btn msvkmarket-btn-default {1}" action="{2}" title="{3}"></button></li>',
            cls, icon, action, title
        );

        res.push(item);
    }

    return String.format(
        '<ul class="msvkmarket-row-actions">{0}</ul>',
        res.join('')
    );
};

msVKMarket.utils.Image = function (value) {
    if (Ext.isEmpty(value)){
        return '';
    }else {
        if (!/\/\//.test(value)){
            if (!/^\//.test(value)){
                value = '/' + value;
            }
        }
    }
    return String.format('<img src="{0}" style="width: 55px;" />', value);
};

msVKMarket.utils.productLink = function (value, id) {
    if (!value) {
        return '';
    }else if (!id) {
        return value;
    }
    return String.format(
        '<a href="index.php?a=resource/update&id={0}" class="msvkmarket-link" target="_blank">{1}</a>',
        id,
        value
    );
};

msVKMarket.window.Console  = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        baseParams: {
            action: config.action || 'console'
        }
    });

    Ext.applyIf(config,{
        title: 'console',
        modal: Ext.isIE ? false : true,
        closeAction: 'hide',
        collapsible: false,
        maximizable: true,
        resizable: false,
        closable: true,
        shadow: true,
        height: 400,
        width: 650,
        refreshRate: 2,
        cls: 'modx-window modx-console',
        items: [{
            xtype: 'panel',
            itemId: 'body',
            id: 'msvkmarket-compilation-window-export',
            cls: 'x-form-text modx-console-text',
            border: false
        }],
        buttons: [ {
            text: 'OK',
            itemId: 'okBtn',
            disabled: false,
            scope: this,
            handler: this.close
        }],
        keys: [{
            key: Ext.EventObject.S,
            ctrl: true,
            fn: this.download,
            scope: this
        },{
            key: Ext.EventObject.ENTER,
            fn: this.close,
            scope: this
        }],
        autoHeight: false,
        url: msVKMarket.config.connector_url
    });

    config.baseParams.output_format = 'json';
    config.baseParams.in_console_mode = true;   // Для отладки

    msVKMarket.window.Console.superclass.constructor.call(this,config);

    this.on('show', this.startWork);
};
Ext.extend(msVKMarket.window.Console, MODx.Window,{
    startWork: function(){ this.submit(); },
    submit: function(close) {
        close = close === false ? false : true;
        var f = this.fp.getForm();
        if (f.isValid() && this.fireEvent('beforeSubmit',f.getValues())) {
            f.submit({
                scope: this,
                failure: function(frm,response) {
                    var response = Ext.decode(response.response.responseText);
                    response.level = response.level || 1;
                    this.log(response);
                    if (typeof(this.updateQuery) == 'function') { this.updateQuery(); }
                },
                success: function(frm, response) {
                    if (typeof(this.updateQuery) == 'function') { this.updateQuery(); }
                    try{
                        var response = Ext.decode(response.response.responseText);
                        this.log(response);
                        if (!response.continue) {
                            this.fireEvent('complete');
                            this.fbar.setDisabled(false);
                            return;
                        }
                    }
                    catch(e){
                        alert(_('msvkmarket_error_response'));
                        console.log(e);
                        return;
                    }
                    if(this.isVisible()){ this.submit();}
                }
            });
        }
    },
    log: function(response){
        try{
            var msg = response.message;
            var level = response.level;
            var cls = '';

            switch(level) {
                case 1: cls = 'error'; break;
                case 2: cls = 'warn'; break;
                case 3: cls = 'info'; break;
                case 4: cls = 'debug'; break;
            }

            msg = '<p class="' + cls + '">' + msg + '</p>';

            var out = this.getComponent('body');
            if (out) {
                out.el.insertHtml('beforeEnd', msg);
                out.el.scroll('b', out.el.getHeight(), true);
            }
        }
        catch(e){ alert(e); return false; }
    }
});