	
	function hinfo(e){
		//alert($(e).html());
		$('#info').slideUp();
		$('#mask, #fill').hide();
		$('#cont').fadeTo('slow',1);
		$('#mask, #fill').unbind('click', hinfo);
		return false;
	}
	
	function emailcheck(v){
		rx = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
		return rx.test(v);
	}
	
	
	var info = null;

	var form = {
		check:function(){
			// objects
			form.alt = info.find('.alt');
			form.req = info.find('label.req');
			form.alt.ul = form.alt.find('ul');
			//
			form.alert.hide();
			form.req.each(function(){
				form.alt.value = $(this).find('input, textarea').val();
				form.alt.title = $(this).find('span').html();
				// if the input doesn't have a value or if its an email and has no correct format
				if ( (form.alt.value.length<1) || ($(this).hasClass('eml') && !emailcheck(form.alt.value)) ) 
					form.alert.show();
			});
			// if the form doesn't have errors proceed to send.
			if(!form.alt.ul.find('li').is('.err')) return form.send();
			return false;
		},
		
		send:function(){
			// get values
			info.fvar = new Array;
			info.fval = new Array;
			info.find('label').each(function(i){
				var t = $(this);
				var x = t.find('input, textarea').val();
				if (x!=''){
					info.fval[i] = x;
					info.fvar[i] = t.attr('class').replace(/req /,'');
				}
			});
			info.fstr = '';
			for(var i=0; i<info.fvar.length; i++){
				info.fstr += info.fvar[i]+'='+info.fval[i];
				if (i<(info.fvar.length-1)) info.fstr+='&';
			}
			info.html('hide');
			$.ajax({
				type: 'POST',
				url:'/inc/email.php',
				data:info.fstr,
				success:form.s,
				error:form.e
			});
			return false;
		},
		
		s:function(msg){
			if(msg=='true') $('el mensaje ha sido enviado');
			else form.e();
		},
		
		e:function(){
			alert('error');
		},
		
		alert:{
			show:function(){
				form.alt.show();
				form.alt.ul.html(form.alt.ul.html()+'<li class="err">'+form.alt.title+'</li>');
			},
			hide:function(){
				form.alt.hide();
				form.alt.ul.html('');
			}
		}
	}

	
	$(document).ready(function() {
		
		$('#debug').click(function(){
			$('#cont').toggleClass('border');
			$('.ad').toggle();
		});
		
		$('#menu a').click(function(){
			// get the current classname and check if #hold has a match
			var tc = $(this).attr('class');
			if(!$('#hold *').hasClass(tc)) return true;
			// set the html of info according to the option clicked
			$('#info')
			.html($('#hold .'+tc).html())
			.slideDown('fast',function(){
				$('#cont').fadeTo('fast',0.75);
			});
			$('#mask, #fill').show().bind('click', hinfo);
			
			
			info = $('#info');
			info.find('#ctac').submit(form.check);
			return false;
		});
		
	});
