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
        let response = JSON.parse(this.responseText);
        let error = document.querySelector("form p.error");
        if(this.status >= 400)
        {
            error.textContent = response.message;
        }
        else if(this.status == 200)
        {
            if(response)
            {
                let remember = document.querySelector("form input[type='checkbox']").value;
                document.cookie = "username=" + username + ";path=/" + (!remember ? (";max-age=" + (60)) : "");
                document.cookie = "password=" + password + ";path=/" + (!remember ? (";max-age=" + (60)) : "") + ";secure";
                location.reload();
            }
            else
            {
                error.textContent = "Incorect password";
            }
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