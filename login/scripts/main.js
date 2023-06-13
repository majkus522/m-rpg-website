let button = document.querySelector("form input[type='button']");
let error = document.querySelector("form p.error");
button.addEventListener("click", () =>
{
    let inputs = document.querySelectorAll("form > label > input");
    let username = inputs[0].value;
    if(username.length == 0)
    {
        error.textContent = "Enter username";
        return;
    }
    let password = btoa(inputs[1].value);
    if(password.length == 0)
    {
        error.textContent = "Enter password";
        return;
    }

    let request = new XMLHttpRequest();
    request.open("GET", "../api/endpoints/players/" + username + "/logged");
    request.onload = function ()
    {
        let response = JSON.parse(this.responseText);
        if(this.status >= 400)
        {
            error.textContent = response.message;
        }
        else if(this.status == 200)
        {
            if(response)
            {
                let remember = document.querySelector("form input[type='checkbox']").value;
                document.cookie = "username=" + username + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "");
                document.cookie = "password=" + password + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
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