const mqSmall = window.matchMedia('(max-width: 600px)');
const navBar = document.querySelector('nav');
const header = document.querySelector('header');
const menuButton = document.querySelector('.menu-button');
const clickedClassName = 'button-clicked-active';
menuButton.addEventListener('click', () => {
    navBar.style.display = navBar.style.display === 'none' ? 'block' : 'none';
    menuButton.classList.contains(clickedClassName) ?
        menuButton.classList.remove(clickedClassName) :
        menuButton.classList.add(clickedClassName);

})
const menuBarHandler = (e) => {
    if(e.matches){
        navBar.style.display = 'none';
        menuButton.style.display = 'block';
        console.log(menuButton.style.display)
    }else{
        navBar.style.display = 'block';
        menuButton.style.display = 'none';
    }
}
mqSmall.addEventListener('change', menuBarHandler)
menuBarHandler(mqSmall);
