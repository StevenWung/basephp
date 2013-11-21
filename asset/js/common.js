/*
 JS Util
 author StevenWung
 1.1.0
 StevenWung@yeah.net
 */

var BASE_DIR = '/';
var Util = Util || {};
if(typeof $ == 'undefined'){
    //window.onload = function(){
    //    document.write("to use common lib, load jquery first");
    //}

}
Util.extendObj = function(dest, from) {
    var prop;
    if (typeof dest !== 'object' || typeof from !== 'object') {
        return false;
    }
    for (prop in from) {
        if (from.hasOwnProperty(prop)) {
            dest[prop] = from[prop];
        }
    }
};

Util.objToString = function(obj){
    var temp = '';
    if( obj == null || typeof obj != 'object' ){
        return false;
    }
    for (prop in obj ){
        temp += prop + '=' + obj[prop];
        temp += '&';
    }
    return temp.slice(0, temp.length - 1);
};
Util.getJSON = function(d){
    if ( d == null || d == '' ){
        return null;
    }else{
        try{
            return eval('('+d+')');
        }catch(e){
            return null;
        }
    }
};
Util.Window = function(){
    this.height = function(){
        return $(document).height();
    }
    this.mheight = function(){
        return $(document).height() / 2;
    }
    this.width = function(){
        return $(document).width();
    }
    this.mwidth = function(){
        return $(document).width() / 2;
    }
    this.open = function(url, mode){
        if(typeof mode == 'undefined'){
            mode = '_blank';
        }
        var win = window.open(url, mode);
        win.opener = null;
        return false;
    }
}

Util.Ajax = function(options){
    var self = this;
    var status = [
        'error',
        'syserr',
        'login'
    ];
    self._setting = {
        url:'url',
        data:'',
        logincheck:false,
        method:'POST',
        success:function(){},
        failure:function(){},
        progress:function(){}
    };
    Util.extendObj(self._setting, options);
    self.newXhr();

}
Util.Ajax.prototype = {
    newXhr:function(){
        var self = this;
        this.xhr = null;

        if (window.XMLHttpRequest){
            self.xhr = new XMLHttpRequest();
        }else{
            self.xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
        self.xhr.onreadystatechange = function(req){
            if (  self.xhr.readyState==4 &&  self.xhr.status==200 ){
                self.onSuccess( self.xhr.responseText );
            }else
            if (  self.xhr.readyState==4 &&  self.xhr.status==404 ){
                self.onFailure( '404' );
            }else{
                //throw 'error';
            }
        };
    },
    post:function(data){
        this._setting.method = 'POST';
        this.request(data);
    },
    get:function(data){
        this._setting.method = 'GET';
        this.request(data);
    },
    request : function(data){
        var self = this;
        var postStr = '';
        var url = this._setting.url;
        if( data )
            data = data + '&___ajak___=' + new Util.Math().key();
        else
            data = '___ajak___=' + new Util.Math().key();
        if( typeof data == 'object' ){
            postStr = Util.objToString(data);
        }else
        if( typeof data == 'string' ){
            postStr = data;
        }
        else{
            return ;
        }
        target_url = BASE_DIR + url;
        if(target_url.indexOf('//') == 0){
            target_url = target_url.replace('//','/')
        }
        if( this._setting.method == 'POST' ){
            self.xhr.open('POST', target_url, true);
        }else{
            var url = url.indexOf('?')>-1?url+'&'+postStr:url+'?'+postStr;
            self.xhr.open('GET',target_url, true);
        }
        self.xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        self.xhr.send(postStr);
    },
    onSuccess : function(data){
        var self = this;
        var resp = Util.getJSON(data);
        if( resp != null && typeof resp.cmd != 'undefined')
        switch(resp.cmd){
            case 'redirect':{
                window.location = BASE_DIR + resp.location;
                break;
            }
            case 'login':{
                var login = new Util.Dialog();
                login.pop('login');
                break;
            }
            default:{
                self._setting.success(resp.data);;
            }
        }

    },
    onFailure : function(type){
    }
};
Util.Form = function(config){
    this.formdata  = '1';
    this._setting = {
        hint : true,
        check : true,
        form : '',
        name : '',
        ajax : true,
        action : '',
        subtn: null,
        richedit: null,
        elements : [],
        richeditors : []
    };
    Util.extendObj(this._setting, config);
    if( this._setting.name == null || this._setting.name == '' ){
        throw ('form name should be specified.');
    }
    this.bind();
};
Util.Form.prototype = {
    bind:function(){
        var self = this;
        var name = self._setting.name;
        var form = $("form[name='"+name+"']");
        if( form ){
            //return;
        }
        self._setting.action = form.attr('action');
        form.bind('submit',  function(){self.submit();});
        var sbt = form.find("input[name='" + self._setting.subtn+"']");
        $(sbt[0]).bind('click', function(){self.submit();});
        if( form.length == 0 ){throw "form is not found."}
        if( self._setting.richedit != null && self._setting.richedit.length > 0 ){
            for(var i=0;i<self._setting.richedit.length;i++){
                var id = self._setting.richedit[i].id;
                var width = self._setting.richedit[i].width|870;
                var editor = new Util.UEditor({ id : id, width: width});
                self._setting.richeditors.push(editor);
            }
        }
    },
    check:function(){

    },
    submit:function(){

        var setting = this._setting;
        var form = $("form[name='" + setting.name+"']");
        var action = form.attr('action');
        action = action || ".";
        setting.elements = [];
        if( setting.richeditors && setting.richeditors.length ){
            for(var i=0;i<setting.richeditors.length;i++){
                var ed = setting.richeditors[i];
                setting.richeditors[i]._setting.editor.post();
                //$('#'+setting.richedit[i].id).va(setting.richeditors[i].getContent());
            }
        }
        form.find('input').each(function(){
            setting.elements.push(this);
        });
        form.find('select').each(function(){
            setting.elements.push(this);
        });
        //console.log( form.find('textarea').length );
        form.find('textarea').each(function(){
            setting.elements.push(this);
        });
        if( setting.ajax ){
            setting.formdata = '';
            for(var i=0;i<setting.elements.length;i++){
                var ele = setting.elements[i];
                if( ele.type == 'submit' || ele.type == 'button' )continue;
                if( ele.type == 'textarea' ){
                    console.log($(ele).val());
                }
                if( ele.type == 'select' ){
                    setting.formdata += ele.name + '=' + $(ele).val() + '&';
                }
                if( ele.type == 'checkbox' ){
                    //alert($(ele).prop('checked'));
                    setting.formdata += ele.name + '=' + $(ele).prop('checked') + '&';
                }else
                    setting.formdata += ele.name + '=' + ele.value + '&';
            }
            setting.formdata += '___key___='+new Util.Math().key()+'&___form___='+new Util.Math().key();
            var ajax = new Util.Ajax({url:action,logincheck:true});
            ajax.post(setting.formdata);
            return false;
        }else{
            //form.unbind('submit', self.submit);
            //e.stopPropagation();
            //form.submit();
            return true;
            //window.event.cancelBubble = false;
        }
    }
}
Util.Form.bindCheckBox = function(){
    $('.body').find('.checkbox').each(function(index, element) {
        var name = $(element).attr('name');
        if( $(element).hasClass('checked'))
            $(element).append($("<input type='checkbox' style='display: none' name='"+name+"' checked/> "));
        else
            $(element).append($("<input type='checkbox' style='display: none' name='"+name+"' /> "));
        $(element).bind('click', function(){
            if( $(element).hasClass('checked') ){
                $(element).removeClass('checked').addClass('unchecked');
                $(element).find("input[type='checkbox']").attr('checked', false);
            }else{
                $(element).removeClass('unchecked').addClass('checked');
                $(element).find("input[type='checkbox']").attr('checked', true);
            }
        });

    });

}
Util.Math = function(option){};
Util.Math.prototype = {
    key:function(){
        var skey = Math.random() * 100;
        return skey;
    }
}
window.alert1 = (function (original) {
    return function (str) {
        if(console) {
            console.log(str)
        }
        //original('--'+str)
        new Util.Dialog().open();
    }
})(window.alert);

/* dialog box */

Util.Dialog = function(options){
    _dialog_setting = {
        title:"Dialog",
        autosize:true,
        width:300,
        height:200
    };
    this.init();
}
Util.Dialog.prototype = {
    init:function(){
        $(document).html('');
    },
    open:function(){

    },
    close:function(){

    },
    pop:function(id){
        if( id == 'login' ){
            $('#loginbox').fadeIn(700);
            $('#loginbox .bg').css('height', $(document).height() + 'px');

            var bHeight = $('#loginbox .loginbox').height();
            var bWidth  = $('#loginbox .loginbox').width();
            var wHeight = $(window).height();
            var wWidth  = $(window).width();
            $('#loginbox .loginbox').css('left', ((wWidth - bWidth) / 2 ) + 'px');
            $('#loginbox .loginbox').css('top',  ((wHeight - bHeight) / 2 * 0.7 ) + 'px');
        }
    }
}

Util.UEditor = function(config){
    this.formdata  = '1';
    this._setting = {
        id:null,
        width:640,
        height:155,
        cssclass:'te',
        controlclass:'tecontrol',
        rowclass:'teheader',
        dividerclass:'tedivider',
        controls:['bold','italic','underline','strikethrough','|','subscript','superscript','|',
        'orderedlist','unorderedlist', //'video',
        //'image'
        //'|','outdent','indent','|','leftalign',
        //'centeralign','rightalign','blockjustify','|','unformat','|','undo','redo','n',
        //'font','size','style','|','image','hr','link','unlink','|',
        //'cut','copy','paste','print','|', 'video'
        ],
        footer:true,
        fonts:['Verdana','Arial','Georgia','Trebuchet MS','宋体'],
        xhtml:true,
        cssfile:'style.css',
        bodyid:'editor',
        footerclass:'tefooter',
        toggle:{text:'源码',activetext:'可视化',cssclass:'toggle'},
        resize:{cssclass:'resize'},
        editor:null
    };
    Util.extendObj(this._setting, config);
    if( this._setting.id == null || this._setting.id == '' ){
        throw ('form name should be specified.');
    }
    this.init();
}
Util.UEditor.prototype = {
    init:function(){
        var self = this;
        if( typeof  TINY == 'undefined' ){
            $('head').append('<link rel="stylesheet" href="'+BASE_DIR+'asset/teditor/style.css'+'" type="text/css" />');
            $.getScript(BASE_DIR+'asset/teditor/tinyeditor.js', function(){
                //alert('asdf');
                self.bind();
            });
        }
    },
    bind:function(){
        var self = this;
        self._setting.editor = new TINY.editor.edit('editor', self._setting);;
    }
};



Util.Slider = function(options){
    this._s_setting = {
        id:'slider',
        cidx:0,
        count:0,
        height:200,
        width:400,
        withbtns:true
    };
    this.init();
    this.bind();
};
Util.Slider.prototype = {
    init:function(){
        var self = this;
        var setting = self._s_setting;

        var count = $('#' + setting.id + ' .content').length;
        setting.count = count;
        setting.width = $('#' + setting.id).parent().width();
        setting.height = $('#' + setting.id).parent().height();
        $('#' + setting.id).css('width', setting.width + 'px');
        $('#' + setting.id).css('height', setting.height + 'px');
        //alert(setting.width);
        $('#' + setting.id + ' .box').css('width', (setting.count * setting.width ) + 'px');
        $('#' + setting.id + ' .content img').css('width', setting.width + 'px').css('height', setting.height + 'px');
        if( setting.withbtns == true ){
            var html = '<div class="buttons">';
            for(var i=0;i<setting.count;i++){
                if(i==0)html += '<div class="button current"></div>';
                else{html += '<div class="button"></div>'};
            };
            html += '</div>';
            $('#' + setting.id).append($(html));
            $("#" + setting.id + ' .buttons').css('top', (setting.height - 23 ) + 'px');
        }
    },
    bind:function(){
        var self = this;
        var setting = self._s_setting;
        $("#" + setting.id ).hover( function() {

            clearInterval( setting.slidetimer );
        },function() {
            setting.slidetimer = setInterval(function() {
                self.show( setting.cidx );
                setting.cidx ++;
                if( setting.cidx == setting.count ) { setting.cidx = 0; }
            },4000);
        }).trigger("mouseleave");
        $( "#" + setting.id + ' .buttons .button').each(function(id){
            $(this).bind('click',function(){
                self.show(id);
                setting.cidx = id;
            });
        });
    },
    show:function(idx){
        var self = this;
        var setting = self._s_setting;
        var nowLeft = - idx * setting.width;
        //alert(nowLeft);
        $("#"+setting.id+" .box").stop(true,false).animate({"left":nowLeft},300);
        $( "#" + setting.id + ' .buttons .button').removeClass('current').eq(idx).addClass('current');
    }
};