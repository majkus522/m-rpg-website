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

    let remember = document.querySelector("form label.remember input").checked;
    let request = new XMLHttpRequest();
    let url = "../api/login.php?player=" + username;
    if(!remember)
        url += "&temp";
    request.open("GET", url, true);
    request.onload = function ()
    {
        if(this.status >= 200 && this.status < 300)
        {
            document.cookie = "session=" + this.responseText + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            document.cookie = "username=" + username + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "");
            location.reload();
            
        }
        else
        {
            error.textContent = this.responseText;
        }
    };
    request.setRequestHeader("Password", password);
    request.send();
});