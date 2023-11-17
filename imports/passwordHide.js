let fields = document.querySelectorAll('input[type="password"]');
document.querySelector("mrpg-checkbox.show").addEventListener("click", (event) => 
{
    let value = event.target.value ? "text" : "password";
    fields.forEach((element) => element.type = value);
});