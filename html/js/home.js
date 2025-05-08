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

const PowerOff = document.getElementById('poweroff');

async function powerOff() {
    const option = {
        method: 'POST'
    };
    const response = fetch('/home/poweroff', option);
    return await response.json();
}
PowerOn.addEventListener('click', async () => {
    const response = await powerOff();
    if (response.status) {
        alert(response.msg)
    }
});

async function proc() {
    console.log('Aguardando o evento de clique...');
}
while (true) {
    await new Promise(async (resolve) => setTimeout(() => { proc(); resolve(); }, 1000)); // Espera 3 segundos
}