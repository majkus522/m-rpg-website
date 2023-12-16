let button = document.querySelector("form input[type='button']");
let loading = document.querySelector("div.loading");
let error = document.querySelector("form p");
button.addEventListener("click", () =>
{
    loading.style.display = "block";
    button.style.display = "none";
    let input = document.querySelector("form input[type='text']").value;
    let url = "../../api/password-recovery";
    let body = {};
    if(input.includes("@"))
        body.email = input;
    else
        body.username = input;
    let request = new XMLHttpRequest();
    request.open("POST", url, true);
    request.onload = function ()
    {
        loading.style.display = "none";
        button.style.display = "block";
        if(this.status >= 200 && this.status < 300)
        {
            error.classList.remove("error");
            error.textContent = "Mail has been sent";
        }
        else
        {
            error.classList.add("error");
            error.textContent = JSON.parse(this.responseText).message;
        }
    }
    request.send(JSON.stringify(body));
});