let button = document.querySelector("form input[type='button']");
let error = document.querySelector("form p.error");
button.addEventListener("click", () =>
{
    let username = document.querySelector("form label.username input").value;
    if(username.length == 0)
    {
        error.textContent = "Enter username";
        return;
    }
    let password = btoa(document.querySelector("form label.password input").value);
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
                let remember = document.querySelector("form label.remember input").value;
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