var d = new Date();
d.setTime(d.getTime() + (365*24*60*60*1000));

			const inputs2 = [].slice.call(document.querySelectorAll("input"));
			
			/* update on input change */ 
			inputs2.forEach(input => input.addEventListener("change", handleUpdate));
			
			/* update on mouse movement 
			inputs.forEach(input => input.addEventListener("mousemove", handleUpdate)); */
			
			/* Handle update (check if its color or not ) */
			function handleUpdate(e) {
			  
			  /* Makes sure any other inputs dont mess with it */
			  if (this.type === "color") {
			  
			  /* Chops off the # */
				var expires = "expires="+ d.toUTCString();
				document.cookie = "neon-color=" + this.value.substring(1) + ";" + expires + ";path=/";
				window.location.reload(false)
			   
				}
			}

function toDefault() {
	document.cookie = 'neon-color=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
	document.documentElement.style.setProperty("--color", "initial");
}
			
		