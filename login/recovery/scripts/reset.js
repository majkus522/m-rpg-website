let result = document.querySelector("form p");
document.querySelector("form input[type='button']").addEventListener("click", () =>
{
    let inputs = document.querySelectorAll("form input[type='password']");
    if(inputs[0].value != inputs[1].value)
        return;
    let request = new XMLHttpRequest();
    request.open("PATCH", "../../api/controllers/password-recovery/" + code, true);
    request.onload = function ()
    {
        if(this.status == 200)
        {
            result.textContent = "Password has been  changed";
        }
        else
        {
            result.textContent = JSON.parse(this.responseText).message;
        }
    }
    request.setRequestHeader("Password", btoa(inputs[0].value));
    request.send();
});