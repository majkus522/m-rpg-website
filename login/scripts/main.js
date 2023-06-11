document.querySelector("input[type='button']").addEventListener("click", () =>
{
    let inputs = document.querySelectorAll("form > label > input");
    let username = inputs[0].value;
    let password = btoa(inputs[1].value);

    let request = new XMLHttpRequest();
    request.open("GET", "../api/endpoints/players/" + username + "/logged");
    request.onload = function ()
    {
        
    };
    request.setRequestHeader("Password", password);
    request.send();
});