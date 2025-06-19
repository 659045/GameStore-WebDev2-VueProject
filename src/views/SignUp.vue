<template>
    <header>
        <NavBar />
    </header>
    <div class="content-container">
        <form @submit.prevent="handleSignUp">
            <div class="form-group d-flex flex-column">
                <h1 class="text-center">Sign Up</h1>
                <label for="username">Username</label>
                <input type="text" class="form-control" v-model="username" placeholder="Enter username" required>
                <label for="password">Password</label>
                <input type="password" class="form-control" v-model="password" placeholder="Password" required>
                <label v-show="errorMessage" class="mx-auto alert alert-danger">{{ errorMessage }}</label>
                <a href="/login" class="mx-auto"><small>Already have an account?</small></a>
                <button type="submit" class="btn btn-primary mt-3 mx-auto">Sign Up</button>
            </div>
        </form>
    </div>
  </template>
  
<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';

export default {
    name: 'SignUp',
    components: {
        NavBar
    },
    data() {
        return {
            username: '',
            password: '',
            errorMessage: ''
        };
    },
    methods: {
        async handleSignUp() {
            try {
                const response = await axios.post('http://localhost/api/user', {
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    username: this.username,
                    password: this.password
                })

                if (response.status === 201) {
                    this.$router.push('/login');
                } else {
                    this.errorMessage = 'Error creating user';
                }
            } catch (error) {
                this.errorMessage = error.response.data.message;
            }
        },
    }
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
  