function display_img (image) 
{ 
    var slidPrincipal = document.querySelector('#slid'); 
    var source = image.src; 
    slidPrincipal.setAttribute('src',source);
    
}
