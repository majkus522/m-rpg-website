function getCookie(name)
{
    let part = document.cookie.split("; ");
    let placeholder = "";
    part.forEach(element => 
    {
        if(element.startsWith(name))
        {
            placeholder = element.replace(name + "=", "");
            return;
        }
    });
    return placeholder;
}