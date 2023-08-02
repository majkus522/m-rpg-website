let result = document.querySelector("form p");
document.querySelector("form input[type='button']").addEventListener("click", () =>
{
    let inputs = document.querySelectorAll("form input[type='password'], form input[type='text']");
    if(inputs[0].value != inputs[1].value)
        return;
    let request = new XMLHttpRequest();
    request.open("PATCH", "../../api/password-recovery.php?code=" + code, true);
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
            result.textContent = this.responseText;
        }
    }
    request.send(JSON.stringify({
        password: btoa(inputs[0].value)
    }));
});