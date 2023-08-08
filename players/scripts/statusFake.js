let error = document.querySelector(".fake p");
document.querySelector(".fake button").addEventListener("click", () => 
{
    let changes = {};
    document.querySelectorAll(".fake input").forEach(element =>
    {
        if(element.dataset.init != element.value)
            changes[element.dataset.stat] = Number(element.value);
    });
    let request = new XMLHttpRequest();
    request.open(fakeExists ? "PATCH" : "POST", "../api/endpoints/fake-status/" + getCookie("username"), true);
    request.onload = function ()
    {
        if(this.status >= 200 && this.status < 300)
        {
            error.textContent = "Fake status changed";
            error.classList.remove("error");
        }
        else
        {
            error.textContent = JSON.parse(this.responseText).message;
            error.classList.add("error");
        }
    };
    request.setRequestHeader("Session-Key", getCookie("session"));
    request.setRequestHeader("Session-Type", "website");
    request.send(JSON.stringify(changes));
});