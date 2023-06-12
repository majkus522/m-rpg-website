let button = document.querySelector("form input[type='button']");
button.disabled = true;
button.addEventListener("click", () =>
{
    let inputs = document.querySelectorAll("form > label > input");
    let username = inputs[0].value;
    let password = btoa(inputs[1].value);

    let request = new XMLHttpRequest();
    request.open("GET", "../api/endpoints/players/" + username + "/logged");
    request.onload = function ()
    {
        console.log(this.responseText);
        if(this.status >= 400)
        {
            document.querySelector("form p.error").textContent = JSON.parse(this.responseText).message;
        }
    };
    request.setRequestHeader("Password", password);
    request.send();
});
let inputsTyped = [false, false];
document.querySelector("form input[type='text']").addEventListener("input", (event) =>
{
    if(event.target.value.length > 0)
        inputsTyped[0] = true;
    else
        inputsTyped[0] = false;
    button.disabled = !(inputsTyped[0] && inputsTyped[1]);
});
document.querySelector("form input[type='password']").addEventListener("input", (event) =>
{
    if(event.target.value.length > 0)
        inputsTyped[1] = true;
    else
        inputsTyped[1] = false;
    button.disabled = !(inputsTyped[0] && inputsTyped[1]);
});