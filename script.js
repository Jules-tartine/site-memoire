document.addEventListener("DOMContentLoaded", function(){
    const menuLinks = document.querySelectorAll("#menu-principal a");
    const pages = document.querySelectorAll(".page");

    menuLinks.forEach(link => {
        link.addEventListener("click", function(e){
            e.preventDefault();
            const targetPage = this.getAttribute("data-page");

            // Masquer toutes les pages
            pages.forEach(page => {
                page.classList.remove("active");
            });

            // Afficher la page cible
            const pageToShow = document.getElementById(targetPage);
            if(pageToShow) {
                pageToShow.classList.add("active");
            }
        });
    });
});
