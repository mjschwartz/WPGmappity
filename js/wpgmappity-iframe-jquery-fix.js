

function  wpgmappity_jquery_fix() {
  //console.log(jQuery.event)

  // IE
  if (!jQuery.support.submitBubbles) {
    //wpgmappity_jquery_patch()
    alert(jQuery.event.special.submit);
    if(typeof String.prototype.trim !== 'function') {
      String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g, '');
      }
    }
}
}

function wpgmappity_jquery_patch() {

    jQuery.event.special.submit.setup = function( data, namespaces ) {
	if ( this.nodeName && this.nodeName.toLowerCase() !== "form" ) {
	  jQuery.event.add(this, "click.specialSubmit", function( e ) {
	    //var elem = e.target, type = elem.type;
	    var elem = e.target, type = jQuery.nodeName( elem, "input" ) ?  elem.type : "";

	      if ( (type === "submit" || type === "image") && jQuery( elem ).closest("form").length ) {
		trigger( "submit", this, arguments );
	      }
		});

	  jQuery.event.add(this, "keypress.specialSubmit", function( e ) {
	    //var elem = e.target, type = elem.type;
	    var elem = e.target, type = jQuery.nodeName( elem, "input" ) ?  elem.type : "";

	      if ( (type === "text" || type === "password") && jQuery( elem ).closest("form").length && e.keyCode === 13 ) {
		trigger( "submit", this, arguments );
			   }
		});

	} else {
	  return false;
	}
    };

      jQuery.event.special.change.click =  function( e ) {
	var elem = e.target, type = jQuery.nodeName( elem, "input" ) ? elem.type : "";

	if ( type === "radio" || type === "checkbox" || jQuery.nodeName( elem, "select" ) ) {
	  return testChange.call( this, e );
	}
      }

  jQuery.event.special.change.keydown = function( e ) {
    var elem = e.target, type = jQuery.nodeName( elem, "input" ) ? elem.type : "";

    if ( (e.keyCode === 13 && !jQuery.nodeName( elem, "textarea" )) ||
      (e.keyCode === 32 && (type === "checkbox" || type === "radio")) ||
	 type === "select-multiple" ) {
	   return testChange.call( this, e );
	 }
  }

}