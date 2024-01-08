let error = document.querySelector(".fake p");
document.querySelector(".fake button").addEventListener("click", () => 
{
    let changes = {};
    document.querySelectorAll(".fake input").forEach(element =>
    {
        if(element.dataset.init != element.value || !fakeExists)
            changes[element.dataset.stat] = Number(element.value);
    });
    changes["clazz"] = document.querySelector(".fake select").value;
    let request = new XMLHttpRequest();
    request.open(fakeExists ? "PATCH" : "POST", "../api/fake-status/" + getCookie("username"), true);
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
    request.setRequestHeader("Session-ID", getCookie("session-id"));
    request.setRequestHeader("Session-Key", getCookie("session-key"));
    request.send(JSON.stringify(changes));
});