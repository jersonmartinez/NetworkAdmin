(function(){

	var button = document.getElementById('cn-button'),
    wrapper = document.getElementById('cn-wrapper');

    //open and close menu when the button is clicked
	var open = false;
	button.addEventListener('click', handler, false);

	function handler(){
	  if(!open){
	    // this.innerHTML = "Cerrar";
	    classie.add(wrapper, 'opened-nav');
	  }
	  else{
	    // this.innerHTML = "Menú";
		classie.remove(wrapper, 'opened-nav');
	  }
	  open = !open;
	}
	function closeWrapper(){
		classie.remove(wrapper, 'opened-nav');
	}


	
	var btnReady = document.getElementById('cn2-button'),
    wrapperReady = document.getElementById('cn2-wrapper');

    //open and close menu when the button is clicked
	var openReady = false;
	btnReady.addEventListener('click', handlerReady, false);

	function handlerReady(){
	  if(!openReady){
	    // this.innerHTML = "Cerrar";
	    classie.add(wrapperReady, 'opened2-nav');
	  }
	  else{
	    // this.innerHTML = "SysLog";
		classie.remove(wrapperReady, 'opened2-nav');
	  }
	  openReady = !openReady;
	}
	function closeWrapper(){
		classie.remove(wrapperReady, 'opened2-nav');
	}




})();
