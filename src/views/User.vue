<template>
    <header>
        <NavBar />
    </header>

    <div class="content-container p-5">
        <div>
            <h1>User Details</h1>
            <div class="form-group d-flex flex-column">
                <label for="email">Email address</label>
                <input type="email" class="form-control" v-model="user.email" placeholder="Enter email" required>
                <label for="username">Username</label>
                <input type="text" class="form-control" v-model="user.username" placeholder="Enter username" required>
                <label for="password">Password</label>
                <input type="password" class="form-control" v-model="user.password" placeholder="Password" required>
                <label v-show="errorMessage" class="mx-auto alert alert-danger">{{ errorMessage }}</label>
                <div>
                    <button v-if="editMode" class="btn btn-danger mt-3 mr-3">Cancel</button>
                    <button v-if="editMode" class="btn btn-success mt-3 mx-auto" @click="editUser()">Confirm</button>
                    <button v-else class="btn btn-primary mt-3 mx-auto" @click="editUser()">Edit</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';

export default {
    name: 'User',
    components: {
        NavBar,
    },
    data() {
        return {
        editMode: false,
        user: {
            id: null,
            username: '',
            email: '',
            password: ''
        }
      };
    },
    mounted() {

    },
    methods: {
        editUser() {
            try {
                const response = axios.post('http://localhost/api/user', {
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    id: this.id,
                    email: this.email,
                    username: this.username,
                    password: this.password
                })

                if (response.status === 201) {
                    this.errorMessage = '';
                    this.successMessage = 'User edited successfully';
                } else {
                    this.errorMessage = 'Error editing user';
                } 
            } catch (error) {
                this.errorMessage = error.response.data.message;
            }
        },
        resetMessages() {
            setTimeout(() => {
                this.errorMessage = '';
                this.successMessage = '';
            }, 3000);
        },
    }
};

</script>

<style scoped>
.content-container {
    height: 100vh;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 1s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}

.alert {
    transition: opacity 1s ease-in-out;
}

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
