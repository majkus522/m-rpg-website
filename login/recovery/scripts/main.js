let result = document.querySelector("form p");
document.querySelector("form input[type='button']").addEventListener("click", () =>
{
    let input = document.querySelector("form input[type='text']").value;
    let url = "../../api/password-recovery.php?";
    if(input.includes("@"))
        url += "email";
    else
        url += "username";
    url += "=" + input;
    let request = new XMLHttpRequest();
    request.open("GET", url, true);
    request.onload = function ()
    {
        if(this.status == 200)
        {
            result.classList.remove("error");
            result.textContent = "Mail has been sent";
        }
        else
        {
            result.classList.add("error");
            result.textContent = this.responseText;
        }
    }
    request.send();
});