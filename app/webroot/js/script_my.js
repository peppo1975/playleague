// http post -------------------------------------------------------------------
    
function httpPost(link, to_send)
{

    return new Promise((resolve, reject) => {

        const xhr = new XMLHttpRequest();

        xhr.open("POST", link);

        xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

        const body = JSON.stringify(to_send);

        xhr.send(body);

        xhr.onload = () => {

            if (xhr.readyState == 4 && xhr.status == 200)
            {
                var arr = JSON.parse(xhr.response);
                resolve(arr);
            }
            else
            {
                reject(new Error(xhr.statusText));
            }
        };
    });
}
    
function httpPostNoJsonResponse(link, to_send)
{

    return new Promise((resolve, reject) => {

        const xhr = new XMLHttpRequest();

        xhr.open("POST", link);

        xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

        const body = JSON.stringify(to_send);

        xhr.send(body);

        xhr.onload = () => {

            if (xhr.readyState == 4 && xhr.status == 200)
            {
                var arr = xhr.response;
                resolve(arr);
            }
            else
            {
                reject(new Error(xhr.statusText));
            }
        };
    });
}