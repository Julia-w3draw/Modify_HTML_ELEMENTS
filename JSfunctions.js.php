
//HTMLE_SETTINGS:
var _HTMLE_SETTINGS={
	_settings_mask:undefined, //body size
	_set_ons:[],                      
	_resize:'',                       
	_m_subject:{},                    
	_subject:'',                      
	_metrics_before_move_resize:{},   
	_mousedown_on_moveresize:{},      
	_settings_on:false,               
	_drag_move:'',                    
	_move_resize_border:20,           
	_resize_direction:'',             
	
	//2._ev_key_down:
	_ev_key_down:function(){
		if(!(event.ctrlKey&&event.shiftKey)){return}		
		this._init()
		this._settings_mask.innerHTML=''
		this._settings_on=false
		this._settings_mask.style.display='block'
		var all_elements=document.getElementsByTagName('*')
		this._set_ons=[]
		for(var c=0;c<all_elements.length;c++){if(all_elements[c].hasAttribute('_SETTINGS_ON')==true) this._set_ons.push(all_elements[c])}
		for(var c=0;c<this._set_ons.length;c++){
			this._m_subject=this._set_ons[c]._get_metrics_abs()
			if((this._m_subject.w>40)&&(this._m_subject.h>40)){				
				var _menu_setting=document.createElement('div')
				_menu_setting.my_subject=this._set_ons[c]
				_menu_setting.className='_menu_setting'
				_menu_setting.style.cssText='width:'+this._m_subject.w+'; height:'+this._m_subject.h+' ; left:'+this._m_subject.x+' ; top:'+this._m_subject.y
				+' ; background:rgba(250,5,15,0.2); position:absolute'
				this._settings_mask.appendChild(_menu_setting)
				var move_resize=document.createElement('div')
				move_resize.id='move_resize'
				move_resize.innerHTML='Move/Resize'
				_menu_setting.appendChild(move_resize)
				move_resize.onclick=this._ev_click_settings_moveresize.bind(this)
			}else{
				console.log(this._set_ons[c].id+'element with the attribute _SETTINGS_ON to small')
			}
		}
	}, //:2._ev_key_down
	
	//3._ev_key_up:
	_ev_key_up:function(){
		if(this._settings_on==false){
			this._settings_mask.style.display='none'
			this._settings_mask.innerHTML=''
		}
	}, //:3._ev_key_up	
	
	//4._ev_click_settings_moveresize:
	_ev_click_settings_moveresize:function(){	
		var _m=event.target.parentNode._get_metrics_abs()		
		this._settings_mask.innerHTML=''
		this._settings_on=true 		
		this._subject=event.target.parentNode.my_subject				
		var _div_move_resize=this._create_move_resize(_m.w,_m.h,_m.x,_m.y)
		this._settings_mask.appendChild(_div_move_resize)
		this._metrics_before_move_resize=_div_move_resize._get_metrics_abs()
		this._settings_mask.onmouseup=this._ev_mouseup_on_settings_mask.bind(this)
		event.stopPropagation()
	}, //:4._ev_click_settings_moveresize	
	
	//5._ev_mousedown_on_move:
	_ev_mousedown_on_move:function(){
		this._drag_move='move'
		this._mousedown_on_moveresize.x=event.pageX		
		this._mousedown_on_moveresize.y=event.pageY
		this._settings_mask.onmouseup=this._ev_mouseup_on_settings_mask.bind(this)
		event.stopPropagation()
	}, //:5._ev_mousedown_on_move	
	
	//6._ev_mouseup_on_settings_mask:
	_ev_mouseup_on_settings_mask:function(){
		if((this._mousedown_on_moveresize.x==event.pageX)&&(this._mousedown_on_moveresize.y==event.pageY)){
			this._settings_mask.innerHTML=''
			this._settings_on=false
			this._settings_mask.style.display='none'
			this._settings_mask.onmouseup=function(){}		
			return
		}
		var _m=this._metrics_before_move_resize
		var _actual_m=this._resize._get_metrics_abs()	
		this._settings_mask.innerHTML=''	
		var d_x=event.pageX-this._mousedown_on_moveresize.x
		var d_y=event.pageY-this._mousedown_on_moveresize.y
		_m.x=_m.x+d_x; _m.y=_m.y+d_y
		if(this._drag_move=='move'){
			this._settings_mask.appendChild(this._create_move_resize(_actual_m.w,_actual_m.h,_m.x,_m.y))
			this._subject.style.left=_m.x+'px'
			this._subject.style.top=_m.y+'px'
		}else{
			if(this._drag_move=='resize'){				
				switch(this._resize_direction){
					//left_up
					case'lu':					
						this._settings_mask.appendChild(this._create_move_resize(_actual_m.w-d_x,_actual_m.h-d_y,_actual_m.x+d_x,_actual_m.y+d_y))
						this._subject.style.width=_actual_m.w-d_x,_actual_m.h-d_y+'px'
						this._subject.style.height=_actual_m.h-d_y+'px'
						this._subject.style.left=_actual_m.x+d_x+'px'
						this._subject.style.top=_actual_m.y+d_y+'px'
					break;						
					//up
					case'u':
						this._settings_mask.appendChild(this._create_move_resize(_actual_m.w,_actual_m.h-d_y,_actual_m.x,_actual_m.y+d_y))
						this._subject.style.width=_actual_m.w+'px'
						this._subject.style.height=_actual_m.h-d_y+'px'
						this._subject.style.left=_actual_m.x+'px'
						this._subject.style.top=_actual_m.y+d_y+'px'
					break;						
					//up_right
					case'ru':
						this._settings_mask.appendChild(this._create_move_resize(_actual_m.w+d_x,_actual_m.h-d_y,_actual_m.x,_actual_m.y+d_y))					
						this._subject.style.width=_actual_m.w+d_x+'px'
						this._subject.style.height=_actual_m.h-d_y+'px'
						this._subject.style.left=_actual_m.x+'px'
						this._subject.style.top=_actual_m.y+d_y+'px'
					break;						
					//left
					case'l':					
						this._settings_mask.appendChild(this._create_move_resize(_actual_m.w-d_x,_actual_m.h,_actual_m.x+d_x,_actual_m.y))
						this._subject.style.width=_actual_m.w-d_x+'px'
						this._subject.style.height=_actual_m.h+'px'
						this._subject.style.left=_actual_m.x+d_x+'px'
						this._subject.style.top=_actual_m.y+'px'
					break;
					//right
					case'r':
						this._settings_mask.appendChild(this._create_move_resize(_actual_m.w+d_x,_actual_m.h,_actual_m.x,_actual_m.y))
						this._subject.style.width=_actual_m.w+d_x+'px'
						this._subject.style.height=_actual_m.h+'px'
						this._subject.style.left=_actual_m.x+'px'
						this._subject.style.top=_actual_m.y+'px'
					break;
					//left_down
					case'ld':
						this._settings_mask.appendChild(this._create_move_resize(_actual_m.w-d_x,_actual_m.h+d_y,_actual_m.x+d_x,_actual_m.y))
						this._subject.style.width=_actual_m.w-d_x+'px'
						this._subject.style.height=_actual_m.h+d_y+'px'
						this._subject.style.left=_actual_m.x+d_x+'px'
						this._subject.style.top=_actual_m.y+'px'
					break;
					//down
					case'd':
						this._settings_mask.appendChild(this._create_move_resize(_actual_m.w,_actual_m.h+d_y,_actual_m.x,_actual_m.y))
						this._subject.style.width=_actual_m.w+'px'
						this._subject.style.height=_actual_m.h+d_y+'px'
						this._subject.style.left=_actual_m.x+'px'
						this._subject.style.top=_actual_m.y+'px'
					break;
					//right_down
					case'rd':
						this._settings_mask.appendChild(this._create_move_resize(_actual_m.w+d_x,_actual_m.h+d_y,_actual_m.x,_actual_m.y))
						this._subject.style.width=_actual_m.w+d_x+'px'
						this._subject.style.height=_actual_m.h+d_y+'px'
						this._subject.style.left=_actual_m.x+'px'
						this._subject.style.top=_actual_m.y+'px'
					break;
				}
			}
		}
		this._settings_mask.onmouseup=function(){}		
	}, //:6._ev_mouseup_on_settings_mask
	
	//8._ev_mousedown_on_moveresize:
	_ev_mousedown_on_moveresize:function(){
		this._drag_move='resize'
		this._mousedown_on_moveresize.x=event.pageX		
		this._mousedown_on_moveresize.y=event.pageY
		this._resize_direction=event.target.id
		this._settings_mask.onmouseup=this._ev_mouseup_on_settings_mask.bind(this)
		event.stopPropagation()	
	}, //:8._ev_mousedown_on_moveresize

	
	//1._init:
	_init:function(){
		this._settings_mask=document.getElementById('_settings_mask')
		if(this._settings_mask!=undefined){this._settings_mask.parentNode.removeChild(this._settings_mask)}
		this._settings_mask=document.createElement('div')
		this._settings_mask.id='_settings_mask'
		this._settings_mask.style.cssText='background:rgba(70,54,51,0.2);display:none;position:absolute;width:100%;height:100%;'
		document.body.appendChild(this._settings_mask)			
		document.body.onkeydown=this._ev_key_down.bind(this)
		document.body.onkeyup=this._ev_key_up.bind(this)		
	},	
//:1._init


//FUNCTIONS:

_create_move_resize:function(w,h,x,y){	
	//sanity daca width sau height este mai mic decat minim
	if(w<this._move_resize_border*4)w=this._move_resize_border*4
	if(h<this._move_resize_border*4)h=this._move_resize_border*4				
	//create div _move
	var _move=document.createElement('div')
	_move.id='move'
	_move.style.cssText='width:'+(w-40)+'; height:'+(h-40)+' ; left:'+20+' ; top:'+20
	+' ; background:rgba(0,0,255,0.5); position:absolute'
	//create div resize
	this._resize=document.createElement('div')
	this._resize.id='resize'
	this._resize.style.cssText='width:'+w+'; height:'+h+' ; left:'+x+' ; top:'+y
	+' ; background:rgba(0,0,0,0.5); position:absolute'

	//create table_container
	var table_container=document.createElement('div')
	table_container.id='table_container'
	table_container.cssText='width:'+w+'; height:'+h+' ; left:'+x+' ; top:'+y
	+' ; background:rgba(0,0,0,0); position:absolute'
	this._resize.appendChild(table_container)
	//compose string table
	var table_string="<table id='margin_table' style='width: 100%; height: 100%; margin: 0px; border:none' cellspacing='0' cellpadding='0' border='1'>\
					<tr style=' height:"+this._move_resize_border+"px; background: #A5A5A5;'>\
						<td style='width:"+this._move_resize_border+"px;background: #A5A5A5;'><div id='lu' MARGIN style='width:100%; height:100%; background: #45ac89' ></div>	</td>\
						<td><div id='u' MARGIN style='width:100%; height:100%; background: #45ac89' ></div></td>\
						<td style='width:"+this._move_resize_border+"px'><div id='ru' MARGIN style='width:100%; height:100%; background: #45ac89' ></div></td>\
					</tr>\
					<tr>\
						<td style='background: #A5A5A5; width:"+this._move_resize_border+"px'><div id='l' MARGIN style='width:100%; height:100%; background: #45ac89' ></div></td>\
						<td><div id='c' style='width:100%; height:100%; background: #FCFCFC; visibility: hidden'> c</div></td>\
						<td style='background: #A5A5A5; width:"+this._move_resize_border+"px'><div id='r' MARGIN style='width:100%; height:100%; background: #45ac89' ></div></td>\
					</tr>\
					<tr style='height:"+this._move_resize_border+"px; background: #A5A5A5;'>\
						<td style='width:"+this._move_resize_border+"px'><div id='ld' MARGIN style='width:100%; height:100%; background: #45ac89' ></div></td>\
						<td><div id='d' MARGIN style='width:100%; height:100%; background: #45ac89' ></div></td>\
						<td style='width:"+this._move_resize_border+"px; background: #A5A5A5;'><div id='rd' MARGIN style='width:100%; height:100%; background: #45ac89' ></div></td>\
					</tr>\
				</table>"
	//add table to the page
	table_container.innerHTML=table_string
	
	//get divs with attribute MARGIN from the table
	this._resize.innerHTML=table_string
	var _all_elemen=this._resize.getElementsByTagName('*')
	for(var c=0;c<_all_elemen.length;c++){
		if(_all_elemen[c].hasAttribute('MARGIN')==true){
			//atasez evenimentul onmousedown pe divs din table
			_all_elemen[c].onmousedown=this._ev_mousedown_on_moveresize.bind(this)
			event.stopPropagation()		
		}
	}

	this._resize.appendChild(_move)
	//atasez evenimentul onmousedown pe div move
	_move.onmousedown=this._ev_mousedown_on_move.bind(this)
	return this._resize
},
}//:HTMLE_SETTINGS


//Functions:
function _px2number(_NOpx){
	if (_NOpx==undefined) return undefined
	var temp=_NOpx.replace("px","")
	var retu=parseInt(temp)
	if (retu.toString().length!=temp.length) return undefined
	return retu
}

HTMLElement.prototype._get_metrics_abs=function(){
	var retu={}
   var temp=this._get_pos_abs()
	retu.x=temp.x;
   retu.y=temp.y;
	retu.w=this._get_W();
   retu.h=this._get_H();
return retu
}


//absolute position
HTMLElement.prototype._get_pos_abs=function(_target_parent,debug){
	var retu={x:0,y:0};var xel=this
	while(xel && xel!=_target_parent){
		retu.x += (xel.offsetLeft + xel.clientLeft);      
		retu.y += (xel.offsetTop  + xel.clientTop);
		if(debug==true){         
         _l('xel.id: ',xel.id,' x:',retu.x,' y:',retu.y)
         //_l('y: ',xel.offsetTop,xel.scrollTop, xel.clientTop)
         }
		xel = xel.offsetParent;
	}
	return retu
}

//_get_W
HTMLElement.prototype._get_W=function(){
	var _width=this.offsetWidth
	if(_width==0)_width=_px2number(this.style.width)
	if(!isNaN(_width))return _width
	return 0
}

//_get_H
HTMLElement.prototype._get_H=function(){
	var _height=this.offsetHeight
	if(_height==0)_height=_px2number(this.style.height)
	if(!isNaN(_height))return _height
	return 0
}
//:Functions











