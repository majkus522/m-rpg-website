let exploded = false;

document.querySelector("header div.ham").addEventListener("click", function ()
{
    exploded = !exploded;
    document.querySelector("header nav").style.display = exploded ? "flex" : "none";
});