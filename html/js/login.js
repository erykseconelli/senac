const Login = document.getElementById("login");

async function Authenticate() {
    const form = document.getElementById("login-form");
    const formData = new FormData(form);
    const options = {
        method: "POST",
        body: formData,
    }
}