<template>
    <div class="content-container">
        <div class="form-group d-flex flex-column">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" required>
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <a href="/signup" class="mx-auto"><small>Don't have a account?</small></a>
            <label id="labelError" class="label mx-auto"></label>
            <button id="btnLogin" @click="login" class="btn btn-primary mx-auto">Login</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

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
            axios.post('http://localhost/api/login', {
                username: this.username,
                password: this.password,
            }).then((response) => {
                if (response.status === 200) {
                    this.$router.push('/');
                    localStorage.setItem('isLoggedIn', true);
                } else {
                    document.getElementById('labelError').innerText = 'Invalid username or password';
                }
            });
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