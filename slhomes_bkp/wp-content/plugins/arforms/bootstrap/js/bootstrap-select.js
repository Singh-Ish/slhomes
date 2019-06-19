!function(e){"use strict";function t(e,t){return e.toUpperCase().indexOf(t.toUpperCase())>-1}function i(t){var i=[{re:/[\xC0-\xC6]/g,ch:"A"},{re:/[\xE0-\xE6]/g,ch:"a"},{re:/[\xC8-\xCB]/g,ch:"E"},{re:/[\xE8-\xEB]/g,ch:"e"},{re:/[\xCC-\xCF]/g,ch:"I"},{re:/[\xEC-\xEF]/g,ch:"i"},{re:/[\xD2-\xD6]/g,ch:"O"},{re:/[\xF2-\xF6]/g,ch:"o"},{re:/[\xD9-\xDC]/g,ch:"U"},{re:/[\xF9-\xFC]/g,ch:"u"},{re:/[\xC7-\xE7]/g,ch:"c"},{re:/[\xD1]/g,ch:"N"},{re:/[\xF1]/g,ch:"n"}];return e.each(i,function(){t=t.replace(this.re,this.ch)}),t}function n(t,i){var n=arguments,o=t,t=n[0],i=n[1];[].shift.apply(n),"undefined"==typeof t&&(t=o);var a,l=this.each(function(){var o=e(this);if(o.is("select")){var l=o.data("selectpicker"),d="object"==typeof t&&t;if(l){if(d)for(var r in d)d.hasOwnProperty(r)&&(l.options[r]=d[r])}else{var c=e.extend({},s.DEFAULTS,e.fn.selectpicker.defaults||{},o.data(),d);o.data("selectpicker",l=new s(this,c,i))}"string"==typeof t&&(a=l[t]instanceof Function?l[t].apply(l,n):l.options[t])}});return"undefined"!=typeof a?a:l}e.expr[":"].icontains=function(i,n,s){return t(e(i).text(),s[3])},e.expr[":"].aicontains=function(i,n,s){return t(e(i).data("normalizedText")||e(i).text(),s[3])};var s=function(t,i,n){n&&(n.stopPropagation(),n.preventDefault()),this.$element=e(t),this.$newElement=null,this.$button=null,this.$menu=null,this.$lis=null,this.options=i,null===this.options.title&&(this.options.title=this.$element.attr("title")),this.val=s.prototype.val,this.render=s.prototype.render,this.refresh=s.prototype.refresh,this.setStyle=s.prototype.setStyle,this.selectAll=s.prototype.selectAll,this.deselectAll=s.prototype.deselectAll,this.destroy=s.prototype.remove,this.remove=s.prototype.remove,this.show=s.prototype.show,this.hide=s.prototype.hide,this.init()};s.VERSION="1.6.2",s.DEFAULTS={noneSelectedText:"Nothing selected",noneResultsText:"No results match",countSelectedText:function(e,t){return 1==e?"{0} item selected":"{0} items selected"},maxOptionsText:function(e,t){var i=[];return i[0]=1==e?"Limit reached ({n} item max)":"Limit reached ({n} items max)",i[1]=1==t?"Group limit reached ({n} item max)":"Group limit reached ({n} items max)",i},selectAllText:"Select All",deselectAllText:"Deselect All",multipleSeparator:", ",style:"btn-default",size:"auto",title:null,selectedTextFormat:"values",width:!1,container:!1,hideDisabled:!1,showSubtext:!1,showIcon:!0,showContent:!0,dropupAuto:!1,header:!1,liveSearch:!1,actionsBox:!1,iconBase:"glyphicon",tickIcon:"glyphicon-ok",maxOptions:!1,mobile:!1,selectOnTab:!1,dropdownAlignRight:!1,searchAccentInsensitive:!1},s.prototype={constructor:s,init:function(){var t=this,i=this.$element.attr("id");this.$element.css({position:"absolute",visibility:"hidden"}),this.multiple=this.$element.prop("multiple"),this.autofocus=this.$element.prop("autofocus"),this.$newElement=this.createView(),this.$element.after(this.$newElement),this.$menu=this.$newElement.find("> .arfdropdown-menu"),this.$button=this.$newElement.find("> button"),this.$searchbox=this.$newElement.find("input"),"undefined"!=typeof i&&(this.$button.attr("data-id",i),e('label[for="'+i+'"]').click(function(e){e.preventDefault(),t.$button.focus()})),this.checkDisabled(),this.clickListener(),this.render(),this.liHeight(),this.setStyle(),this.setWidth(),this.options.container&&this.selectPosition(),this.$menu.data("this",this),this.$newElement.data("this",this),this.options.mobile&&this.mobile()},createDropdown:function(){var t=this.multiple?" show-tick":"",i=(this.$element.parent().hasClass("input-group")?" input-group-btn":"",this.autofocus?" autofocus":"",this.$element.parents().hasClass("form-group-lg")?" btn-lg":this.$element.parents().hasClass("form-group-sm")?" btn-sm":"",this.options.header?'<div class="popover-title"><button type="button" class="close" aria-hidden="true">&times;</button>'+this.options.header+"</div>":""),n=this.options.liveSearch?'<div class="bs-searchbox"><input type="text" class="input-block-level form-control" autocomplete="off" /></div>':"",s=(this.options.actionsBox?'<div class="bs-actionsbox"><div class="btn-group btn-block"><button class="actions-btn bs-select-all btn btn-sm btn-default">'+this.options.selectAllText+'</button><button class="actions-btn bs-deselect-all btn btn-sm btn-default">'+this.options.deselectAllText+"</button></div></div>":"",'<div class="btn-group bootstrap-select'+t+'"><button type="button" class="arfbtn dropdown-toggle" data-toggle="arfdropdown"><div class="filter-option pull-left"></div>&nbsp;<div class="arf_caret"></div></button><div class="arfdropdown-menu open">'+i+n+'<ul class="arfdropdown-menu inner" role="menu"></ul></div></div>');return e(s)},createView:function(){var e=this.createDropdown(),t=this.createLi();return e.find("ul").append(t),e},reloadLi:function(){this.destroyLi();var e=this.createLi();this.$menu.find("ul").append(e)},destroyLi:function(){this.$menu.find("li").remove()},createLi:function(){var t=this,n=[],s=0,o=function(e,t,i){return"<li"+("undefined"!=typeof i?' class="'+i+'"':"")+("undefined"!=typeof t|null===t?' data-original-index="'+t+'"':"")+">"+e+"</li>"},a=function(n,s,o,a){var l=i(e.trim(e("<div/>").html(n).text()).replace(/\s\s+/g," "));return 0==l&&(n="0"),'<a tabindex="0"'+("undefined"!=typeof s?' class="'+s+'"':"")+("undefined"!=typeof o?' style="'+o+'"':"")+("undefined"!=typeof a?'data-optgroup="'+a+'"':"")+' data-normalized-text="'+l+'">'+n+'<i class="'+t.options.iconBase+" "+t.options.tickIcon+' check-mark"></i></a>'};return this.$element.find("option").each(function(){var i=e(this),l=i.attr("class")||"",d=i.attr("style"),r=i.data("content")?i.data("content"):i.html(),c="undefined"!=typeof i.data("subtext")?'<small class="muted text-muted">'+i.data("subtext")+"</small>":"",h="undefined"!=typeof i.data("icon")?'<i class="'+t.options.iconBase+" "+i.data("icon")+'"></i> ':"",p=i.is(":disabled")||i.parent().is(":disabled"),u=i[0].index;if(""!==h&&p&&(h="<span>"+h+"</span>"),i.data("content")||(0==i.data("content")&&(r="0"),r=h+'<span class="text">'+r+c+"</span>"),!t.options.hideDisabled||!p)if(i.parent().is("optgroup")&&i.data("divider")!==!0){if(0===i.index()){s+=1;var f=i.parent().attr("label"),m="undefined"!=typeof i.parent().data("subtext")?'<small class="muted text-muted">'+i.parent().data("subtext")+"</small>":"",v=i.parent().data("icon")?'<i class="'+t.options.iconBase+" "+i.parent().data("icon")+'"></i> ':"";f=v+'<span class="text">'+f+m+"</span>",0!==u&&n.length>0&&n.push(o("",null,"divider")),n.push(o(f,null,"dropdown-header"))}n.push(o(a(r,"opt "+l,d,s),u))}else n.push(i.data("divider")===!0?o("",u,"divider"):i.data("hidden")===!0?o(a(r,l,d),u,"hide is-hidden"):o(a(r,l,d),u))}),this.multiple||0!==this.$element.find("option:selected").length||this.options.title||this.$element.find("option").eq(0).prop("selected",!0).attr("selected","selected"),e(n.join(""))},findLis:function(){return null==this.$lis&&(this.$lis=this.$menu.find("li")),this.$lis},render:function(t){var i=this;t!==!1&&this.$element.find("option").each(function(t){i.setDisabled(t,e(this).is(":disabled")||e(this).parent().is(":disabled")),i.setSelected(t,e(this).is(":selected"))}),this.tabIndex();var n=this.options.hideDisabled?":not([disabled])":"",s=this.$element.find("option:selected"+n).map(function(){var t,n=e(this),s=n.data("icon")&&i.options.showIcon?'<i class="'+i.options.iconBase+" "+n.data("icon")+'"></i> ':"";return t=i.options.showSubtext&&n.attr("data-subtext")&&!i.multiple?' <small class="muted text-muted">'+n.data("subtext")+"</small>":"",n.data("content")&&i.options.showContent?n.data("content"):"undefined"!=typeof n.attr("title")?n.attr("title"):s+n.html()+t}).toArray(),o=this.multiple?s.join(this.options.multipleSeparator):s[0];if(this.multiple&&this.options.selectedTextFormat.indexOf("count")>-1){var a=this.options.selectedTextFormat.split(">");if(a.length>1&&s.length>a[1]||1==a.length&&s.length>=2){n=this.options.hideDisabled?", [disabled]":"";var l=this.$element.find("option").not('[data-divider="true"], [data-hidden="true"]'+n).length,d="function"==typeof this.options.countSelectedText?this.options.countSelectedText(s.length,l):this.options.countSelectedText;o=d.replace("{0}",s.length.toString()).replace("{1}",l.toString())}}this.options.title=this.$element.attr("title"),"static"==this.options.selectedTextFormat&&(o=this.options.title),0==o&&(this.options.title=0),o||(o="undefined"!=typeof this.options.title?this.options.title:this.options.noneSelectedText),this.$newElement.find(".filter-option").html(o)},setStyle:function(e,t){this.$element.attr("class")&&this.$newElement.addClass(this.$element.attr("class").replace(/selectpicker|mobile-device|validate\[.*\]/gi,""));var i=e?e:this.options.style;"add"==t?this.$button.addClass(i):"remove"==t?this.$button.removeClass(i):(this.$button.removeClass(this.options.style),this.$button.addClass(i))},liHeight:function(){if(this.options.size!==!1){var e=this.$menu.parent().clone().find("> .arfdropdown-toggle").prop("autofocus",!1).end().appendTo("body"),t=e.addClass("open").find("> .arfdropdown-menu"),i=t.find("li").not(".divider").not(".dropdown-header").filter(":visible").children("a").outerHeight(),n=this.options.header?t.find(".popover-title").outerHeight():0,s=this.options.liveSearch?t.find(".bs-searchbox").outerHeight():0,o=this.options.actionsBox?t.find(".bs-actionsbox").outerHeight():0;e.remove(),this.$newElement.data("liHeight",i).data("headerHeight",n).data("searchHeight",s).data("actionsHeight",o)}},setSize:function(){this.findLis();var t,i,n,s=this,o=this.$menu,a=o.find(".inner"),l=this.$newElement.outerHeight(),d=this.$newElement.data("liHeight"),r=this.$newElement.data("headerHeight"),c=this.$newElement.data("searchHeight"),h=this.$newElement.data("actionsHeight"),p=this.$lis.filter(".divider").outerHeight(!0),u=parseInt(o.css("padding-top"))+parseInt(o.css("padding-bottom"))+parseInt(o.css("border-top-width"))+parseInt(o.css("border-bottom-width")),f=this.options.hideDisabled?", .disabled":"",m=e(".arf_fieldset"),v=u+parseInt(o.css("margin-top"))+parseInt(o.css("margin-bottom"))+2,b=function(){i=s.$newElement.offset().top-m.scrollTop(),n=m.height()-i-l};if(b(),this.options.header&&o.css("padding-top",0),"auto"==this.options.size){var $=function(){var e,l=s.$lis.not(".hide");b(),t=n-v,s.options.dropupAuto&&s.$newElement.toggleClass("dropup",i>n&&t-v<o.height()),s.$newElement.hasClass("dropup")&&(t=i-v),e=l.length+l.filter(".dropdown-header").length>3?3*d+v-2:0,o.css({"max-height":t+"px",overflow:"hidden","min-height":e+r+c+h+"px"}),a.css({"max-height":t-r-c-h-u+"px","overflow-y":"auto","min-height":Math.max(e-u,0)+"px"})};$(),this.$searchbox.off("input.getSize propertychange.getSize").on("input.getSize propertychange.getSize",$),e(window).off("resize.getSize").on("resize.getSize",$),e(window).off("scroll.getSize").on("scroll.getSize",$)}else if(this.options.size&&"auto"!=this.options.size){var g=this.$lis.not(".divider"+f).find(" > *").slice(0,this.options.size).last().parent().index(),x=this.$lis.slice(0,g+1).filter(".divider").length;t=d*this.options.size+x*p+u,s.options.dropupAuto&&this.$newElement.toggleClass("dropup",i>n&&t<o.height());var w=this.$newElement,y=w.offset(),C=y.top,k=C+w.height(),S=w.parents(".arf_form_outer_wrapper");if("undefined"!=typeof S&&w.parents().hasClass("arf_form_outer_wrapper")){var E=S.offset(),D=E.top,A=D+S.height();e(".arf_fieldset").parents().hasClass("arf_flymodal")&&(A-=d)}var T="",z=t+r+c+h;if("undefined"!=typeof S&&w.parents().hasClass("arf_form_outer_wrapper")){var I=A-k;T=t>I?I-10:z}else T=z;o.css({"max-height":T+"px",overflow:"hidden"}),a.css({"max-height":T-5+"px","overflow-y":"auto"})}},setWidth:function(){if("auto"==this.options.width){this.$menu.css("min-width","0");var e=this.$newElement.clone().appendTo("body"),t=e.find("> .dropdown-menu").css("width"),i=e.css("width","auto").find("> button").css("width");e.remove(),this.$newElement.css("width",Math.max(parseInt(t),parseInt(i))+"px")}else"fit"==this.options.width?(this.$menu.css("min-width",""),this.$newElement.css("width","").addClass("fit-width")):this.options.width?(this.$menu.css("min-width",""),this.$newElement.css("width",this.options.width)):(this.$menu.css("min-width",""),this.$newElement.css("width",""));this.$newElement.hasClass("fit-width")&&"fit"!==this.options.width&&this.$newElement.removeClass("fit-width")},selectPosition:function(){var t,i,n=this,s="<div />",o=e(s),a=function(e){o.addClass(e.attr("class").replace(/form-control/gi,"")).toggleClass("dropup",e.hasClass("dropup")),t=e.offset(),i=e.hasClass("dropup")?0:e[0].offsetHeight,o.css({top:t.top+i,left:t.left,width:e[0].offsetWidth,position:"absolute"})};this.$newElement.on("click",function(){n.isDisabled()||(a(e(this)),o.appendTo(n.options.container),o.toggleClass("open",!e(this).hasClass("open")),o.append(n.$menu))}),e(window).resize(function(){a(n.$newElement)}),e(window).on("scroll",function(){a(n.$newElement)}),e("html").on("click",function(t){e(t.target).closest(n.$newElement).length<1&&o.removeClass("open")})},setSelected:function(e,t){this.findLis(),this.$lis.filter('[data-original-index="'+e+'"]'),this.$element.hasClass("arf_select_controll")&&(t?this.$lis.filter('[data-original-index="'+e+'"]').addClass("selected").find("a").attr("href","#").attr("tabindex",0):this.$lis.filter('[data-original-index="'+e+'"]').removeClass("selected").find("a").attr("href","#").attr("tabindex",0))},setDisabled:function(e,t){this.findLis(),t?this.$lis.filter('[data-original-index="'+e+'"]').addClass("disabled").find("a").attr("href","#").attr("tabindex",-1):this.$lis.filter('[data-original-index="'+e+'"]').removeClass("disabled").find("a").attr("href","#").attr("tabindex",0)},isDisabled:function(){return this.$element.is(":disabled")},checkDisabled:function(){var e=this;this.isDisabled()?this.$button.addClass("disabled").attr("tabindex",-1):(this.$button.hasClass("disabled")&&this.$button.removeClass("disabled"),-1==this.$button.attr("tabindex")&&(this.$element.data("tabindex")||this.$button.removeAttr("tabindex"))),this.$button.click(function(){return!e.isDisabled()})},tabIndex:function(){this.$element.is("[tabindex]")&&(this.$element.data("tabindex",this.$element.attr("tabindex")),this.$button.attr("tabindex",this.$element.data("tabindex")))},clickListener:function(){var t=this;this.$newElement.on("touchstart.dropdown",".dropdown-menu",function(e){e.stopPropagation()}),this.$newElement.on("click",function(){t.setSize(),t.options.liveSearch||t.multiple||setTimeout(function(){t.$menu.find(".selected a").focus()},10)}),this.$menu.on("click","li a",function(i){var n=e(this),s=n.parent().data("originalIndex"),o=t.$element.val(),a=t.$element.prop("selectedIndex");if(jQuery(".sltstandard_front .bootstrap-select").removeClass("open"),t.multiple&&i.stopPropagation(),i.preventDefault(),!t.isDisabled()&&!n.parent().hasClass("disabled")){var l=t.$element.find("option"),d=l.eq(s),r=d.prop("selected"),c=d.parent("optgroup"),h=t.options.maxOptions,p=c.data("maxOptions")||!1;if(t.multiple){if(d.prop("selected",!r),t.setSelected(s,!r),n.blur(),h!==!1||p!==!1){var u=h<l.filter(":selected").length,f=p<c.find("option:selected").length;if(h&&u||p&&f)if(h&&1==h)l.prop("selected",!1),d.prop("selected",!0),t.$menu.find(".selected").removeClass("selected"),t.setSelected(s,!0);else if(p&&1==p){c.find("option:selected").prop("selected",!1),d.prop("selected",!0);var m=n.data("optgroup");t.$menu.find(".selected").has('a[data-optgroup="'+m+'"]').removeClass("selected"),t.setSelected(s,!0)}else{var v="function"==typeof t.options.maxOptionsText?t.options.maxOptionsText(h,p):t.options.maxOptionsText,b=v[0].replace("{n}",h),$=v[1].replace("{n}",p),g=e('<div class="notify"></div>');v[2]&&(b=b.replace("{var}",v[2][h>1?0:1]),$=$.replace("{var}",v[2][p>1?0:1])),d.prop("selected",!1),t.$menu.append(g),h&&u&&(g.append(e("<div>"+b+"</div>")),t.$element.trigger("maxReached.bs.select")),p&&f&&(g.append(e("<div>"+$+"</div>")),t.$element.trigger("maxReachedGrp.bs.select")),setTimeout(function(){t.setSelected(s,!1)},10),g.delay(750).fadeOut(300,function(){e(this).remove()})}}}else l.prop("selected",!1),d.prop("selected",!0),t.$menu.find(".selected").removeClass("selected"),t.setSelected(s,!0);t.multiple?t.options.liveSearch&&t.$searchbox.focus():t.$button.focus(),(o!=t.$element.val()&&t.multiple||a!=t.$element.prop("selectedIndex")&&!t.multiple)&&t.$element.change()}}),this.$menu.on("click","li.disabled a, .popover-title, .popover-title :not(.close)",function(e){e.target==this&&(e.preventDefault(),e.stopPropagation(),t.options.liveSearch?t.$searchbox.focus():t.$button.focus())}),this.$menu.on("click","li.divider, li.dropdown-header",function(e){e.preventDefault(),e.stopPropagation(),t.options.liveSearch?t.$searchbox.focus():t.$button.focus()}),this.$menu.on("click",".popover-title .close",function(){t.$button.focus()}),this.$searchbox.on("click",function(e){e.stopPropagation()}),this.$menu.on("click",".actions-btn",function(i){t.options.liveSearch?t.$searchbox.focus():t.$button.focus(),i.preventDefault(),i.stopPropagation(),e(this).is(".bs-select-all")?t.selectAll():t.deselectAll(),t.$element.change()}),this.$element.change(function(){t.render(!1)})},liveSearchListener:function(){var t=this,n=e('<li class="no-results"></li>');this.$newElement.on("click.dropdown.data-api",function(){t.$menu.find(".active").removeClass("active"),t.$searchbox.val()&&(t.$searchbox.val(""),t.$lis.not(".is-hidden").removeClass("hide"),n.parent().length&&n.remove()),t.multiple||t.$menu.find(".selected").addClass("active"),setTimeout(function(){t.$searchbox.focus()},10)}),this.$searchbox.on("input propertychange",function(){t.$searchbox.val()?(t.options.searchAccentInsensitive?t.$lis.not(".is-hidden").removeClass("hide").find("a").not(":aicontains("+i(t.$searchbox.val())+")").parent().addClass("hide"):t.$lis.not(".is-hidden").removeClass("hide").find("a").not(":icontains("+t.$searchbox.val()+")").parent().addClass("hide"),t.$menu.find("li").filter(":visible:not(.no-results)").length?n.parent().length&&n.remove():(n.parent().length&&n.remove(),n.html(t.options.noneResultsText+' "'+t.$searchbox.val()+'"').show(),t.$menu.find("li").last().after(n))):(t.$lis.not(".is-hidden").removeClass("hide"),n.parent().length&&n.remove()),t.$menu.find("li.active").removeClass("active"),t.$menu.find("li").filter(":visible:not(.divider)").eq(0).addClass("active").find("a").focus(),e(this).focus()})},val:function(e){return"undefined"!=typeof e?(this.$element.val(e),this.render(),this.$element):this.$element.val()},selectAll:function(){this.findLis(),this.$lis.not(".divider").not(".disabled").not(".selected").filter(":visible").find("a").click()},deselectAll:function(){this.findLis(),this.$lis.not(".divider").not(".disabled").filter(".selected").filter(":visible").find("a").click()},keydown:function(t){var n,s,o,a,l,d,r,c,h,p=e(this),u=p.is("input")?p.parent().parent():p.parent(),f=u.data("this"),m={32:" ",48:"0",49:"1",50:"2",51:"3",52:"4",53:"5",54:"6",55:"7",56:"8",57:"9",59:";",65:"a",66:"b",67:"c",68:"d",69:"e",70:"f",71:"g",72:"h",73:"i",74:"j",75:"k",76:"l",77:"m",78:"n",79:"o",80:"p",81:"q",82:"r",83:"s",84:"t",85:"u",86:"v",87:"w",88:"x",89:"y",90:"z",96:"0",97:"1",98:"2",99:"3",100:"4",101:"5",102:"6",103:"7",104:"8",105:"9"};if(f.options.liveSearch&&(u=p.parent().parent()),f.options.container&&(u=f.$menu),n=e("[role=menu] li a",u),h=f.$menu.parent().hasClass("open"),!h&&/([0-9]|[A-z])/.test(String.fromCharCode(t.keyCode))&&(f.options.container?f.$newElement.trigger("click"):(f.setSize(),f.$menu.parent().addClass("open"),h=!0),f.$searchbox.focus()),f.options.liveSearch&&(/(^9$|27)/.test(t.keyCode.toString(10))&&h&&0===f.$menu.find(".active").length&&(t.preventDefault(),f.$menu.parent().removeClass("open"),f.$button.focus()),n=e("[role=menu] li:not(.divider):not(.dropdown-header):visible",u),p.val()||/(38|40)/.test(t.keyCode.toString(10))||0===n.filter(".active").length&&(n=f.$newElement.find("li").filter(f.options.searchAccentInsensitive?":aicontains("+i(m[t.keyCode])+")":":icontains("+m[t.keyCode]+")"))),n.length){if(/(38|40)/.test(t.keyCode.toString(10)))s=n.index(n.filter(":focus")),a=n.parent(":not(.disabled):visible").first().index(),l=n.parent(":not(.disabled):visible").last().index(),o=n.eq(s).parent().nextAll(":not(.disabled):visible").eq(0).index(),d=n.eq(s).parent().prevAll(":not(.disabled):visible").eq(0).index(),r=n.eq(o).parent().prevAll(":not(.disabled):visible").eq(0).index(),f.options.liveSearch&&(n.each(function(t){e(this).is(":not(.disabled)")&&e(this).data("index",t)}),s=n.index(n.filter(".active")),a=n.filter(":not(.disabled):visible").first().data("index"),l=n.filter(":not(.disabled):visible").last().data("index"),o=n.eq(s).nextAll(":not(.disabled):visible").eq(0).data("index"),d=n.eq(s).prevAll(":not(.disabled):visible").eq(0).data("index"),r=n.eq(o).prevAll(":not(.disabled):visible").eq(0).data("index")),c=p.data("prevIndex"),38==t.keyCode&&(f.options.liveSearch&&(s-=1),s!=r&&s>d&&(s=d),a>s&&(s=a),s==c&&(s=l)),40==t.keyCode&&(f.options.liveSearch&&(s+=1),-1==s&&(s=0),s!=r&&o>s&&(s=o),s>l&&(s=l),s==c&&(s=a)),p.data("prevIndex",s),f.options.liveSearch?(t.preventDefault(),p.is(".dropdown-toggle")||(n.removeClass("active"),n.eq(s).addClass("active").find("a").focus(),p.focus())):n.eq(s).focus();else if(!p.is("input")){var v,b,$=[];n.each(function(){e(this).parent().is(":not(.disabled)")&&e.trim(e(this).text().toLowerCase()).substring(0,1)==m[t.keyCode]&&$.push(e(this).parent().index())}),v=e(document).data("keycount"),v++,e(document).data("keycount",v),b=e.trim(e(":focus").text().toLowerCase()).substring(0,1),b!=m[t.keyCode]?(v=1,e(document).data("keycount",v)):v>=$.length&&(e(document).data("keycount",0),v>$.length&&(v=1)),n.eq($[v-1]).focus()}(/(13|32)/.test(t.keyCode.toString(10))||/(^9$)/.test(t.keyCode.toString(10))&&f.options.selectOnTab)&&h&&(/(32)/.test(t.keyCode.toString(10))||t.preventDefault(),f.options.liveSearch?/(32)/.test(t.keyCode.toString(10))||(f.$menu.find(".active a").click(),p.focus()):e(":focus").click(),e(document).data("keycount",0)),(/(^9$|27)/.test(t.keyCode.toString(10))&&h&&(f.multiple||f.options.liveSearch)||/(27)/.test(t.keyCode.toString(10))&&!h)&&(f.$menu.parent().removeClass("open"),f.$button.focus())}},mobile:function(){this.$element.addClass("mobile-device").appendTo(this.$newElement),this.options.container&&this.$menu.hide()},refresh:function(){this.$lis=null,this.reloadLi(),this.render(),this.setWidth(),this.setStyle(),this.checkDisabled(),this.liHeight()},update:function(){this.reloadLi(),this.setWidth(),this.setStyle(),this.checkDisabled(),this.liHeight()},hide:function(){this.$newElement.hide()},show:function(){this.$newElement.show()},remove:function(){this.$newElement.remove(),this.$element.remove()}};var o=e.fn.selectpicker;e.fn.selectpicker=n,e.fn.selectpicker.Constructor=s,e.fn.selectpicker.noConflict=function(){return e.fn.selectpicker=o,this},e(document).data("keycount",0).on("keydown",".bootstrap-select [data-toggle=arfdropdown], .bootstrap-select [role=menu], .bs-searchbox input",s.prototype.keydown).on("focusin.modal",".bootstrap-select [data-toggle=arfdropdown], .bootstrap-select [role=menu], .bs-searchbox input",function(e){e.stopPropagation()}),e(window).on("load.bs.select.data-api",function(){e(".selectpicker").each(function(){var t=e(this);n.call(t,t.data())})})}(jQuery);