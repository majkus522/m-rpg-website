let button = document.querySelector("form input[type='button']");
let loading = document.querySelector("div.loading");
let error = document.querySelector("form p");
button.addEventListener("click", () =>
{
    loading.style.display = "block";
    button.style.display = "none";
    let inputs = document.querySelectorAll("form input[type='password'], form input[type='text']");
    if(inputs[0].value != inputs[1].value)
        return;
    let request = new XMLHttpRequest();
    request.open("PATCH", "../../api/password-recovery/" + code, true);
    request.onload = function ()
    {
        loading.style.display = "none";
        button.style.display = "block";
        if(this.status >= 200 && this.status < 300)
        {
            error.classList.remove("error");
            error.textContent = "Password has been changed";
        }
        else
        {
            error.classList.add("error");
            error.textContent = JSON.parse(this.responseText).message;
        }
    }
    request.send(JSON.stringify({
        password: btoa(inputs[0].value)
    }));
});