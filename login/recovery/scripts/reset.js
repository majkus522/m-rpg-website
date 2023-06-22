let result = document.querySelector("form p");
document.querySelector("form input[type='button']").addEventListener("click", () =>
{
    let inputs = document.querySelectorAll("form input[type='password'], form input[type='text']");
    if(inputs[0].value != inputs[1].value)
        return;
    let request = new XMLHttpRequest();
    request.open("PATCH", "../../api/controllers/password-recovery/" + code, true);
    request.onload = function ()
    {
        if(this.status == 200)
        {
            result.classList.remove("error");
            result.textContent = "Password has been changed";
        }
        else
        {
            result.classList.add("error");
            result.textContent = JSON.parse(this.responseText).message;
        }
    }
    request.setRequestHeader("Password", btoa(inputs[0].value));
    request.send();
});