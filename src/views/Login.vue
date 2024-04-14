<template>
    <form method="POST" class="content-container">
        <div class="form-group d-flex flex-column">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="usernameInput" name="username" placeholder="Username" required>
            <label for="password">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" required>
            <a href="/signup" class="mx-auto"><small>Don't have a account?</small></a>
            <label id="labelError" class="label mx-auto"></label>
            <button id="btnLogin" type="submit" class="btn btn-primary mx-auto">Login</button>
        </div>
    </form>
</template>

<script>
    export default {
        name: 'Login',
        data() {
            return {
                username: '',
                password: '',
            };
        },
        methods: {
            async login() {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username: this.username,
                        password: this.password,
                    }),
                });

                if (response.status === 200) {
                    this.$router.push('/');
                } else {
                    document.getElementById('labelError').innerText = 'Invalid username or password';
                }
            },
        },
    };
</script>

<style>
    .form-group {
        width: 25%;
        margin: 8% auto;
    }

    .form-control {
        margin-bottom: 10%;
    }

    .content-container {
        height: 100vh;
    }

    .btn {
        width: 30%;
    }
</style>