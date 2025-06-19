<template>
    <header>
        <NavBar />
    </header>
    <div class="content-container">
        <form @submit.prevent="handleLogin" class="form-group d-flex flex-column">
            <h1 class="text-center">Login</h1>
            <label for="username">Username</label>
            <input type="text" class="form-control" v-model="username" placeholder="Username" required>
            <label for="password">Password</label>
            <input type="password" class="form-control" v-model="password" placeholder="Password" required>
            <a href="/signup" class="mx-auto"><small>Don't have a account?</small></a>
            <label v-show="errorMessage" class="mx-auto alert alert-danger mt-3">{{ errorMessage }}</label>
            <button id="btnLogin" @click="login" class="btn btn-primary mx-auto">Login</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';
import { jwtDecode } from 'jwt-decode';

export default {
    name: 'Login',
    components: {
        NavBar,
    },
    data() {
        return {
            username: '',
            password: '',
            errorMessage: '',
        };
    },
    methods: {
        async handleLogin() {
            try {
                const response = await axios.post('http://localhost/api/login', {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    username: this.username,
                    password: this.password,
                });

                if (response.status === 200) {
                    let decoded = jwtDecode(response.data.token);

                    localStorage.setItem('token', response.data.token);
                    localStorage.setItem('user_id', decoded.user_id);
                    localStorage.setItem('username', decoded.username);
                    localStorage.setItem('role', decoded.role);
                    localStorage.setItem('isLoggedIn', true);
                    this.$router.push('/');
                } else {
                    this.errorMessage = 'Invalid username or password';
                }
            } catch (error) {
                this.errorMessage = error.response.data.message;
            }
        }
       
    },
};
</script>

<style scoped>
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