// document.addEventListener("DOMContentLoaded", function(event) {
   
//     const showNavbar = (toggleId, navId, bodyId, headerId) =>{
//     const toggle = document.getElementById(toggleId),
//     nav = document.getElementById(navId),
//     bodypd = document.getElementById(bodyId),
//     headerpd = document.getElementById(headerId)
    

//     if(toggle && nav && bodypd && headerpd){
//     toggle.addEventListener('click', ()=>{
//     nav.classList.toggle('show')
//     toggle.classList.toggle('bx-x')
//     bodypd.classList.toggle('body-pd')
//     headerpd.classList.toggle('body-pd')
//     })
//     }
//     }
    
//     showNavbar('header-toggle','nav-bar','body-pd','header')
    
//     /*===== LINK ACTIVE =====*/
//     const linkColor = document.querySelectorAll('.nav_link')
    
//     function colorLink(){
//     if(linkColor){
//     linkColor.forEach(l=> l.classList.remove('active'))
//     this.classList.add('active')
//     }
//     }
//     linkColor.forEach(l=> l.addEventListener('click', colorLink))
    
//     });


     document.addEventListener("DOMContentLoaded", function(event) {


        const showNavbar = (toggleId, navId, bodyId, headerId) => {
            const toggle = document.getElementById(toggleId),
                  nav = document.getElementById(navId),
                  bodypd = document.getElementById(bodyId),
                  headerpd = document.getElementById(headerId)
   
            // Set the navbar to be shown by default
            nav.classList.add('show');  // Ensure the 'show' class is added initially
            bodypd.classList.add('body-pd'); // Add the body padding class initially
            headerpd.classList.add('body-pd'); // Add the header padding class initially
   
            if (toggle && nav && bodypd && headerpd) {
                toggle.addEventListener('click', () => {
                    // Toggle the navbar visibility when the button is clicked
                    nav.classList.toggle('show');           // Toggle visibility of the nav
                    toggle.classList.toggle('bx-x');        // Toggle the "x" icon (if any)
                    bodypd.classList.toggle('body-pd');    // Toggle body padding
                    headerpd.classList.toggle('body-pd');  // Toggle header padding
                });
            }
        }
   
        showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');
   
        /*===== LINK ACTIVE =====*/
        const linkColor = document.querySelectorAll('.nav_link')
   
        function colorLink() {
            if (linkColor) {
                linkColor.forEach(l => l.classList.remove('active')) // Remove active class
            }
            this.classList.add('active') // Add active class to the clicked link
        }
   
        linkColor.forEach(l => l.addEventListener('click', colorLink))
   
    });
   

