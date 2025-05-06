const PowerOn = document.getElementById('poweron');

async function powerOn() {
    const option = {
        method: 'POST'
    };
    const response = fetch('/home/poweron', option);
    return await response.json();
}
PowerOn.addEventListener('click', async () => {
    const response = await powerOn();
    if (response.status) {
        alert(response.msg)
    }
});